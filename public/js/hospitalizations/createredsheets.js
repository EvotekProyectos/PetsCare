window.onload = function () {
  fetchAndRenderData();
  if (Pic_id !== null) {
    let fileRoute = Pic_route.startsWith("/")
      ? Pic_route.substring(1)
      : Pic_route;
    $("#preview").attr("src", ruta + fileRoute);
  } else {
    $("#preview").attr("src", imgDefault);
  }
};

$(document).ready(function () {
  $("#service_type_id").select2({
    placeholder: "Añadir Servicio",
    width: "resolve",
    closeOnSelect: false,
  });
  $("#lab_type_id").select2({
    placeholder: "Añadir Laboratorio",
    width: "resolve",
    closeOnSelect: false,
  });
  $("#imaging_type_id").select2({
    placeholder: "Añadir Imagenologia",
    width: "resolve",
    closeOnSelect: false,
  });
  // $('#product_type_id').select2({
  //     placeholder: 'Añadir Cirugia',
  //      width: 'resolve'
  // });
  $("#product_type_id").select2({
    theme: "bootstrap-5",
    dropdownParent: $("#ModalSurgeries"),
  });

  // Oculta el campo de fecha de ModalSurgeries sin tocar surgery/form.blade.php
  // (compartido con surgery.create/edit y status-surgery, que sí lo necesitan
  // visible): la fecha de esta cirugía siempre es "ahora", fijada por
  // OpenSurgeries()/SurgeryController::store() — ver use_current_date en
  // red-sheet/create.blade.php. El tipo de cirugía ocupa el ancho que deja
  // libre, para no dejar un hueco en la fila.
  const $surgeryDateGroup = $('#ModalSurgeries label[for="date"]').closest(
    ".col-md-5",
  );
  $surgeryDateGroup.hide();
  $surgeryDateGroup
    .siblings(".col-md-7")
    .removeClass("col-md-7")
    .addClass("col-md-12");
});

$("#table-container").on("click", "#btnGenerarVale", function () {
  let redsheets = [];

  $(".descontar-stock:checked").each(function () {
    redsheets.push($(this).data("redsheet"));
  });

  // Definida en vouchers/sign-modal.js: un solo SweetAlert de confirmación,
  // luego encadena store-products + generate (el médico ya no captura
  // cantidad ni firma, ver VoucherController::generate()).
  generateVoucherWithoutReview(
    "red_sheet",
    Reception_Id,
    redsheets,
    $(this),
    () => voucherTableReload(),
  );
});

function generarVale() {
  let redsheets = [];

  $(".descontar-stock:checked").each(function () {
    let id = $(this).data("redsheet");
    redsheets.push(id);
  });

  //console.log(redsheets);
}

function fetchAndRenderData(showLoader = true) {
  if (showLoader) {
    $("#table-container").hide();
    $("#table-loader").show();
  }

  // La bitácora de eventos vive en un endpoint aparte (no DataTables::of())
  // porque recap() ya aplana surgeries+redsheets en un solo array que
  // normalizeData()/renderEntryRow() procesan asumiendo forma de servicio;
  // los eventos no tienen esa forma.
  $.when(
    $.ajax({ url: route("red-sheets.recap", Reception_Id), method: "GET" }),
    $.ajax({ url: route("red-sheets.events", Reception_Id), method: "GET" }),
  ).done(function (recapResult, eventsResult) {
    const response = recapResult[0];
    const eventsData = eventsResult[0];

    const Data = response.data.flat();
    const normalizedData = normalizeData(Data);
    renderData(normalizedData, eventsData);

    if (showLoader) {
      $("#table-loader").hide();
      $("#table-container").show();
    }
    // Arrancar o detener polling según si hay vales activos
    const hayValesActivos = normalizedData.some(
      (entry) => entry.active_voucher_folio,
    );

    if (hayValesActivos) {
      startPolling();
    } else {
      stopPolling();
    }
  });
}

function normalizeData(data) {
  //console.log(data);
  return data.map((entry) => ({
    ...entry,
    lab: entry.lab || null,
    imaging: entry.imaging || null,
    service: entry.service || null,
    surgery: entry.surgery || null,

    vet: entry.vet || { name: "Desconocido" },
  }));
}

// Metadatos visuales por tipo de evento de la bitácora (ver
// ReceptionController::update() -> admission_change, ReceptionTransferController
// ::store() -> transfer, FollowUpController::store() -> followup_added; son
// los 3 únicos event_type que existen hoy en ReceptionEvent). Íconos
// reutilizados de los que ya usa esta misma pantalla para las acciones
// equivalentes (Cambiar admisión / Trasladar / Seguimientos), para no
// introducir un vocabulario visual nuevo.
const EVENT_TYPE_META = {
  admission_change: {
    icon: "clarity--two-way-arrows-line",
    color: "#0455A0",
    bg: "#E6F1FB",
    label: "Cambio de admisión",
  },
  followup_added: {
    icon: "clarity--note-edit-line",
    // Mismo verde que ya usa ServicesTable para "Vacuna" (ver
    // serviceTypeColors en appointments/create.js) — categoría distinta,
    // pero se reutiliza el mismo tono para no sumar otro verde al proyecto.
    color: "#16A34A",
    bg: "#E8F6EC",
    label: "Seguimiento agregado",
  },
  transfer: {
    icon: "fas fa-exchange-alt",
    color: "#993C1D",
    bg: "#FAECE7",
    label: "Traslado",
  },
};
const DEFAULT_EVENT_META = {
  icon: "fas fa-circle",
  color: "#6c757d",
  bg: "#F1F3F6",
  label: "Evento",
};

// Qué días quedan expandidos/colapsados en el acordeón de "Resumen días
// hospitalizado". Vive a nivel de módulo (no dentro de renderData) para
// sobrevivir entre llamadas: fetchAndRenderData() se vuelve a disparar tras
// cada alta de servicio o cambio de vale, y sin esto el acordeón se
// resetearía al estado default (solo el día más reciente expandido) cada
// vez, haciendo que el usuario pierda su lugar. Limitación conocida: este
// estado vive solo en memoria del tab actual, así que un F5 completo sí lo
// reinicia — no se intentó persistirlo (localStorage, querystring) para no
// sumar complejidad sin que se haya pedido explícitamente.
const dayExpandedState = {};

$(document).on("shown.bs.collapse", ".day-collapse-body", function () {
  dayExpandedState[$(this).data("day")] = true;
});
$(document).on("hidden.bs.collapse", ".day-collapse-body", function () {
  dayExpandedState[$(this).data("day")] = false;
});

function renderData(data, events = []) {
  // recap() (ver RedSheetController::recap()) arma la respuesta como
  // [surgeries, redsheets] y fetchAndRenderData() la aplana con .flat() en
  // ESE orden fijo: todas las cirugías primero, luego todos los redsheets,
  // sin importar cuándo se registró cada uno. Como groupedData más abajo
  // solo agrupa por día preservando el orden que traiga data, las cirugías
  // terminaban siempre arriba de la tabla de su día. Se ordena aquí por
  // created_at (hora real de alta, la tienen ambos tipos) ANTES de agrupar,
  // para que cirugías y servicios se intercalen según cuándo se registraron
  // de verdad — no se reordena visualmente en el HTML, se corrige el orden
  // de origen de los datos.
  data = [...data].sort(
    (a, b) => new Date(a.created_at) - new Date(b.created_at),
  );

  // "Generar Vale" solo tiene sentido si al menos una fila (de cualquier
  // día, de CUALQUIERA de los 3 tipos — lab/imaging/service) tiene checkbox
  // disponible — mismo criterio que ya decide el checkbox dentro de
  // renderVoucherCell() para cada tipo. Antes solo revisaba entry.service/
  // entry.serv, así que si el único insumo vale-able del día era un
  // Laboratorio o una Imagenología, el checkbox salía en la fila pero el
  // botón nunca aparecía. Se recalcula en cada render, y renderData() se
  // llama de nuevo tras cada alta de servicio y tras generar/cancelar un
  // vale (ver fetchAndRenderData()/voucherTableReload()), así que no queda obsoleto.
  const hasIssuableRow = data.some(
    (entry) =>
      (entry.lab &&
        entry.laboratory &&
        entry.laboratory.ES_ALMACENABLE === "S" &&
        !entry.active_voucher_folio) ||
      (entry.imaging &&
        entry.img &&
        entry.img.ES_ALMACENABLE === "S" &&
        !entry.active_voucher_folio) ||
      (entry.service &&
        entry.serv &&
        entry.serv.ES_ALMACENABLE === "S" &&
        !entry.active_voucher_folio),
  );

  const groupedData = data.reduce((acc, item) => {
    acc[item.day_count] = acc[item.day_count] || [];
    acc[item.day_count].push(item);
    return acc;
  }, {});

  const eventsByDay = events.reduce((acc, event) => {
    acc[event.day_count] = acc[event.day_count] || [];
    acc[event.day_count].push(event);
    return acc;
  }, {});

  // Unión de días con servicios y/o eventos (ej. un traslado sin ningún
  // servicio registrado ese día igual debe mostrar su "DÍA N").
  const allDays = Array.from(
    new Set([
      ...Object.keys(groupedData).map(Number),
      ...Object.keys(eventsByDay).map(Number),
    ]),
  ).sort((a, b) => a - b);

  $("#table-container").empty();

  // Solo el día más reciente (day_count más alto) arranca expandido; el
  // resto arranca colapsado. Días ya vistos por el usuario conservan lo que
  // haya elegido (ver dayExpandedState arriba).
  const mostRecentDay = allDays.length ? allDays[allDays.length - 1] : null;

  allDays.forEach((dayCount) => {
    const entries = groupedData[dayCount] || [];
    const dayEvents = eventsByDay[dayCount] || [];
    const isCurrentDay = dayCount === mostRecentDay;

    const isExpanded = Object.prototype.hasOwnProperty.call(
      dayExpandedState,
      dayCount,
    )
      ? dayExpandedState[dayCount]
      : dayCount === mostRecentDay;
    dayExpandedState[dayCount] = isExpanded;

    const collapseId = `dayCollapse-${dayCount}`;
    const serviceLabel = entries.length === 1 ? "servicio" : "servicios";
    const eventLabel = dayEvents.length === 1 ? "evento" : "eventos";

    // Un día puede tener solo eventos (sin servicios registrados ese día) o
    // viceversa; solo se arma la columna que corresponde, y si hay ambas se
    // reparte en 2 columnas (se apilan solas en pantallas angostas gracias
    // al grid de Bootstrap).
    const tableHtml =
      entries.length > 0
        ? `
            <table class="table table-hover table-flat-rows responsive w-100 rounded-table">
                <thead class="thead text-uppercase">
                    <tr>
                        <th>Tipo</th>
                        <th>Nombre</th>
                        <th>Observaciones</th>
                        <th>M.V.Z.</th>
                        <th>Vale</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    ${entries.map((entry) => renderEntryRow(entry, isCurrentDay)).join("")}
                </tbody>
            </table>
        `
        : "";
    const timelineHtml =
      dayEvents.length > 0 ? renderEventsTimeline(dayEvents) : "";

    const bothColumns = entries.length > 0 && dayEvents.length > 0;

    // "Generar Vale" ya no vive suelto al final de la página (quedaba fuera
    // de cualquier card, "expuesto"): se muestra dentro de la card del día
    // vigente (mostRecentDay), junto a los checkboxes de los que depende
    // (.descontar-stock, dentro de esta misma tabla). Mismo botón/id/handler
    // de siempre (#btnGenerarVale, delegado en $("#table-container")), mismo
    // criterio hasIssuableRow (cualquier día) — solo cambia dónde se pinta.
    const valeButtonHtml =
      isCurrentDay && hasIssuableRow
        ? `
            <div class="d-flex justify-content-end mt-2 day-vale-button-wrapper">
                <button
                    id="btnGenerarVale"
                    class="action-link action-link--success btn-icon-circle"
                    type="button"
                    title="Generar vale">
                    <span class="heroicons-outline--ticket"></span>
                </button>
            </div>
          `
        : "";

    const dayBlock = `
        <div class="card-panel day-accordion-card">
            <div class="d-flex justify-content-between align-items-center chevron-toggle day-accordion-toggle"
                data-bs-toggle="collapse" data-bs-target="#${collapseId}"
                aria-expanded="${isExpanded ? "true" : "false"}" aria-controls="${collapseId}">
                <div class="d-flex align-items-center gap-3">
                    <div class="day-pill">DÍA ${dayCount}</div>
                    <span class="day-accordion-summary">${entries.length} ${serviceLabel} · ${dayEvents.length} ${eventLabel}</span>
                </div>
                <i class="fas fa-chevron-down text-primary"></i>
            </div>
            <div class="collapse day-collapse-body${isExpanded ? " show" : ""}" id="${collapseId}" data-day="${dayCount}">
                <div class="row g-3">
                    ${tableHtml ? `<div class="${bothColumns ? "col-lg-7" : "col-12"}">${tableHtml}</div>` : ""}
                    ${timelineHtml ? `<div class="${bothColumns ? "col-lg-5" : "col-12"}">${timelineHtml}</div>` : ""}
                </div>
                ${valeButtonHtml}
            </div>
        </div>
    `;
    $("#table-container").append(dayBlock);
  });
}

function renderEventsTimeline(dayEvents) {
  const items = dayEvents
    .map((event) => {
      const meta = EVENT_TYPE_META[event.event_type] || DEFAULT_EVENT_META;
      const time = event.created_at
        ? new Date(event.created_at).toLocaleTimeString("es-MX", {
            hour: "2-digit",
            minute: "2-digit",
          })
        : "";
      const iconMarkup = meta.icon.startsWith("fas ")
        ? `<i class="${meta.icon}"></i>`
        : `<span class="${meta.icon}"></span>`;

      // "Ver detalle" solo aplica a followup_added, y solo si el evento
      // trae followup_type/followup_id (ver RedSheetController::events()):
      // eventos viejos (creados antes de esta columna) o de un controller de
      // seguimiento que todavía no genera el hook (ver Paso 0 —
      // FollowupSurgical/FollowupsCritic/FollowupIntern hoy no lo generan)
      // no los traen, y en ese caso se omite el link, mostrando solo la
      // descripción como antes.
      const detailLink =
        event.event_type === "followup_added" &&
        event.followup_type &&
        event.followup_id
          ? ` · <a href="javascript:void(0)" class="timeline-detail-link"
                onclick="openFollowUpDetail('${event.followup_type}', ${event.followup_id})">Ver detalle</a>`
          : "";

      return `
            <li class="timeline-item">
                <span class="timeline-icon" style="color:${meta.color}; background-color:${meta.bg};">${iconMarkup}</span>
                <div class="timeline-content">
                    <div class="timeline-title" style="color:${meta.color};">${meta.label}</div>
                    <div class="timeline-desc">${event.description}</div>
                    <div class="timeline-meta">${time ? time + " · " : ""}${event.created_by}${detailLink}</div>
                </div>
            </li>
        `;
    })
    .join("");

  return `
        <div class="timeline-wrapper">
            <div class="timeline-heading">Bitácora del día</div>
            <ul class="timeline-list">${items}</ul>
        </div>
    `;
}

function renderVoucherCell(entry) {
  // Un servicio eliminado (auditoría de eliminación) nunca debe poder
  // generar vale, sin importar su estatus de ES_ALMACENABLE/vale existente.
  if (entry.removed_at) {
    return "";
  }

  if (!entry.serv_producto || entry.serv_producto.ES_ALMACENABLE !== "S") {
    return "";
  }

  let checkbox = "";

  if (entry.active_voucher_folio) {
    checkbox = `<span class="badge bg-primary voucher-folio-badge" style="cursor:pointer;"
            data-voucher-id="${entry.active_voucher_id}">
            ${entry.active_voucher_folio}
          </span>`;
  } else {
    checkbox = `<input type="checkbox"
                       class="form-check-input descontar-stock border border-primary"
            data-redsheet="${entry.id}">`;
  }

  if (entry.has_cancelled_history) {
    checkbox += `<i class="fas fa-history voucher-history-icon" style="cursor:pointer; margin-left:6px; color:#6c757d;"
            data-source-type="red_sheet" data-source-id="${entry.id}" title="Ver historial de vales"></i>`;
  }

  return checkbox;
}

// Motivo/quién de cada servicio eliminado, indexado por entry.id, para el
// modal informativo "Motivo de eliminación" (openRemovalReasonModal) — se
// llena al renderizar la celda "Eliminar" (renderEliminarCell), reutilizando
// los mismos datos que ya trae la tabla (entry.removal_reason/removed_by_user),
// sin ningún fetch adicional al backend.
let redSheetRemovalReasons = {};

// Celda "Eliminar": si ya está marcado como eliminado, muestra quién/motivo
// en vez de un botón (auditoría visible, ver RedSheetController::removeService()).
// Si no, solo hay botón en el día actual (isCurrentDay), deshabilitado con
// tooltip si el producto es consumible (ES_ALMACENABLE "S") y su vale activo
// ya está Surtido (ya se descontó de almacén, no se puede deshacer).
function renderEliminarCell(entry, producto, isCurrentDay) {
  if (entry.removed_at) {
    const who = entry.removed_by_user
      ? entry.removed_by_user.name
      : "Desconocido";

    redSheetRemovalReasons[entry.id] = {
      who: who,
      reason: entry.removal_reason || "Sin motivo registrado.",
    };

    return `<span class="badge bg-secondary">
                Eliminado por ${who}
            </span>
            <i class="fas fa-eye removal-reason-icon" style="cursor:pointer; margin-left:6px; color:#6c757d;"
                title="Ver motivo de eliminación" data-entry-id="${entry.id}"></i>`;
  }

  if (!isCurrentDay) {
    return "";
  }

  const blocked =
    producto &&
    producto.ES_ALMACENABLE === "S" &&
    entry.active_voucher_status === "Surtido";

  if (blocked) {
    return `<button type="button" class="btn btn-sm btn-outline-secondary" disabled
                title="Este servicio ya tiene un vale Surtido y no puede eliminarse.">
                <i class="fas fa-trash"></i>
            </button>`;
  }

  return `<button type="button" class="btn btn-sm icon-btn-outline text-danger "
                onclick="removeRedSheetService(${entry.id})">
                <i class="fas fa-trash"></i>
            </button>`;
}

// Modal informativo "Motivo de eliminación" (removal-reason-modal.blade.php):
// solo lectura, no permite modificar el motivo. .text() escapa el contenido,
// por si el motivo capturado incluyera caracteres de HTML.
function openRemovalReasonModal(entryId) {
  const data = redSheetRemovalReasons[entryId] || {
    who: "Desconocido",
    reason: "Sin motivo registrado.",
  };

  $("#removalReasonWho").text(data.who);
  $("#removalReasonText").text(data.reason);

  bootstrap.Modal.getOrCreateInstance(
    document.getElementById("removalReasonModal"),
  ).show();
}

$(document).on("click", ".removal-reason-icon", function () {
  openRemovalReasonModal($(this).data("entry-id"));
});

function renderEntryRow(entry, isCurrentDay) {
  let rows = "";
  const rowClass = entry.removed_at
    ? ' class="text-decoration-line-through text-muted"'
    : "";

  if (entry.lab) {
    rows += `
            <tr${rowClass}>
                <td>Laboratorio</td>
                <td>${entry.laboratory.NOMBRE}</td>
                <td>${entry.observations || "—"}</td>
                <td>${entry.vet ? entry.vet.name : ""}</td>
                <td>${renderVoucherCell({ ...entry, serv_producto: entry.laboratory })}</td>
                <td>${renderEliminarCell(entry, entry.laboratory, isCurrentDay)}</td>
            </tr>
        `;
  }

  if (entry.imaging) {
    rows += `
            <tr${rowClass}>
                <td>Imagenologia</td>
                <td>${entry.img.NOMBRE}</td>
                <td>${entry.observations || "—"}</td>
                <td>${entry.vet ? entry.vet.name : ""}</td>
                <td>${renderVoucherCell({ ...entry, serv_producto: entry.img })}</td>
                <td>${renderEliminarCell(entry, entry.img, isCurrentDay)}</td>
            </tr>
        `;
  }

  if (entry.service) {
    rows += `
            <tr${rowClass}>
                <td>Servicio</td>
                <td>${entry.serv.NOMBRE}</td>
                <td>${entry.observations || "—"}</td>
                <td>${entry.vet ? entry.vet.name : ""}</td>
                <td>${renderVoucherCell({ ...entry, serv_producto: entry.serv })}</td>
                <td>${renderEliminarCell(entry, entry.serv, isCurrentDay)}</td>
            </tr>
        `;
  }

  if (entry.surgery) {
    // Cirugía: el checkbox de vale y el botón de eliminar NO aplican todavía
    // — Surgery no está integrado al mecanismo de vales ni a este flujo.
    // (Antes del header "Observaciones" esta fila ya traía 6 <td> contra un
    // header de 5 columnas: quedaba desalineada -observaciones se veía bajo
    // "M.V.Z." y el M.V.Z. real bajo "Vale". Con la columna nueva ya
    // encajan en su lugar real, sin tocar el orden de esta fila.)
    rows += `
            <tr>
                <td>Cirugia</td>
                <td>${entry.surg.NOMBRE}</td>
                <td>${entry.observations || "—"}</td>
                <td>${entry.vet ? entry.vet.name : ""}</td>
                <td></td>
                <td></td>
            </tr>
        `;
  }

  return rows;
}

// function renderEntryRow(entry) {
//   let rows = "";

//   if (entry.lab) {
//     rows += `
//             <tr>
//                 <td>Laboratorio</td>
//                 <td>${entry.laboratory.NOMBRE}</td>
//                 <td>${entry.observations || ""}</td>
//                 <td>${entry.vet ? entry.vet.name : ""}</td>
//                 <td>$${parseFloat(entry.lab.PRECIO).toFixed(2)}</td>
//                 <td></td>

//             </tr>
//         `;
//   }

//   if (entry.imaging) {
//     rows += `
//             <tr>
//                 <td>Imagenologia</td>
//                 <td>${entry.img.NOMBRE}</td>
//                 <td>${entry.observations || ""}</td>
//                 <td>${entry.vet ? entry.vet.name : ""}</td>
//                 <td>$${parseFloat(entry.imaging.PRECIO).toFixed(2)}</td>
//                  <td></td>
//             </tr>
//         `;
//   }

//   if (entry.service) {
//     let checkbox = "";

//     if (entry.serv && entry.serv.ES_ALMACENABLE === "S") {
//       if (entry.active_voucher_folio) {
//         checkbox = `<span class="badge bg-primary voucher-folio-badge" style="cursor:pointer;"
//                 data-voucher-id="${entry.active_voucher_id}">
//                 ${entry.active_voucher_folio}
//               </span>`;
//       } else {
//         checkbox = `<input type="checkbox"
//                                    class="form-check-input descontar-stock border border-primary"
//                     data-redsheet="${entry.id}">`;
//       }

//       if (entry.has_cancelled_history) {
//         checkbox += `<i class="fas fa-history voucher-history-icon" style="cursor:pointer; margin-left:6px; color:#6c757d;"
//                 data-source-type="red_sheet" data-source-id="${entry.id}" title="Ver historial de vales"></i>`;
//       }
//     }

//     rows += `
//             <tr>
//                 <td>Servicio</td>
//                 <td>${entry.serv.NOMBRE}</td>
//                 <td>${entry.observations || ""}</td>
//                 <td>${entry.vet ? entry.vet.name : ""}</td>
//                 <td>$${parseFloat(entry.service.PRECIO).toFixed(2)}</td>
//                 <td >${checkbox}</td>
//             </tr>
//         `;
//   }

//   if (entry.surgery) {
//     rows += `
//             <tr>
//                 <td>Cirugia</td>
//                 <td>${entry.surg.NOMBRE}</td>
//                 <td>${entry.observations || ""}</td>
//                 <td>${entry.vet ? entry.vet.name : ""}</td>
//                 <td>$${parseFloat(entry.surgery.PRECIO).toFixed(2)}</td>
//                  <td></td>
//             </tr>
//         `;
//   }

//   return rows;
// }

async function NewEntry() {
  event.preventDefault();
  let url = route("red-sheets.store");
  let form = new FormData(document.getElementById("NewRedSheet"));
  let pet = await fetch(url, { method: "POST", body: form });
  // Un solo await pet.json(): el body de un Response solo se puede leer una
  // vez (una 2a llamada tronaba con "body stream already read"), así que la
  // rama de error de abajo nunca llegaba a mostrar nada útil.
  let resp = await pet.json();

  if (pet.ok) {
    Swal.fire({
      icon: "success",
      title: "Se guardaron los procedimientos con exito",
      timer: 1500,
      showConfirmButton: false,
      timerProgressBar: true,
    });
    // table.ajax.reload();
    fetchAndRenderData();
    $("#lab_type_id").val(null).trigger("change");
    $("#service_type_id").val(null).trigger("change");
    $("#imaging_type_id").val(null).trigger("change");
  } else {
    // Mismo criterio que hospital_auth.js: 422 con mensaje propio del
    // backend (ej. guardReceptionNotDischarged() en Controller.php) se
    // muestra tal cual; cualquier otro error usa un mensaje genérico.
    const message =
      pet.status === 422 && resp?.message
        ? resp.message
        : "Ocurrió un error al procesar la solicitud. Inténtalo de nuevo.";

    Swal.fire({
      icon: "error",
      title: "Error",
      text: message,
    });
  }
}

// Mapea followup_type (mismo valor que reception_events.followup_type, ver
// FollowUpController::store() y RedSheetController::events()) al modal/
// formulario correspondiente. Se usa tanto para el "Ver detalle" en modo
// solo lectura (openFollowUpDetail()) como para poder devolver ese modal a
// modo edición normal antes de usarlo para dar de alta un seguimiento nuevo.
const FOLLOWUP_TYPE_TO_MODAL = {
  follow_up: { modalId: "ModalFollowUps", formId: "NewFollowUp" },
  followup_surgical: {
    modalId: "ModalFollowupSurgical",
    formId: "NewFollowupSurgical",
  },
  followups_critic: {
    modalId: "ModalFollowupsCritic",
    formId: "NewFollowupsCritic",
  },
  followup_intern: {
    modalId: "ModalFollowupIntern",
    formId: "NewFollowupIntern",
  },
};

// Snapshot del HTML original (server-rendered por Blade, con reception_id/
// vet_id ya resueltos) del body de cada modal de seguimiento, tomado una
// sola vez al cargar la página. Sirve para volver a modo "crear" después de
// haber mostrado un detalle en solo lectura: restaurar así evita tener que
// reconstruir a mano cada campo (incluyendo reception_id/vet_id, que si no
// se restauraran quedarían con los valores del registro visto en modo
// lectura en vez de los de la recepción/usuario actual).
const followUpModalPristineHtml = {};
Object.values(FOLLOWUP_TYPE_TO_MODAL).forEach(({ modalId }) => {
  const body = document.querySelector(`#${modalId} .modal-body`);
  if (body) followUpModalPristineHtml[modalId] = body.innerHTML;
});

function resetFollowUpModalToCreateMode(modalId) {
  const pristine = followUpModalPristineHtml[modalId];
  const $body = $(`#${modalId} .modal-body`);
  if (pristine && $body.length) {
    $body.html(pristine);
  }
  $(`#${modalId} .followup-save-btn`).show();
  $(`#${modalId} .followup-cancel-btn`).text("Cancelar");
}

// Precarga el formulario del modal con `data` (la respuesta de
// follow-ups.detail), deshabilita todos sus controles y oculta "Guardar" —
// deja solo "Cerrar". Reutiliza el mismo modal/formulario que se usa para
// dar de alta, no hay un modal de "solo vista" aparte.
function setFollowUpModalReadonly(modalId, data) {
  const form = document.querySelector(`#${modalId} form`);
  if (!form) return;

  form.querySelectorAll("[name]").forEach((el) => {
    if (!(el.name in data)) return;
    if (el.type === "radio") {
      el.checked = String(el.value) === String(data[el.name]);
    } else {
      el.value = data[el.name] ?? "";
    }
  });

  form.querySelectorAll("input, select, textarea").forEach((el) => {
    el.disabled = true;
  });

  $(`#${modalId} .followup-save-btn`).hide();
  $(`#${modalId} .followup-cancel-btn`).text("Cerrar");
}

// Botón "Ver detalle" de la bitácora (ver renderEventsTimeline()): trae el
// registro completo de follow-ups.detail y lo pinta en modo solo lectura en
// el modal que le corresponda según followupType.
async function openFollowUpDetail(followupType, followupId) {
  const target = FOLLOWUP_TYPE_TO_MODAL[followupType];
  if (!target) return;

  let data;
  try {
    const resp = await fetch(
      route("follow-ups.detail", [followupType, followupId]),
    );
    if (!resp.ok) throw new Error("request failed");
    data = await resp.json();
  } catch (error) {
    Swal.fire({
      icon: "error",
      title: "No se pudo cargar el detalle del seguimiento",
    });
    return;
  }

  resetFollowUpModalToCreateMode(target.modalId);
  setFollowUpModalReadonly(target.modalId, data);
  $(`#${target.modalId}`).modal("show");
}

// FollowUpModalId se calcula en red-sheet/create.blade.php a partir del área
// de la recepción (Quirúrgicos/Críticos/Internos/genérico) y apunta al id del
// modal correspondiente incluido al final de esa vista.
function OpenFollowUps() {
  // Restaurar el formulario original
  resetFollowUpModalToCreateMode(FollowUpModalId);

  // Obtener el modal actual
  const modal = document.getElementById(FollowUpModalId);

  if (!modal) {
    console.error("No se encontró el modal:", FollowUpModalId);
    return;
  }

  // Buscar reception_id dentro del formulario de ese modal
  const receptionInput = modal.querySelector('input[name="reception_id"]');

  if (!receptionInput) {
    console.error("No existe input[name='reception_id'] en:", FollowUpModalId);
    return;
  }

  // Asignar recepción actual
  receptionInput.value = Reception_Id;

  console.log("=== ABRIENDO SEGUIMIENTO ===");
  console.log("Modal:", FollowUpModalId);
  console.log("Reception ID:", Reception_Id);
  console.log("Input:", receptionInput);
  console.log("Valor asignado:", receptionInput.value);

  // Abrir modal
  $(`#${FollowUpModalId}`).modal("show");
}

// Uncheck acotado al modal (no document-wide) para no afectar radios de
// otras cards/modales de esta misma pantalla (ej. ModalSurgeries).
function uncheckRadiosIn(containerId) {
  document
    .querySelectorAll(`#${containerId} input[type="radio"]`)
    .forEach((radio) => {
      radio.checked = false;
    });
}

async function AddFollowupSurgical() {
  event.preventDefault();

  const form = document.getElementById("NewFollowupSurgical");
  const url = route("followup-surgicals.store");

  try {
    const response = await fetch(url, {
      method: "POST",
      body: new FormData(form),
      headers: {
        Accept: "application/json",
      },
    });

    const data = await response.json().catch(() => null);

    if (!response.ok) {
      console.error("Error seguimiento quirúrgico:", data);

      if (data?.errors) {
        const errorMessages = Object.entries(data.errors)
          .map(
            ([field, messages]) =>
              `<p><strong>${field}:</strong> ${messages.join(", ")}</p>`,
          )
          .join("");

        Swal.fire({
          icon: "error",
          title: "Errores en el formulario",
          html: errorMessages,
        });
      } else {
        Swal.fire({
          icon: "error",
          title: "Error al guardar",
          text: data?.message || "No se pudo guardar el seguimiento.",
        });
      }

      return;
    }

    await Swal.fire({
      icon: "success",
      title: "Se guardó el seguimiento con éxito",
      timer: 1000,
      showConfirmButton: false,
      timerProgressBar: true,
    });

    fetchAndRenderData();
    CloseFollowupSurgical();
  } catch (error) {
    console.error("Error inesperado:", error);

    Swal.fire({
      icon: "error",
      title: "Error",
      text: "Ocurrió un error inesperado al guardar el seguimiento.",
    });
  }
}

function CloseFollowupSurgical() {
  document.getElementById("which_alterations_surgicals").value = "";
  document.getElementById("which_therapeutic_surgicals").value = "";
  document.getElementById("quantity_vomiting_surgicals").value = "";
  document.getElementById("quantity_defecation_surgicals").value = "";
  document.getElementById("quantity_urine_surgicals").value = "";
  document.getElementById("type_feeding_surgicals").value = "";
  document.getElementById("pendings_surgicals").value = "";
  document.getElementById("clean_observations_surgicals").value = "";
  document.getElementById("secretion_observations_surgicals").value = "";
  document.getElementById("quantity_drainage_surgicals").value = "";
  document.getElementById("type_blocked_surgicals").value = "";
  document.getElementById("type_time_infusions_surgicals").value = "";
  document.getElementById("which_alterations_surgery_surgicals").value = "";
  uncheckRadiosIn("ModalFollowupSurgical");
  $("#ModalFollowupSurgical").modal("hide");
}

async function AddFollowupIntern() {
  event.preventDefault();

  const form = document.getElementById("NewFollowupIntern");
  const url = route("followup-interns.store");

  try {
    const response = await fetch(url, {
      method: "POST",
      body: new FormData(form),
      headers: {
        Accept: "application/json",
      },
    });

    const data = await response.json().catch(() => null);

    if (!response.ok) {
      console.error("Error seguimiento interno:", data);

      if (data?.errors) {
        const errorMessages = Object.entries(data.errors)
          .map(
            ([field, messages]) =>
              `<p><strong>${field}:</strong> ${messages.join(", ")}</p>`,
          )
          .join("");

        Swal.fire({
          icon: "error",
          title: "Errores en el formulario",
          html: errorMessages,
        });
      } else {
        Swal.fire({
          icon: "error",
          title: "Error al guardar",
          text: data?.message || "No se pudo guardar el seguimiento.",
        });
      }

      return;
    }

    await Swal.fire({
      icon: "success",
      title: "Se guardó el seguimiento con éxito",
      timer: 1000,
      showConfirmButton: false,
      timerProgressBar: true,
    });

    fetchAndRenderData();
    CloseFollowupIntern();
  } catch (error) {
    console.error("Error inesperado:", error);

    Swal.fire({
      icon: "error",
      title: "Error",
      text: "Ocurrió un error inesperado al guardar el seguimiento.",
    });
  }
}

function CloseFollowupIntern() {
  document.getElementById("which_alterations_interns").value = "";
  document.getElementById("which_therapeutic_interns").value = "";
  document.getElementById("quantity_vomiting_interns").value = "";
  document.getElementById("quantity_defecation_interns").value = "";
  document.getElementById("quantity_urine_interns").value = "";
  document.getElementById("type_feeding_interns").value = "";
  document.getElementById("observations_ultrasounds_interns").value = "";
  document.getElementById("pendings_interns").value = "";
  uncheckRadiosIn("ModalFollowupIntern");
  $("#ModalFollowupIntern").modal("hide");
}

async function AddFollowupsCritic() {
  event.preventDefault();

  const form = document.getElementById("NewFollowupsCritic");
  const url = route("followups-critics.store");

  try {
    const response = await fetch(url, {
      method: "POST",
      body: new FormData(form),
      headers: {
        Accept: "application/json",
      },
    });

    const data = await response.json().catch(() => null);

    if (!response.ok) {
      console.error("Error seguimiento crítico:", data);

      if (data?.errors) {
        const errorMessages = Object.entries(data.errors)
          .map(
            ([field, messages]) =>
              `<p><strong>${field}:</strong> ${messages.join(", ")}</p>`,
          )
          .join("");

        Swal.fire({
          icon: "error",
          title: "Errores en el formulario",
          html: errorMessages,
        });
      } else {
        Swal.fire({
          icon: "error",
          title: "Error al guardar",
          text: data?.message || "No se pudo guardar el seguimiento.",
        });
      }

      return;
    }

    await Swal.fire({
      icon: "success",
      title: "Se guardó el seguimiento con éxito",
      timer: 1000,
      showConfirmButton: false,
      timerProgressBar: true,
    });

    fetchAndRenderData();
    CloseFollowupsCritic();
  } catch (error) {
    console.error("Error inesperado:", error);

    Swal.fire({
      icon: "error",
      title: "Error",
      text: "Ocurrió un error inesperado al guardar el seguimiento.",
    });
  }
}

function CloseFollowupsCritic() {
  document.getElementById("preasure_critics").value = "";
  document.getElementById("temperature_critics").value = "";
  document.getElementById("glycemia_critics").value = "";
  document.getElementById("throwup_detail_critics").value = "";
  document.getElementById("defecate_detail_critics").value = "";
  document.getElementById("orino_detail_critics").value = "";
  document.getElementById("eat_detail_critics").value = "";
  document.getElementById("infusions_detail_critics").value = "";
  document.getElementById("terapeutic_detail_critics").value = "";
  document.getElementById("imaging_detail_critics").value = "";
  document.getElementById("pends_critics").value = "";
  uncheckRadiosIn("ModalFollowupsCritic");
  $("#ModalFollowupsCritic").modal("hide");
}

function CloseFollowUp() {
  document.getElementById("time").value = "";
  document.getElementById("details").value = "";
  document.getElementById("temperature").value = "";
  document.getElementById("systolic").value = "";
  document.getElementById("diastolic").value = "";
  document.getElementById("average").value = "";
  document.getElementById("glycemia_level").value = "";
  $("#ModalFollowUps").modal("hide");
}

async function AddFollowUp() {
  event.preventDefault();

  const form = document.getElementById("NewFollowUp");
  const url = route("follow-ups.store");

  try {
    const response = await fetch(url, {
      method: "POST",
      body: new FormData(form),
      headers: {
        Accept: "application/json",
      },
    });

    const data = await response.json().catch(() => null);

    if (!response.ok) {
      console.error("Error al guardar seguimiento:", data);

      if (data?.errors) {
        const errorMessages = Object.entries(data.errors)
          .map(
            ([field, messages]) =>
              `<p><strong>${field}:</strong> ${messages.join(", ")}</p>`,
          )
          .join("");

        Swal.fire({
          icon: "error",
          title: "Errores en el formulario",
          html: errorMessages,
        });
      } else {
        Swal.fire({
          icon: "error",
          title: "Error al guardar",
          text: data?.message || "No se pudo registrar el seguimiento.",
        });
      }

      return;
    }

    await Swal.fire({
      icon: "success",
      title: "Se guardó el seguimiento con éxito",
      timer: 1000,
      showConfirmButton: false,
      timerProgressBar: true,
    });

    if (typeof followtable !== "undefined" && followtable) {
      followtable.ajax.reload(null, false);
    }

    fetchAndRenderData();
    CloseFollowUp();
  } catch (error) {
    console.error("Error inesperado:", error);

    Swal.fire({
      icon: "error",
      title: "Error",
      text: "Ocurrió un error inesperado al registrar el seguimiento.",
    });
  }
}

async function discharge(receptionID) {
  event.preventDefault();
  const result = await Swal.fire({
    title: "¿Dar de alta a este paciente?",
    text: "Por favor seleccione el tipo de alta para seguir el proceso",
    icon: "question",
    input: "select",
    inputOptions: getDischarges(altas),
    inputPlaceholder: "Selecciona el tipo de alta",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Confirmar.",
    cancelButtonText: "Cancelar.",
    inputValidator: (value) => {
      return new Promise((resolve) => {
        if (value === "") {
          resolve("Debes seleccionar un tipo de alta");
        } else {
          resolve();
        }
      });
    },
  });

  if (result.isConfirmed) {
    const selectedOption = result.value;

    switch (selectedOption) {
      case "1":
        normal(receptionID, selectedOption);
        break;
      case "2":
        volunteer(receptionID, selectedOption);
        break;
      case "3":
        death(receptionID, selectedOption);
        break;
    }
  }
}

async function normal(reception, type) {
  try {
    const csrfToken = document
      .querySelector('meta[name="csrf-token"]')
      .getAttribute("content");

    let form = new FormData();
    form.append("reception_id", reception);
    form.append("hospital_discharges_id", type);
    form.append("_token", csrfToken);

    let url = route("hospitalization.discharge");

    let response = await fetch(url, {
      method: "POST",
      body: form,
    });

    if (response.ok) {
      window.location.href = route("prescriptions.new", reception);
    }
  } catch (error) {
    console.error("Error:", error);
  }
}

async function volunteer(reception, type) {
  try {
    const csrfToken = document
      .querySelector('meta[name="csrf-token"]')
      .getAttribute("content");

    let form = new FormData();
    form.append("reception_id", reception);
    form.append("hospital_discharges_id", type);
    form.append("_token", csrfToken);

    let url = route("hospitalization.discharge");

    let response = await fetch(url, {
      method: "POST",
      body: form,
    });

    if (response.ok) {
      window.location.href = route("alta.voluntaria", reception);
    }
  } catch (error) {
    console.error("Error:", error);
  }
}

async function death(reception, type) {
  try {
    const csrfToken = document
      .querySelector('meta[name="csrf-token"]')
      .getAttribute("content");

    let form = new FormData();
    form.append("reception_id", reception);
    form.append("hospital_discharges_id", type);
    form.append("_token", csrfToken);

    let url = route("hospitalization.discharge");

    let response = await fetch(url, {
      method: "POST",
      body: form,
    });

    // El único camino a Cremación ahora es el flujo de Traslado (que ya
    // registra el alta por fallecimiento automáticamente al trasladar
    // Hospitalización -> Cremación, ver ReceptionTransferController::store())
    // — aquí ya no se ofrece iniciar cremación, solo se registra el alta.
    if (response.ok) {
      window.location.href = route("assignment.hospital");
    }
  } catch (error) {
    console.error("Error:", error);
  }
}

function getDischarges(DischargeData) {
  return DischargeData.reduce((options, Discharges) => {
    options[Discharges.id] = Discharges.name;
    return options;
  }, {});
}

// surgery/form.blade.php usa id="date", pero esa página (red-sheet/create.blade.php)
// también incluye followup-intern.modal, que TAMBIÉN tiene un campo oculto
// con id="date" — con el id duplicado, document.getElementById("date") toma
// el primero que aparece en el DOM (el de followup-intern, no el de la
// cirugía). Escopado a #NewSurgery (único en la página) para tomar siempre
// el campo correcto, sin tocar el id compartido del partial.
function getSurgeryDateInput() {
  return document.querySelector('#NewSurgery [name="date"]');
}

async function OpenSurgeries() {
  const receptionId = document.getElementById("reception_id_followup").value;

  const url = route("surgery.checkRequirements", receptionId);
  const response = await fetch(url);
  const data = await response.json();

  if (data.status === "ok") {
    // Hora del momento en que se abre el modal (hora local, no UTC)
    const now = new Date();
    now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
    getSurgeryDateInput().value = now.toISOString().slice(0, 16);

    $("#ModalSurgeries").modal("show");
  } else {
    Swal.fire({
      icon: "error",
      title: "Error",
      text: data.message || "Ocurrió un error al verificar los requisitos.",
    });
  }
}
jQuery("#ModalSurgeries").on("shown.bs.modal", function () {
  jQuery(document).off("focusin.modal");
});

async function AddSurgery() {
  event.preventDefault();

  // #date va oculto (ver arriba), pero sigue viajando en el FormData con el
  // valor que fijó OpenSurgeries() al abrir el modal; se refresca aquí para
  // que refleje el momento real del guardado, no el de apertura del modal.
  // El backend igual la vuelve a fijar con now() (use_current_date) como
  // fuente de verdad — esto es solo para que el valor enviado sea coherente.
  const now = new Date();
  now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
  getSurgeryDateInput().value = now.toISOString().slice(0, 16);

  const url = route("surgeries.store");
  const form = new FormData(document.getElementById("NewSurgery"));

  try {
    const response = await fetch(url, { method: "POST", body: form });

    if (response.ok) {
      Swal.fire({
        icon: "success",
        title: "Se guardo la cirugia con exito",
        timer: 1500,
        showConfirmButton: false,
        timerProgressBar: true,
      });
      CloseSurgeries();
      fetchAndRenderData();
    } else {
      const resp = await response.json();
      Swal.fire({
        icon: "error",
        title: "Error",
        text: resp.message || "Ocurrió un error al guardar la cirugía.",
      });
    }
  } catch (error) {
    console.error("Error al guardar la cirugía:", error);
    Swal.fire({
      icon: "error",
      title: "Error",
      text: "Ocurrió un error inesperado. Vuelve a intentar más tarde.",
    });
  }
}

function CloseSurgeries() {
  // Bug real detrás de "Ocurrió un error inesperado" y el modal sin cerrar:
  // #observations no existe (el textarea de surgery.form es
  // "observations_surgery"), así que getElementById("observations") daba
  // null y el .value = "" siguiente reventaba con "Cannot set properties of
  // null" — eso se comía el resto de esta función (nunca llegaba a
  // modal("hide")) y, al llamarse dentro del try/catch de AddSurgery(),
  // aparecía como error aunque la cirugía ya se hubiera guardado bien.
  getSurgeryDateInput().value = "";
  // product_type_id es Select2 (ver $(document).ready arriba): asignar
  // .value directo no actualiza su UI renderizada, se queda mostrando la
  // cirugía recién guardada aunque el <select> nativo ya esté vacío. Mismo
  // patrón que ya usa NewEntry() para limpiar sus propios Select2
  // (lab_type_id/service_type_id/imaging_type_id) tras guardar con éxito.
  $("#product_type_id").val(null).trigger("change");
  document.getElementById("observations_surgery").value = "";
  $("#ModalSurgeries").modal("hide");
}

var followtable = undefined;
$(document).ready(function () {
  followtable = $("#follow-ups").DataTable({
    ajax: route("followup.entry", Reception_Id),
    responsive: true,
    order: [0, "desc"],
    columns: [
      {
        data: "created_at",
        render: function (data) {
          if (data) {
            let date = new Date(data);
            let formattedDate = date.toLocaleDateString("en-US", {
              year: "numeric",
              month: "short",
              day: "numeric",
            });
            return `${formattedDate} `;
          }
          return "";
        },
      },

      {
        data: "time",
      },

      {
        data: "details",
      },

      {
        data: "temperature",
      },
      {
        data: "systolic",
      },
      {
        data: "diastolic",
      },
      {
        data: "average",
      },
      {
        data: "glycemia_level",
      },
      {
        data: null,
        render: function (data) {
          return data.vet ? data.vet.name : "";
        },
      },
    ],
  });
});

async function Transfer(currentAdmissionTypeId) {
  event.preventDefault();

  // Obtener todas las admisiones
  const options = getAdm(admisiones);

  // Deshabilitar la admisión actual
  delete options[currentAdmissionTypeId];

  Swal.fire({
    title: "Traslado de paciente",
    icon: "question",
    html: "Selecciona el nuevo tipo de admisión para este traslado",
    input: "select",
    inputOptions: options,
    inputPlaceholder: "Selecciona la admisión",
    showCancelButton: true,
    confirmButtonText: "Asignar",
    cancelButtonText: "Cancelar",

    inputValidator: (value) => {
      return new Promise((resolve) => {
        if (value === "") {
          resolve("Debes seleccionar una admisión");
        } else {
          resolve();
        }
      });
    },
  }).then(async (result) => {
    if (result.isConfirmed) {
      const form = new FormData();

      const token = document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content");

      form.append("_token", token);
      form.append("_method", "PUT");
      form.append("admission_type_id", result.value);

      let url = route("reception.transfer", Reception_Id);

      let pet = await fetch(url, {
        method: "POST",
        body: form,
      });

      if (pet.ok) {
        window.location.reload();
      }
    }
  });
}

function getAdm(AdminssionData) {
  return AdminssionData.reduce((options, Adminssion) => {
    options[Adminssion.id] = Adminssion.name;

    return options;
  }, {});
}

// El listado completo de vales (antes tabla #tableVoucher siempre visible)
// se quitó de esta pantalla: cada servicio muestra su propio folio (ver
// renderEntryRow) y abre el modal compartido de detalle/cancelación. Surtir
// y rechazar vales sigue viviendo en la pantalla global de Vales
// (public/js/vouchers/index.js), sin cambios.
//
// voucherTableReload() se conserva con este nombre porque las pantallas de
// cancelar/surtir/rechazar (abiertas en pestaña nueva) llaman
// window.opener.voucherTableReload() al terminar.
function voucherTableReload() {
  fetchAndRenderData();
}

async function removeRedSheetService(id) {
  const { value: reason, isConfirmed } = await Swal.fire({
    title: "¿Eliminar este servicio?",
    input: "text",
    inputLabel: "Motivo de la eliminación",
    inputPlaceholder: "Escribe el motivo",
    showCancelButton: true,
    confirmButtonText: "Eliminar",
    cancelButtonText: "Cancelar",
    inputValidator: (value) => {
      if (!value || !value.trim()) {
        return "El motivo es obligatorio";
      }
    },
  });

  if (!isConfirmed) return;

  const resp = await fetch(route("red-sheets.remove-service", id), {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
      "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
    body: JSON.stringify({ reason }),
  });
  const data = await resp.json();

  if (!resp.ok) {
    Swal.fire({
      icon: "error",
      title: "No se pudo eliminar",
      text: data.message || "Ocurrió un problema al eliminar el servicio.",
    });
    return;
  }

  fetchAndRenderData(false);
}

let pollingInterval = null;

// pollForChanges (global.js) chequea DE INMEDIATO (no solo en el primer
// tick a los 15s) y además al volver de bfcache ('pageshow' + persisted) —
// mismo helper compartido que receptions/index.js, vouchers/index.js y
// hospitalizations/show.js.
function startPolling() {
  if (pollingInterval) return; // ya está corriendo

  pollingInterval = pollForChanges({
    checkFn: function () {
      return $.ajax({
        url: route("vouchers.lastUpdate"),
        method: "GET",
        data: { reception_id: Reception_Id },
      }).then(function (response) {
        return response.last_update;
      });
    },
    onChanged: function () {
      fetchAndRenderData(false);
    },
    intervalMs: 15000,
  });
}

function stopPolling() {
  if (pollingInterval) {
    clearInterval(pollingInterval);
    pollingInterval = null;
    lastUpdate = null;
  }
}

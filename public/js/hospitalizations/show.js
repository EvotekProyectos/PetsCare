// Versión de solo lectura de createredsheets.js, para
// red-sheet/show-reception.blade.php — reutiliza función por función lo que
// aplica (fetch + render del acordeón de días + bitácora), sin ningún
// handler de captura (NewEntry/AddSurgery/AddFollowup*/discharge/Transfer/
// removeRedSheetService, etc. — ver el plan de esta tarea).

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

function fetchAndRenderData(showLoader = true) {
  if (showLoader) {
    $("#table-container").hide();
    $("#table-loader").show();
  }

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
  return data.map((entry) => ({
    ...entry,
    lab: entry.lab || null,
    imaging: entry.imaging || null,
    service: entry.service || null,
    surgery: entry.surgery || null,
    observations: entry.observations || "Sin observaciones",
    vet: entry.vet || { name: "Desconocido" },
  }));
}

// Mismos metadatos visuales que createredsheets.js (ver ese archivo para el
// porqué de cada ícono/color — no se duplica la explicación aquí).
const EVENT_TYPE_META = {
  admission_change: {
    icon: "clarity--two-way-arrows-line",
    color: "#0455A0",
    bg: "#E6F1FB",
    label: "Cambio de admisión",
  },
  followup_added: {
    icon: "clarity--note-edit-line",
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

// Igual que createredsheets.js: qué días quedan expandidos/colapsados,
// sobrevive entre llamadas a fetchAndRenderData() (ej. el polling).
const dayExpandedState = {};

$(document).on("shown.bs.collapse", ".day-collapse-body", function () {
  dayExpandedState[$(this).data("day")] = true;
});
$(document).on("hidden.bs.collapse", ".day-collapse-body", function () {
  dayExpandedState[$(this).data("day")] = false;
});

function renderData(data, events = []) {
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

  const allDays = Array.from(
    new Set([
      ...Object.keys(groupedData).map(Number),
      ...Object.keys(eventsByDay).map(Number),
    ]),
  ).sort((a, b) => a - b);

  $("#table-container").empty();

  const mostRecentDay = allDays.length ? allDays[allDays.length - 1] : null;

  allDays.forEach((dayCount) => {
    const entries = groupedData[dayCount] || [];
    const dayEvents = eventsByDay[dayCount] || [];

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
                    </tr>
                </thead>
                <tbody>
                    ${entries.map((entry) => renderEntryRow(entry)).join("")}
                </tbody>
            </table>
        `
        : "";
    const timelineHtml =
      dayEvents.length > 0 ? renderEventsTimeline(dayEvents) : "";

    const bothColumns = entries.length > 0 && dayEvents.length > 0;

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

// Solo lectura: sin la rama de checkbox de generar vale (a diferencia de
// createredsheets.js) — si hay vale activo se muestra el folio (badge
// clicable, abre el modal de detalle en modo consulta); si no, nada.
function renderVoucherCell(entry) {
  if (!entry.serv_producto || entry.serv_producto.ES_ALMACENABLE !== "S") {
    return "";
  }

  let cell = "";

  if (entry.active_voucher_folio) {
    cell = `<span class="badge bg-primary voucher-folio-badge" style="cursor:pointer;"
            data-voucher-id="${entry.active_voucher_id}">
            ${entry.active_voucher_folio}
          </span>`;
  }

  if (entry.has_cancelled_history) {
    cell += `<i class="fas fa-history voucher-history-icon" style="cursor:pointer; margin-left:6px; color:#6c757d;"
            data-source-type="red_sheet" data-source-id="${entry.id}" title="Ver historial de vales"></i>`;
  }

  return cell;
}

// Sin columna de Eliminar (esa es una acción de captura, no aplica aquí).
function renderEntryRow(entry) {
  let rows = "";

  if (entry.lab) {
    rows += `
            <tr>
                <td>Laboratorio</td>
                <td>${entry.laboratory.NOMBRE}</td>
                <td>${entry.observations || ""}</td>
                <td>${entry.vet ? entry.vet.name : ""}</td>
                <td>${renderVoucherCell({ ...entry, serv_producto: entry.laboratory })}</td>
            </tr>
        `;
  }

  if (entry.imaging) {
    rows += `
            <tr>
                <td>Imagenologia</td>
                <td>${entry.img.NOMBRE}</td>
                <td>${entry.observations || ""}</td>
                <td>${entry.vet ? entry.vet.name : ""}</td>
                <td>${renderVoucherCell({ ...entry, serv_producto: entry.img })}</td>
            </tr>
        `;
  }

  if (entry.service) {
    rows += `
            <tr>
                <td>Servicio</td>
                <td>${entry.serv.NOMBRE}</td>
                <td>${entry.observations || ""}</td>
                <td>${entry.vet ? entry.vet.name : ""}</td>
                <td>${renderVoucherCell({ ...entry, serv_producto: entry.serv })}</td>
            </tr>
        `;
  }

  if (entry.surgery) {
    rows += `
            <tr>
                <td>Cirugia</td>
                <td>${entry.surg.NOMBRE}</td>
                <td>${entry.observations || ""}</td>
                <td>${entry.vet ? entry.vet.name : ""}</td>
                <td></td>
            </tr>
        `;
  }

  return rows;
}

// Mapea followup_type al modal/formulario correspondiente — solo se usa acá
// para openFollowUpDetail() (el link "Ver detalle" de la bitácora), nunca
// para dar de alta un seguimiento nuevo (sin trigger de "crear" en esta vista).
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

// Las pantallas de cancelar/surtir/rechazar vale (abiertas en pestaña nueva
// desde el modal compartido) llaman window.opener.voucherTableReload() al
// terminar.
function voucherTableReload() {
  fetchAndRenderData();
}

let pollingInterval = null;
let lastUpdate = null;

function startPolling() {
  if (pollingInterval) return;

  pollingInterval = setInterval(function () {
    $.ajax({
      url: route("vouchers.lastUpdate"),
      method: "GET",
      data: { reception_id: Reception_Id },
      success: function (response) {
        if (lastUpdate === null) {
          lastUpdate = response.last_update;
          return;
        }

        if (response.last_update !== lastUpdate) {
          lastUpdate = response.last_update;
          fetchAndRenderData(false);
        }
      },
    });
  }, 15000);
}

function stopPolling() {
  if (pollingInterval) {
    clearInterval(pollingInterval);
    pollingInterval = null;
    lastUpdate = null;
  }
}

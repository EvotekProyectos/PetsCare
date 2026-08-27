const areas = {
  1: "#079BCE", // Quirúrgicos (Azul)
  2: "#F54245", // Cuidado Intensivo (Rojo)
  3: "#2ecb56", // Internos (Verde)
  4: "#ff7855", // Felinos (Salmón)
  5: "#8C65B5", // Infecciosos (Morado)
};

// Columna "Cuenta" (estatus de Account, ver episode.account en
// ReceptionController::list()): a diferencia de las columnas de estatus
// clínico, aquí el color NO varía por estado — solo el ícono/texto — por
// eso es un único color fijo en vez de un mapa color-por-estado.
const ACCOUNT_CUENTA_META = {
  OPEN: { label: "Abierta", icon: "fa-lock-open" },
  CLOSED: { label: "Cerrada", icon: "fa-lock" },
  PAID: { label: "Pagada", icon: "fa-hand-holding-usd" },
};
const ACCOUNT_CUENTA_COLOR = "#495057";

function renderCuentaBadge(data) {
  const status =
    data.episode && data.episode.account ? data.episode.account.status : null;
  if (!status) return "";
  const meta = ACCOUNT_CUENTA_META[status] || {
    label: status,
    icon: "fa-info-circle",
  };
  return `<span style="background-color: ${lightenColor(ACCOUNT_CUENTA_COLOR)}; color: ${ACCOUNT_CUENTA_COLOR}; padding: 5px 10px; border-radius: 5px; font-weight: 600; -webkit-box-decoration-break: clone; box-decoration-break: clone;">
      <i class="fas ${meta.icon}"></i> ${meta.label}
    </span>`;
}

var table = undefined;
var table2 = undefined;
var table3 = undefined;
var table4 = undefined;
var table5 = undefined;

function initTable() {
  if (table) {
    return;
  }
  table = $("#table").DataTable({
    ajax: {
      url: route("reception.list", 1),
      data: function (d) {
        d.date = $("#filterConsultaFecha").val();
        d.date_to = $("#filterConsultaFechaHasta").val();
        d.status_id = $("#filterConsultaEstado").val();
        d.vet_id = $("#filterConsultaMedico").val();
        d.account_status = $("#filterConsultaCuenta").val();
      },
    },
    responsive: true,
    order: [0, "desc"],
    columns: [
      { data: "entry_date", render: formatDate },

      {
        data: null,
        render: function (data) {
          return data.vet ? data.vet.name : "";
        },
      },

      {
        data: null,
        render: function (data) {
          return data.family ? data.family.name : "";
        },
      },
      {
        data: null,
        render: function (data) {
          if (!data.pet) {
            return '<span class="text-muted">—</span>';
          }

          let textColorClass =
            data.pet.deceased === 1 ? "text-secondary" : "text-primary";

          let deceasedIcon =
            data.pet.deceased === 1 ? '<span class="mdi--cross"></span> ' : "";

          let speciesIcon = data.pet.species?.icon
            ? `<span class="${data.pet.species.icon}"></span> `
            : '<i class="fas fa-paw"></i> ';

          return `
      <a type="button"
         href="${route("pet-history.index", { id: data.pet.id, type: 1 })}"
         class="btn btn-sm pet-chip ${textColorClass}">
          ${deceasedIcon}${speciesIcon}${data.pet.name}
      </a>
    `;
        },
      },
      {
        data: null,
        render: function (data) {
          if (data && data.reason_id && data.reason) {
            return `<span style="
        color: ${data.reason.color};
        background-color: ${lightenColor(data.reason.color)};
        padding: 5px 10px;
        border-radius: 5px;
        font-weight: 600;
        line-height: 1.8;
        -webkit-box-decoration-break: clone;
        box-decoration-break: clone;
      ">
        ${data.reason.name}
      </span>`;
          }
          return "";
        },
      },
      {
        data: null,
        render: function (data) {
          return data.room ? data.room.name : "Sin definir";
        },
      },
      {
        data: null,
        render: function (data) {
          if (
            data &&
            data.current_status_appointment &&
            data.current_status_appointment.attention_status
          ) {
            const status = data.current_status_appointment.attention_status;
            return `<span style="background-color: ${lightenColor(status.color)}; padding: 5px 10px; color: ${status.color}; border-radius: 5px; font-weight: 600;
        -webkit-box-decoration-break: clone;
        box-decoration-break: clone;">
                ${status.name}
              </span>`;
          }
          return "Sin estatus";
        },
      },
      { data: null, render: renderCuentaBadge },
      {
        data: null,
        render: function (data) {
          // Estatus por nombre desde el backend (ATTENTION_STATUS_EN_ESPERA_ID),
          // no hardcodeado — ver ReceptionController::index().
          const status = data.current_status_appointment?.attention_status?.id;

          const showEdit = data.can_edit && status === ATTENTION_STATUS_EN_ESPERA_ID;
          const showDelete = data.can_delete && status === ATTENTION_STATUS_EN_ESPERA_ID;
          const showAccount = status === ATTENTION_STATUS_EN_ESPERA_ID;

          let buttons = "";

          if (showEdit) {
            buttons += `
                <a type="button" href="#" onclick="openEditReceptionModal(${data.id}); return false;" class="btn btn-sm icon-btn-outline text-primary">
                    <i class="fas fa-edit"></i>
                </a>`;
          }

          // if (showDelete) {
          //   buttons += `
          //       <button type="button" class="btn btn-sm icon-btn-outline text-primary" onclick="showAlertWithCallback(() => deleteReception(${data.id}, table5));">
          //           <i class="fas fa-trash"></i>
          //       </button>`;
          // }

          if (status !== ATTENTION_STATUS_EN_ESPERA_ID) {
            buttons += `
                <a type="button" href="#" onclick="openAccountStatementModal(${data.id}); return false;" class="btn btn-sm icon-btn-outline text-primary" title="Estado de cuenta">
                 <i class="fas fa-dollar-sign"></i>
                </a>`;
          }

          if (data.has_transfers) {
            buttons += `
                <a type="button" href="#" onclick="openTransfersTrackingModal({reception_id: ${data.id}}); return false;" class="btn btn-sm icon-btn-outline text-info" title="Tracking de traslados">
                   <i class="fas fa-exchange-alt"></i>
                </a>`;
          }

          return buttons || '<span class="text-muted">—</span>';
        },
      },
    ],
  });
}

function initTable2() {
  if (table2) {
    return;
  }
  table2 = $("#table2").DataTable({
    ajax: {
      url: route("reception.list", 2),
      data: function (d) {
        d.date = $("#filterHospFecha").val();
        d.date_to = $("#filterHospFechaHasta").val();
        d.status_id = $("#filterHospEstatus").val();
        d.area_id = $("#filterHospArea").val();
        d.admission_type_id = $("#filterHospAdmision").val();
        d.account_status = $("#filterHospCuenta").val();
      },
    },
    responsive: true,
    order: [0, "desc"],
    columns: [
      { data: "entry_date", render: formatDate },

      {
        data: null,
        render: function (data) {
          return data.vet ? data.vet.name : "";
        },
      },

      {
        data: null,
        render: function (data) {
          return data.family ? data.family.name : "";
        },
      },
      {
        data: null,
        render: function (data) {
          if (!data.pet) {
            return '<span class="text-muted">—</span>';
          }

          let textColorClass =
            data.pet.deceased === 1 ? "text-secondary" : "text-primary";

          let deceasedIcon =
            data.pet.deceased === 1 ? '<span class="mdi--cross"></span> ' : "";

          let speciesIcon = data.pet.species?.icon
            ? `<span class="${data.pet.species.icon}"></span> `
            : '<i class="fas fa-paw"></i> ';

          return `
      <a type="button"
         href="${route("pet-history.index", { id: data.pet.id, type: 2 })}"
         class="btn btn-sm pet-chip ${textColorClass}">
          ${deceasedIcon}${speciesIcon}${data.pet.name}
      </a>
    `;
        },
      },
      {
        data: null,
        render: function (data) {
          if (!data || data.area === null) {
            return "";
          }

          const baseColor = areas[data.area_id]; // color definido en tu variable areas

          return `<span style="
        color: ${baseColor};
        background-color: ${lightenColor(baseColor)};
        padding: 5px 10px;
        border-radius: 5px;
        font-weight: 600;
        line-height: 1.8;
        -webkit-box-decoration-break: clone;
        box-decoration-break: clone;
      ">
        ${data.area.name}
      </span>`;
        },
      },

      {
        data: null,
        render: function (data) {
          return data.admission_type ? data.admission_type.name : "";
        },
      },
      {
        data: null,
        render: function (data) {
          if (
            data &&
            data.current_hospitalization_status &&
            data.current_hospitalization_status.hospitalization_status
          ) {
            const status =
              data.current_hospitalization_status.hospitalization_status;
            return `<span style="background-color: ${lightenColor(status.color)}; padding: 5px 10px; color: ${status.color}; border-radius: 5px; font-weight: 600;">
                ${status.name}
              </span>`;
          }
          return "Sin estatus";
        },
      },
      { data: null, render: renderCuentaBadge },

      {
        data: null,
        render: function (data) {
          return `




                        <a type="button" href="#" onclick="openDocumentsModal(${data.id}); return false;"
                        class="btn btn-sm icon-btn-outline text-primary" title="Documentos"> <i class="fas fa-folder-open"></i>
                        </a>

              <a type="button" href="#" onclick="openAccountStatementModal(${data.id}, 'redsheet'); return false;" class="btn btn-sm icon-btn-outline text-primary" title="Estado de cuenta">
                            <i class="fas fa-dollar-sign"></i>
                        </a>
                      
                        ${
                          data.has_transfers
                            ? `<a type="button" href="#" onclick="openTransfersTrackingModal({reception_id: ${data.id}}); return false;" class="btn btn-sm icon-btn-outline text-info" title="Tracking de traslados">
                            <i class="fas fa-exchange-alt"></i>
                        </a>`
                            : ""
                        }`;
        },
      },
    ],
  });
}

function initTable3() {
  if (table3) {
    return;
  }
  table3 = $("#table3").DataTable({
    ajax: {
      url: route("reception.list", 3),
      data: function (d) {
        d.date = $("#filterGroomingFecha").val();
        d.date_to = $("#filterGroomingFechaHasta").val();
        d.status_id = $("#filterGroomingEstatus").val();
        d.vet_id = $("#filterGroomingColaborador").val();
        d.account_status = $("#filterGroomingCuenta").val();
      },
    },
    responsive: true,
    order: [0, "desc"],
    columns: [
      { data: "entry_date", render: formatDate },

      {
        data: null,
        render: function (data) {
          return data.vet ? data.vet.name : "";
        },
      },

      {
        data: null,
        render: function (data) {
          return data.family ? data.family.name : "";
        },
      },
      {
        data: null,
        render: function (data) {
          if (!data.pet) {
            return '<span class="text-muted">—</span>';
          }

          let textColorClass =
            data.pet.deceased === 1 ? "text-secondary" : "text-primary";

          let deceasedIcon =
            data.pet.deceased === 1 ? '<span class="mdi--cross"></span> ' : "";

          let speciesIcon = data.pet.species?.icon
            ? `<span class="${data.pet.species.icon}"></span> `
            : '<i class="fas fa-paw"></i> ';

          return `
      <a type="button"
         href="${route("pet-history.index", { id: data.pet.id, type: 3 })}"
         class="btn btn-sm pet-chip ${textColorClass}">
          ${deceasedIcon}${speciesIcon}${data.pet.name}
      </a>
    `;
        },
      },
      { data: "exit_date", render: formatDate },
      {
        data: null,
        render: function (data) {
          if (
            data &&
            data.current_status_grooming &&
            data.current_status_grooming.grooming_status
          ) {
            const status = data.current_status_grooming.grooming_status;
            return `<span style="background-color: ${lightenColor(status.color)}; padding: 5px 10px; color: ${status.color}; border-radius: 5px; font-weight: 600;">
                ${status.name}
              </span>`;
          }
          return "Sin estatus";
        },
      },
      { data: null, render: renderCuentaBadge },
      {
        data: null,
        render: function (data) {
          return `


                          <a type="button"
                        href="#"
                        onclick="openDocumentsModal(${data.id}); return false;"
                        class="btn btn-sm icon-btn-outline text-primary"
                        title="Documentos">
                        <i class="fas fa-folder-open"></i>
            </a>
                        <a type="button" href="#" onclick="openAccountStatementModal(${data.id}, 'grooming'); return false;" class="btn btn-sm icon-btn-outline text-primary" title="Estado de cuenta">
                            <i class="fas fa-dollar-sign"></i>
                        </a>
                       
                        ${
                          data.has_transfers
                            ? `<a type="button" href="#" onclick="openTransfersTrackingModal({reception_id: ${data.id}}); return false;" class="btn btn-sm icon-btn-outline text-info" title="Tracking de traslados">
                             <i class="fas fa-exchange-alt"></i>
                        </a>`
                            : ""
                        }`;
        },
      },
    ],
  });
}

function initTable4() {
  if (table4) {
    return;
  }
  table4 = $("#table4").DataTable({
    ajax: {
      url: route("reception.list", 4),
      data: function (d) {
        d.date = $("#filterHotelFechaAbierta").val();
        d.date_to = $("#filterHotelFechaAbiertaHasta").val();
        d.status_id = $("#filterHotelEstatus").val();
        d.exit_date = $("#filterHotelFechaSalida").val();
        d.account_status = $("#filterHotelCuenta").val();
      },
    },
    responsive: true,
    order: [0, "desc"],
    columns: [
      { data: "entry_date", render: formatDate },

      {
        data: null,
        render: function (data) {
          return data.vet ? data.vet.name : "";
        },
      },

      {
        data: null,
        render: function (data) {
          return data.family ? data.family.name : "";
        },
      },
      {
        data: null,
        render: function (data) {
          if (!data.pet) {
            return '<span class="text-muted">—</span>';
          }

          let textColorClass =
            data.pet.deceased === 1 ? "text-secondary" : "text-primary";

          let deceasedIcon =
            data.pet.deceased === 1 ? '<span class="mdi--cross"></span> ' : "";

          let speciesIcon = data.pet.species?.icon
            ? `<span class="${data.pet.species.icon}"></span> `
            : '<i class="fas fa-paw"></i> ';

          return `
      <a type="button"
         href="${route("pet-history.index", { id: data.pet.id, type: 4 })}"
         class="btn btn-sm pet-chip ${textColorClass}">
          ${deceasedIcon}${speciesIcon}${data.pet.name}
      </a>
    `;
        },
      },
      { data: "exit_date", render: formatDate },
      {
        data: null,
        render: function (data) {
          if (
            data &&
            data.current_hotel_status &&
            data.current_hotel_status.hotel_status
          ) {
            const status = data.current_hotel_status.hotel_status;
            return `<span style="background-color: ${lightenColor(status.color)}; padding: 5px 10px; color: ${status.color}; border-radius: 5px; font-weight: 600;">
                ${status.name}
              </span>`;
          }
          return "Sin estatus";
        },
      },
      { data: null, render: renderCuentaBadge },
      {
        data: null,
        render: function (data) {
          return `

                        <a type="button" href="${route("advance-payments.add", data.id)}" class="btn btn-sm icon-btn-outline text-primary" title="Crear Anticipo">
                            <span class="lets-icons--paper-fill"></span>
                        </a>

                                                <a type="button" href="#" onclick="openDocumentsModal(${data.id}); return false;"
                        class="btn btn-sm icon-btn-outline text-primary" title="Documentos"> <i class="fas fa-folder-open"></i>
                        </a>

                        <a type="button" href="#" onclick="openAccountStatementModal(${data.id}, 'hotel'); return false;" class="btn btn-sm icon-btn-outline text-primary" title="Estado de cuenta">
                            <i class="fas fa-dollar-sign"></i>
                        </a>
                       
                        ${
                          data.has_transfers
                            ? `<a type="button" href="#" onclick="openTransfersTrackingModal({reception_id: ${data.id}}); return false;" class="btn btn-sm icon-btn-outline text-info" title="Tracking de traslados">
                             <i class="fas fa-exchange-alt"></i>
                        </a>`
                            : ""
                        }
                       `;
        },
      },
    ],
  });
}

function initTable5() {
  if (table5) {
    return;
  }
  table5 = $("#table5").DataTable({
    ajax: {
      url: route("reception.list", 5),
      data: function (d) {
        d.date = $("#filterCremacionFecha").val();
        d.date_to = $("#filterCremacionFechaHasta").val();
        d.status_id = $("#filterCremacionEstatus").val();
        d.account_status = $("#filterCremacionCuenta").val();
      },
    },
    responsive: true,
    order: [0, "desc"],
    columns: [
      {
        data: null,
        orderable: false,
        render: function (data) {
          return `<input type="checkbox" class="cremation-checkbox" value="${data.id}">`;
        },
      },
      { data: "entry_date", render: formatDate },

      {
        data: null,
        render: function (data) {
          return data.receptionist ? data.receptionist.name : "";
        },
      },

      {
        data: null,
        render: function (data) {
          return data.family ? data.family.name : "";
        },
      },
      {
        data: null,
        render: function (data) {
          if (!data.pet) {
            return '<span class="text-muted">—</span>';
          }

          let textColorClass =
            data.pet.deceased === 1 ? "text-secondary" : "text-primary";

          let deceasedIcon =
            data.pet.deceased === 1 ? '<span class="mdi--cross"></span> ' : "";

          let speciesIcon = data.pet.species?.icon
            ? `<span class="${data.pet.species.icon}"></span> `
            : '<i class="fas fa-paw"></i> ';

          return `
      <a type="button"
         href="${route("pet-history.index", { id: data.pet.id, type: 5 })}"
         class="btn btn-sm pet-chip ${textColorClass}">
          ${deceasedIcon}${speciesIcon}${data.pet.name}
      </a>
    `;
        },
      },
      {
        data: null,
        render: function (data) {
          if (
            data &&
            data.current_status_cremation &&
            data.current_status_cremation.cremation_status
          ) {
            const status = data.current_status_cremation.cremation_status;
            return `<span style="background-color: ${lightenColor(status.color)}; padding: 5px 10px; color: ${status.color}; border-radius: 5px; font-weight: 600;">
                ${status.name}
              </span>`;
          }
          return "Sin estatus";
        },
      },
      { data: null, render: renderCuentaBadge },
      {
        data: null,
        render: function (data) {
          // Reception de tipo Cremación sin su Cremation todavía (ej.
          // traslado Hospitalización -> Cremación por alta por fallecimiento,
          // ver ReceptionTransferController::store()): el médico ya no llena
          // el formulario, así que aquí se ofrece completarlo con los datos
          // de la recepción ya creada.
          if (!data.cremation) {
            return `
                <a type="button" href="${route("new.cremation", { id: data.id })}" class="btn btn-sm btn-primary" title="Crear cremación">
                    <i class="fas fa-plus"></i>
                </a>`;
          }

          return `


                                                <a type="button" href="#" onclick="openAccountStatementModal(${data.id}, 'cremation'); return false;" class="btn btn-sm icon-btn-outline text-primary" title="Estado de cuenta">
                         <i class="fas fa-dollar-sign"></i>
                        </a>

                        <a type="button" href="#" onclick="openDocumentsModal(${data.id}); return false;"
                        class="btn btn-sm icon-btn-outline text-primary" title="Documentos"> <i class="fas fa-folder-open"></i>
                        </a>

                        ${
                          data.has_transfers
                            ? `<a type="button" href="#" onclick="openTransfersTrackingModal({reception_id: ${data.id}}); return false;" class="btn btn-sm icon-btn-outline text-info" title="Tracking de traslados">
                              <i class="fas fa-exchange-alt"></i>
                        </a>`
                            : ""
                        }`;
        },
      },
    ],
  });
}

$(document).on(
  "change",
  ".cremation-checkbox, #selectAllCremations",
  function () {
    const checked = $(".cremation-checkbox:checked").length;
    $("#cremationBulkBar").toggle(checked > 0);
    $("#cremationSelectedCount").text(checked + " seleccionados");
  },
);

$("#selectAllCremations").on("change", function () {
  $(".cremation-checkbox").prop("checked", this.checked).trigger("change");
});

$("#btnAdvanceStatus").on("click", async function () {
  const checkedBoxes = $(".cremation-checkbox:checked");
  const ids = checkedBoxes
    .map(function () {
      return parseInt(this.value);
    })
    .get();

  if (ids.length === 0) return;

  // "Entregado" es el último estatus y requiere firma individual del
  // propietario (responsiva de entrega de cenizas): no se puede avanzar en
  // lote hacia él, y avanzar una sola fila hacia él no actualiza el estatus
  // aquí — redirige a la responsiva, que es la única que lo actualiza al
  // guardarse (ver CremationController::entregaCenizasPdf()).
  const rowsGoingToEntregado = checkedBoxes.filter(function () {
    const rowData = table5.row($(this).closest("tr")).data();
    const nextName =
      window.cremationStatusNextMap[
        rowData.current_status_cremation?.cremation_status_id
      ];
    return nextName === "Entregado";
  });

  if (rowsGoingToEntregado.length > 0) {
    if (ids.length > 1) {
      Swal.fire({
        icon: "warning",
        title: "Selecciona una sola cremación",
        text: "La entrega de cenizas requiere firma individual del propietario — selecciona una sola cremación a la vez.",
      });
      return;
    }

    window.location.href = route("cremation.entrega-cenizas", ids[0]);
    return;
  }

  // Construir resumen de transiciones (agrupado por estatus actual -> siguiente)
  const transitionSummary = {};
  checkedBoxes.each(function () {
    const rowData = table5.row($(this).closest("tr")).data();
    const currentName =
      rowData.current_status_cremation?.cremation_status?.name || "Sin estatus";
    const nextName =
      window.cremationStatusNextMap[
        rowData.current_status_cremation?.cremation_status_id
      ] || "último estatus (sin cambio)";
    const key = `${currentName} → ${nextName}`;
    transitionSummary[key] = (transitionSummary[key] || 0) + 1;
  });

  const summaryHtml = Object.entries(transitionSummary)
    .map(
      ([transition, count]) =>
        `<div>${count} registro(s): <strong>${transition}</strong></div>`,
    )
    .join("");

  const result = await Swal.fire({
    title: "¿Avanzar estatus?",
    html: `<div style="text-align: left;">${summaryHtml}</div>`,
    icon: "question",
    showCancelButton: true,
    confirmButtonText: "Sí, avanzar",
    cancelButtonText: "Cancelar",
    confirmButtonColor: "#2f8fc4",
  });

  if (!result.isConfirmed) return;

  try {
    const response = await fetch(route("cremation.bulkAdvanceStatus"), {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "X-CSRF-TOKEN": document
          .querySelector('meta[name="csrf-token"]')
          .getAttribute("content"),
      },
      body: JSON.stringify({ ids }),
    });

    const data = await response.json();

    if (data.success) {
      table5.ajax.reload(null, false);
      $("#cremationBulkBar").hide();
      $("#selectAllCremations").prop("checked", false);

      let msg = `${data.updated.length} actualizados.`;
      if (data.skipped.length > 0) {
        msg += ` ${data.skipped.length} ya estaban en el último estatus.`;
      }
      Swal.fire({
        icon: "success",
        title: "Listo",
        text: msg,
        timer: 2500,
        showConfirmButton: false,
      });
    }
  } catch (error) {
    console.error("Error al avanzar estatus", error);
  }
});

// Recarga solo si la tabla ya fue inicializada (lazy init: table2..table5
// pueden no existir todavía si el usuario nunca abrió esa pestaña). No hace
// falta hacer nada más para el caso "el usuario cambió el filtro antes de
// que la tabla existiera": initTableN() lee el valor vigente del filtro en
// su propio callback ajax.data() la primera vez que se inicializa.
function reloadIfReady(dt) {
  if (dt) dt.ajax.reload(null, false);
}

// Evita que Desde quede después de Hasta (o viceversa): al cambiar uno, si
// cruza al otro, se arrastra el otro al mismo valor en vez de bloquear con
// un diálogo. Se registra ANTES que los listeners de recarga de tabla de
// abajo (mismo elemento, mismo evento "change") para que la tabla recargue
// ya con el rango corregido.
function setupDateRangeClamp(fromId, toId) {
  $("#" + fromId).on("change", function () {
    const from = $(this).val();
    const to = $("#" + toId).val();
    if (from && to && from > to) {
      $("#" + toId).val(from);
    }
  });
  $("#" + toId).on("change", function () {
    const from = $("#" + fromId).val();
    const to = $(this).val();
    if (from && to && to < from) {
      $("#" + fromId).val(to);
    }
  });
}

$(document).ready(function () {
  setupDateRangeClamp("filterConsultaFecha", "filterConsultaFechaHasta");
  setupDateRangeClamp("filterHospFecha", "filterHospFechaHasta");
  setupDateRangeClamp("filterGroomingFecha", "filterGroomingFechaHasta");
  setupDateRangeClamp(
    "filterHotelFechaAbierta",
    "filterHotelFechaAbiertaHasta",
  );
  setupDateRangeClamp("filterCremacionFecha", "filterCremacionFechaHasta");

  // Cada bloque de filtros recarga únicamente su propia tabla — nunca las
  // otras 4. "Limpiar filtros" restaura los selects a su default (Todos/
  // Todas, o el estatus default calculado en el backend por nombre, ver
  // ReceptionController::index()), pero las fechas quedan VACÍAS, no en
  // "hoy": TODAY_DATE solo es el valor inicial al cargar la página (ver
  // value="..." de cada <input type="date">) — limpiar es un acto explícito
  // del usuario para ver "todas las fechas", no un reset a ese default.
  $(
    "#filterConsultaFecha, #filterConsultaFechaHasta, #filterConsultaEstado, #filterConsultaMedico, #filterConsultaCuenta",
  ).on("change", function () {
    reloadIfReady(table);
  });
  $("#btnClearConsultaFilters").on("click", function () {
    $("#filterConsultaFecha").val("");
    $("#filterConsultaFechaHasta").val("");
    $("#filterConsultaEstado").val("");
    $("#filterConsultaMedico").val("");
    $("#filterConsultaCuenta").val("");
    reloadIfReady(table);
  });

  $(
    "#filterHospFecha, #filterHospFechaHasta, #filterHospEstatus, #filterHospArea, #filterHospAdmision, #filterHospCuenta",
  ).on("change", function () {
    reloadIfReady(table2);
  });
  $("#btnClearHospFilters").on("click", function () {
    $("#filterHospFecha").val("");
    $("#filterHospFechaHasta").val("");
    $("#filterHospEstatus").val("");
    $("#filterHospArea").val("");
    $("#filterHospAdmision").val("");
    $("#filterHospCuenta").val("");
    reloadIfReady(table2);
  });

  $(
    "#filterGroomingFecha, #filterGroomingFechaHasta, #filterGroomingEstatus, #filterGroomingColaborador, #filterGroomingCuenta",
  ).on("change", function () {
    reloadIfReady(table3);
  });
  $("#btnClearGroomingFilters").on("click", function () {
    $("#filterGroomingFecha").val("");
    $("#filterGroomingFechaHasta").val("");
    $("#filterGroomingEstatus").val("");
    $("#filterGroomingColaborador").val("");
    $("#filterGroomingCuenta").val("");
    reloadIfReady(table3);
  });

  $(
    "#filterHotelFechaAbierta, #filterHotelFechaAbiertaHasta, #filterHotelEstatus, #filterHotelFechaSalida, #filterHotelCuenta",
  ).on("change", function () {
    reloadIfReady(table4);
  });
  $("#btnClearHotelFilters").on("click", function () {
    $("#filterHotelFechaAbierta").val("");
    $("#filterHotelFechaAbiertaHasta").val("");
    $("#filterHotelEstatus").val("");
    $("#filterHotelFechaSalida").val("");
    $("#filterHotelCuenta").val("");
    reloadIfReady(table4);
  });

  $(
    "#filterCremacionFecha, #filterCremacionFechaHasta, #filterCremacionEstatus, #filterCremacionCuenta",
  ).on("change", function () {
    reloadIfReady(table5);
  });
  $("#btnClearCremacionFilters").on("click", function () {
    $("#filterCremacionFecha").val("");
    $("#filterCremacionFechaHasta").val("");
    $("#filterCremacionEstatus").val("");
    $("#filterCremacionCuenta").val("");
    reloadIfReady(table5);
  });
});

$(document).ready(function () {
  // La pestaña "Consultas" está activa por defecto, así que su tabla se
  // inicializa de inmediato; el resto se inicializa la primera vez que se
  // muestra su pestaña (shown.bs.tab) para evitar que DataTables calcule
  // mal el ancho de columnas dentro de un tab-pane oculto.
  initTable();

  $("#tab-hospitalizaciones-tab").on("shown.bs.tab", initTable2);
  $("#tab-grooming-tab").on("shown.bs.tab", initTable3);
  $("#tab-hotel-tab").on("shown.bs.tab", function () {
    initTable4();
    loadHotelCubicleAvailability();
  });
  $("#tab-cremaciones-tab").on("shown.bs.tab", initTable5);

  startPollingReceptions();
});

// Mapa de tab-pane -> instancia de DataTable correspondiente. Se usa una
// función por entrada (no la variable directamente) porque al momento de
// registrar el mapa la mayoría de las tablas todavía no existen (lazy-init).
const receptionTabTables = {
  "tab-consultas": () => table,
  "tab-hospitalizaciones": () => table2,
  "tab-grooming": () => table3,
  "tab-hotel": () => table4,
  "tab-cremaciones": () => table5,
};

function getActiveReceptionTable() {
  const activePaneId = $("#receptionTabsContent > .tab-pane.active").attr("id");
  const getTable = receptionTabTables[activePaneId];
  return getTable ? getTable() : null;
}

let pollingReceptions = null;
let lastUpdateReceptions = null;

function startPollingReceptions() {
  if (pollingReceptions) return;

  pollingReceptions = setInterval(function () {
    $.ajax({
      url: route("receptions.lastUpdateGlobal"),
      method: "GET",
      success: function (response) {
        if (lastUpdateReceptions === null) {
          lastUpdateReceptions = response.last_update;
          return;
        }

        if (response.last_update !== lastUpdateReceptions) {
          lastUpdateReceptions = response.last_update;
          const activeTable = getActiveReceptionTable();
          if (activeTable) {
            activeTable.ajax.reload(null, false);
          }
        }
      },
    });
  }, 30000);
}

// Widget informativo (no bloqueante) de disponibilidad de cubículos por
// pensión en el tab Hotel del Index — reutiliza el conteo agregado de
// HotelController::availabilitySummary() (mismos modelos Cubicle/CubicleType
// que ya usa hotel/cubicles/view). Se recarga solo al mostrar el tab, no
// hace falta engancharlo al polling de 30s: es informativo, no crítico.
async function loadHotelCubicleAvailability() {
  const $container = $("#hotelCubicleAvailability");
  if (!$container.length) return;

  try {
    const resp = await fetch(route("hotel.availability-summary"));
    if (!resp.ok) return;

    const summary = await resp.json();
    $container.empty();
    $container
      .removeClass("d-flex flex-wrap gap-2")
      .addClass("cubicle-availability-grid");

    summary.forEach((row) => {
      const name = row.cubicle_type ? row.cubicle_type.name : "Sin tipo";
      const available = Number(row.available);
      const total = Number(row.total);
      const occupied = total - available;
      const pct = total > 0 ? (available / total) * 100 : 0;
      const caption =
        occupied > 0
          ? `${occupied} ocupado${occupied === 1 ? "" : "s"}`
          : "Sin ocupación";
      const iconSvg = name.toLowerCase().includes("gato")
        ? `<svg viewBox="0 0 24 24" fill="none" stroke="#1565c0" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M18 12c0-2-1-3-1-5 0-1 1-2 1-2s-2 0-3 1c-1-.5-2-.5-3-.5s-2 0-3 .5c-1-1-3-1-3-1s1 1 1 2c0 2-1 3-1 5 0 3.5 2.5 6 6 6s6-2.5 6-6z"/><path d="M9 13v.01"/><path d="M15 13v.01"/></svg>`
        : `<svg viewBox="0 0 24 24" fill="none" stroke="#1565c0" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="8" width="18" height="10" rx="1.5"/><path d="M3 12h18"/><path d="M7 8V6a2 2 0 012-2h6a2 2 0 012 2v2"/></svg>`;

      $container.append(`
        <div class="cubicle-tile">
          <div class="cubicle-tile-top">
            <div class="cubicle-tile-icon">${iconSvg}</div>
            <div class="cubicle-tile-label">${name}</div>
          </div>
          <div class="cubicle-tile-value-row">
            <span class="cubicle-tile-value">${available}</span>
            <span class="cubicle-tile-of">/ ${total} disponibles</span>
          </div>
          <div class="cubicle-tile-meter-track">
            <div class="cubicle-tile-meter-fill" style="width: ${pct}%;"></div>
          </div>
          <div class="cubicle-tile-caption">${caption}</div>
        </div>
      `);
    });
  } catch (error) {
    console.error("Error al cargar disponibilidad de cubículos:", error);
  }
}

let currentReceptionId = null;

let currentFamilyPhone = null; // agregar junto a donde ya declaras currentReceptionId

async function openDocumentsModal(receptionId, familyPhone) {
  currentReceptionId = receptionId;
  currentFamilyPhone = familyPhone || null;

  const $body = $("#documents_body");

  $body.html(`
        <tr>
            <td colspan="3" class="text-center">
                Cargando...
            </td>
        </tr>
    `);

  bootstrap.Modal.getOrCreateInstance(
    document.getElementById("documentsModal"),
  ).show();

  try {
    const response = await fetch(route("receptions.documents", receptionId), {
      headers: {
        Accept: "application/json",
        "X-Requested-With": "XMLHttpRequest",
      },
    });

    if (!response.ok) {
      throw new Error();
    }

    const { documents, missing_formats } = await response.json();

    if (documents.length === 0 && missing_formats.length === 0) {
      $body.html(`
                <tr>
                    <td colspan="3" class="text-center text-muted">
                        No hay documentos asociados.
                    </td>
                </tr>
            `);

      return;
    }

    const documentRows = documents
      .map(
        (doc) => `
        <tr>
            <td>${doc.type}</td>
            <td>${doc.created_at}</td>
            <td class="text-center">
                <a href="${doc.url}" target="_blank" class="btn btn-sm icon-btn-outline text-primary" title="Ver">
                    <i class="fas fa-eye"></i>
                </a>
                <button type="button" class="btn btn-sm icon-btn-outline text-success" title="Enviar por WhatsApp"
                    onclick="sendDocumentWhatsApp('${doc.url}', '${doc.type.replace(/'/g, "\\'")}')">
                    <i class="ri--whatsapp-fill"></i>
                </button>
            </td>
        </tr>
    `,
      )
      .join("");

    // Responsivas que el tipo de recepción requiere y todavía no existen
    // (ver ReceptionController::requiredFormatsFor()): misma fila que un
    // documento normal, solo cambia el botón de acción.
    const missingRows = missing_formats
      .map(
        (fmt) => `
        <tr>
            <td>${fmt.name}</td>
            <td class="text-muted">Pendiente</td>
            <td class="text-center">
                <a href="${fmt.url}" target="_blank" class="btn btn-sm icon-btn-outline text-warning" title="Generar responsiva">
                    <i class="fas fa-pencil-alt"></i>
                </a>
            </td>
        </tr>
    `,
      )
      .join("");

    $body.html(documentRows + missingRows);
  } catch (e) {
    $body.html(`
            <tr>
                <td colspan="3" class="text-danger text-center">
                    Error al cargar los documentos.
                </td>
            </tr>
        `);

    console.error(e);
  }
}

async function sendDocumentWhatsApp(docUrl, docType) {
  event.preventDefault();

  if (!currentFamilyPhone) {
    Swal.fire({
      icon: "warning",
      title: "Sin teléfono",
      text: "Esta familia no tiene un número de teléfono registrado.",
    });
    return;
  }

  const result = await Swal.fire({
    icon: "question",
    title: "Enviar documento",
    text: "Será redirigido a WhatsApp para enviar el documento.",
    showConfirmButton: true,
    confirmButtonText: "Continuar",
    showCancelButton: true,
    cancelButtonText: "Cancelar",
  });

  if (result.isConfirmed) {
    const mensaje = `Hola, te compartimos tu documento (${docType}): ${docUrl}`;
    const whatsappURL = `https://wa.me/${currentFamilyPhone}?text=${encodeURIComponent(mensaje)}`;

    window.open(whatsappURL, "_blank");
  }
}

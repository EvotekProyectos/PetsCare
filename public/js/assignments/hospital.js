const areas = {
  1: "#079BCE", // Quirúrgicos (Azul)
  2: "#F54245", // Cuidado Intensivo (Rojo)
  3: "#2ecb56", // Internos (Verde)
  4: "#ff7855", // Felinos (Salmón)
  5: "#8C65B5", // Infecciosos (Morado)
};

var table = undefined;

$(document).ready(function () {
  table = $("#table").DataTable({
    ajax: {
      url: route("assignment.hospitalizations"),
      data: function (d) {
        d.date = $("#filterConsultaFecha").val();
        d.status_id = $("#filterConsultaEstado").val();
      },
      type: "GET",
      headers: {
        "X-Requested-With": "XMLHttpRequest",
      },
      error: function (xhr, error, thrown) {
        if (xhr.status === 403) {
        } else {
          console.error("Error en DataTable:", xhr.responseText);
        }
        table.clear().draw();
      },
    },
    responsive: true,
    order: [0, "desc"],
    columns: [
      { data: "entry_date", render: formatDate },
      {
        data: null,
        render: function (data) {
          return data.admission_type ? data.admission_type.name : "";
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
          return data.vet ? data.vet.name : "";
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
            const status = data.current_hospitalization_status.hospitalization_status;
            return `<span style="background-color: ${lightenColor(status.color)}; padding: 5px 10px; color: ${status.color}; border-radius: 5px; font-weight: 600;">
                ${status.name}
              </span>`;
          }
          return "Sin estatus";
        },
      },
      {
        data: null,
        render: function (data) {
          // Editable en "Trasladado" (recién trasladada desde Consulta, el
          // médico ya puede trabajar en Red Sheet sin esperar pago ni
          // responsiva firmada) y en "Hospitalizado" — mismo criterio que
          // RedSheetController::entry(). "Dado de alta" u otro estatus:
          // solo lectura. entry() sigue siendo la autoridad real (si esta
          // hospitalización ya fue trasladada a otra -isTransferred()-,
          // igual redirige a modo lectura aunque el botón de aquí diga
          // "Atender").
          const isEditableStatus =
            data.status_id === HOSPITALIZATION_STATUS_HOSPITALIZADO_ID ||
            data.status_id === HOSPITALIZATION_STATUS_TRASLADADO_ID;

          if (!isEditableStatus) {
            return `
                          <a type="button" href="${route("redsheet.show", data.id)}" class="btn btn-sm icon-btn-outline text-primary" title="Ver">
                            <i class="fas fa-eye"></i>
                        </a>

                       `;
          }

          return `
                          <a type="button" href="${route("redsheet.entry", data.id)}" class="btn btn-sm icon-btn-outline text-primary" title="Atender">
                            <span class="mage--hospital-shield-fill"></span>
                        </a>

                       `;
        },
      },
    ],
  });

  // La fila de filtros vivía comentada en el blade (los ids ya se leían
  // arriba en ajax.data(), pero nada disparaba la recarga al cambiarlos).
  $("#filterConsultaFecha, #filterConsultaEstado").on("change", function () {
    table.ajax.reload(null, false);
  });

  $("#btnClearConsultaFilters").on("click", function () {
    $("#filterConsultaFecha").val("");
    $("#filterConsultaEstado").val("");
    table.ajax.reload(null, false);
  });

  startPollingHospitalizations();
});

// pollForChanges (global.js) ya hace el chequeo inicial DE INMEDIATO (esta
// vista ya lo hacía bien por su cuenta antes de este cambio) y además, ahora,
// fuerza un chequeo extra al volver de bfcache ('pageshow' + persisted) —
// mismo helper compartido que receptions/index.js.
let pollingHospitalizations = null;

function startPollingHospitalizations() {
  if (pollingHospitalizations) return;

  pollingHospitalizations = pollForChanges({
    checkFn: function () {
      return $.ajax({
        url: route("assignment.lastUpdateHospitalizations"),
        method: "GET",
      }).then(function (response) {
        return response.last_update;
      });
    },
    onChanged: function () {
      table.ajax.reload(null, false);
    },
    intervalMs: 30000,
  });
}

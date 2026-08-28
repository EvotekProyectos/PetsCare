var table;

$(document).ready(function () {
  table = $("#table").DataTable({
    ajax: {
      url: route("assignment.appointments"),
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
          return data.reception_type ? data.reception_type.name : "";
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
            return `<span style="background-color: ${lightenColor(status.color)}; padding: 5px 10px; color: ${status.color}; border-radius: 5px; font-weight: 600;">
                ${status.name}
              </span>`;
          }
          return "Sin estatus";
        },
      },
      {
        data: null,
        className: "text-center",
        render: function (data) {
          // En espera: atender -> confirma y cambia el estatus a En
          // consulta (ver Attend()). En consulta: reabrir sin tocar el
          // estatus (Attend() ya no lo cambia cuando Status != En espera,
          // solo redirige). Cualquier otro estatus (Finalizada/Trasladado):
          // ya no se puede iniciar/modificar la consulta, solo verla en modo
          // lectura — no pasa por Attend() en absoluto. IDs por nombre desde
          // el backend (ATTENTION_STATUS_*), no hardcodeados — ver
          // AssignmentController::index().
          if (data.status_id === ATTENTION_STATUS_EN_ESPERA_ID) {
            return `
                        <button type="button" class="btn btn-sm icon-btn-outline text-primary" title="Atender"
                            onclick="Attend(${data.reception_type_id}, ${data.id}, ${data.status_id});">
                            <i class="fas fa-check-circle text-success"></i>
                        </button>`;
          }

          if (data.status_id === ATTENTION_STATUS_EN_CONSULTA_ID) {
            return `
                        <button type="button" class="btn btn-sm icon-btn-outline text-primary" title="Abrir consulta"
                            onclick="Attend(${data.reception_type_id}, ${data.id}, ${data.status_id});">
                     <i class="fas fa-check-circle text-success"></i>
                        </button>`;
          }

          return `
                        <a href="${route("appointment.show", data.id)}" class="btn btn-sm icon-btn-outline text-primary" title="Ver">
                            <i class="fas fa-eye"></i>
                        </a>`;
        },
      },
    ],
  });

  // Cambiar cualquier filtro recarga la tabla por AJAX (sin recargar la
  // página); "Limpiar filtros" restaura Estado a su default de esta
  // pantalla (En espera, calculado en el backend por nombre del catálogo —
  // ver AssignmentController::index()) y dejar Fecha vacía (todas las
  // fechas), mismo criterio que las 5 tablas de Recepción.
  $("#filterConsultaFecha, #filterConsultaEstado").on("change", function () {
    table.ajax.reload();
  });

  $("#btnClearConsultaFilters").on("click", function () {
    $("#filterConsultaFecha").val("");
    $("#filterConsultaEstado").val("");
    table.ajax.reload();
  });

  startPollingAppointments();
});

// Mismo patrón que startPollingReceptions() (public/js/receptions/index.js):
// intervalo de 30s, snapshot local de last_update, se salta el reload en el
// primer tick (evita un reload espurio justo al cargar la página), recarga
// sin resetear la página actual (reload(null, false)) solo si cambió.
let pollingAppointments = null;
let lastUpdateAppointments = null;

function startPollingAppointments() {
  if (pollingAppointments) return;

  pollingAppointments = setInterval(function () {
    $.ajax({
      url: route("assignment.lastUpdateAppointments"),
      method: "GET",
      success: function (response) {
        if (lastUpdateAppointments === null) {
          lastUpdateAppointments = response.last_update;
          return;
        }

        if (response.last_update !== lastUpdateAppointments) {
          lastUpdateAppointments = response.last_update;
          table.ajax.reload(null, false);
        }
      },
    });
  }, 30000);
}

async function Attend(Type, ID, Status) {
  event.preventDefault();
  if (Status !== ATTENTION_STATUS_EN_ESPERA_ID) {
    Swal.fire({
      icon: "info",
      title: "Redirigiendo...",
      text: "Este paciente ya inició su proceso de atención.",
      allowOutsideClick: false,
      allowEscapeKey: false,
      showConfirmButton: false,
      didOpen: () => {
        Swal.showLoading();
      },
    });

    window.location.href = route("appointment.consultation", ID);
    return;
  } else {
    const result = await Swal.fire({
      title: "¿Estás listo para atender este paciente?",
      text: "Confirma su atención",
      icon: "question",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Sí, atender",
      cancelButtonText: "No, continuar en espera",
    });
    if (result.isConfirmed && Type === 1) {
      if (Type === 1) {
        let url = route("reception-status-histories.store");
        let form = new FormData();
        form.append("reception_id", ID);
        form.append("attention_status_id", ATTENTION_STATUS_EN_CONSULTA_ID);
        let csrfToken = document.querySelector('input[name="_token"]').value;
        form.append("_token", csrfToken);
        let pet = await fetch(url, { method: "POST", body: form });
        if (pet.ok) {
          window.location.href = route("appointment.consultation", ID);
        }
      }
    }
  }
}

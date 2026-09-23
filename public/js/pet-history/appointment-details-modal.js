// Modal "Ver Detalles" de una Consulta (ver
// resources/views/pet_history/partials/appointment-details-modal.blade.php).
// Reemplaza, únicamente para el tipo Consulta, la navegación a
// appointment.show (página completa) desde el Historial de la mascota —
// ver el cambio en Details() en public/js/pet-history/view.js. La página
// appointment/show.blade.php sigue existiendo intacta para cualquier otro
// punto del sistema que la use.

let consultaServicesTable = null;

async function openConsultaDetailsModal(receptionId) {
  const $loading = $("#consultaDetailsLoading");
  const $content = $("#consultaDetailsContent");

  $content.addClass("d-none");
  $loading.removeClass("d-none");
  $("#consultaDetailsServiciosWrapper").addClass("d-none");
  $("#consultaDetailsPrescriptionWrapper").addClass("d-none");

  bootstrap.Modal.getOrCreateInstance(document.getElementById("consultaDetailsModal")).show();

  try {
    const response = await fetch(route("appointment.detailsModal", receptionId), {
      headers: { Accept: "application/json", "X-Requested-With": "XMLHttpRequest" },
    });

    if (!response.ok) {
      throw new Error("No se pudo obtener el detalle de la consulta.");
    }

    const data = await response.json();

    $("#consultaDetailsRegistro").html(data.registro_html);

    if (data.has_prescription) {
      $("#consultaDetailsPrescription").html(data.prescription_html);
      $("#consultaDetailsPrintPrescription").attr(
        "href",
        route("prescription.imprimir", data.prescription_id)
      );
      $("#consultaDetailsPrescriptionWrapper").removeClass("d-none");
    }

    if (data.has_services) {
      $("#consultaDetailsServiciosWrapper").removeClass("d-none");
      initConsultaServicesTable(receptionId);
    }

    $loading.addClass("d-none");
    $content.removeClass("d-none");
  } catch (error) {
    console.error("Error al cargar el detalle de la consulta:", error);
    $loading.addClass("d-none");
    bootstrap.Modal.getInstance(document.getElementById("consultaDetailsModal"))?.hide();
    Swal.fire({
      icon: "error",
      title: "Error",
      text: "No se pudo cargar el detalle de la consulta.",
    });
  }
}

// Mismas columnas que appointments/show.js (#DataServices de
// appointment/show.blade.php) — el modal reutiliza el mismo endpoint
// (appointment-services.registros), solo cambia el <table> destino. Se
// destruye y reinicializa en cada apertura porque el modal es único en el
// DOM y puede abrirse para distintas recepciones.
function initConsultaServicesTable(receptionId) {
  if ($.fn.DataTable.isDataTable("#consultaDetailsServicesTable")) {
    $("#consultaDetailsServicesTable").DataTable().destroy();
    $("#consultaDetailsServicesTable tbody").empty();
  }

  const serviceTypeColors = {
    Laboratorio: "#7C3AED",
    Imagen: "#EA580C",
    Vacuna: "#16A34A",
  };

  consultaServicesTable = $("#consultaDetailsServicesTable").DataTable({
    ajax: route("appointment-services.registros", receptionId),
    responsive: true,
    order: [[0, "asc"]],
    columns: [
      {
        data: "tipo",
        render: function (tipo) {
          if (!tipo) return "";
          const color = serviceTypeColors[tipo] || "#6c757d";
          return `<span style="color: ${color}; background-color: ${lightenColor(color)}; padding: 5px 10px; border-radius: 5px; font-weight: 600;">
                ${tipo}
              </span>`;
        },
      },
      {
        data: "nombre",
        render: function (nombre) {
          if (!nombre) return "";
          return `<span class="neutral-chip">${nombre}</span>`;
        },
      },
      { data: "observations" },
      { data: "vet" },
      {
        data: null,
        orderable: false,
        render: function (data) {
          if (!data.active_voucher_folio) {
            return "";
          }
          return `<span class="badge bg-primary">${data.active_voucher_folio}</span>`;
        },
      },
    ],
  });
}

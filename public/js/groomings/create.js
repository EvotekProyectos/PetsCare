// window.onload = function () {
//   first(type);
// };

let isUpdating = false;

$(document).ready(function () {
  // closeOnSelect: false, mismo criterio que los selects múltiples de
  // RedSheet (createredsheets.js): permite elegir varios servicios/vacunas
  // sin que el dropdown se cierre después de cada selección.
  $("#service_id").select2({
    placeholder: "Buscar Servicio",
    width: "resolve",
    closeOnSelect: false,
  });
  $("#service_id_vaccine").select2({
    placeholder: "Buscar Servicio",
    width: "resolve",
    closeOnSelect: false,
  });
});

function toggleDeliveryReferences() {
  const deliveryService = document.getElementById("delivery_service").value;
  const references = document.getElementById("delivery_references_container");

  if (deliveryService == "1") {
    references.style.display = "block";
  } else {
    references.style.display = "none";
  }
}

document.addEventListener("DOMContentLoaded", function () {
  // Estado inicial
  toggleDeliveryReferences();

  // Cuando cambie el select
  document
    .getElementById("delivery_service")
    .addEventListener("change", toggleDeliveryReferences);
});

async function Add() {
  event.preventDefault();

  let url = route("groomings.store");
  // .value en un <select multiple> solo da la primera opción marcada; con
  // jQuery/select2, .val() da el arreglo completo (o null si no hay nada).
  let serviceValues = $("#service_id").val() || [];
  let vaccineValues = $("#service_id_vaccine").val() || [];

  if (serviceValues.length === 0 && vaccineValues.length === 0) {
    return;
  }

  let successLabels = [];
  let errorMessages = [];

  async function sendForm(formId, label, clearSelectId) {
    let formData = new FormData(document.getElementById(formId));

    try {
      let response = await fetch(url, { method: "POST", body: formData });
      let respData = await response.json();

      if (response.ok) {
        successLabels.push(label);
        $(`#${clearSelectId}`).val(null).trigger("change");
      } else {
        errorMessages.push(
          `${label}: ${respData.message || "Ocurrió un error en el registro."}`,
        );
      }
    } catch (error) {
      errorMessages.push(`${label}: Ocurrió un error en el registro.`);
    }
  }

  if (serviceValues.length > 0) {
    await sendForm("NewGroomingServ", "Servicio", "service_id");
  }
  if (vaccineValues.length > 0) {
    await sendForm("NewVaccineServ", "Vacuna", "service_id_vaccine");
  }

  if (successLabels.length > 0) {
    table.ajax.reload();

    await Swal.fire({
      icon: "success",
      title:
        successLabels.length === 2
          ? "Servicio y vacuna registrados con éxito"
          : `${successLabels[0]} registrado con éxito`,
      timer: 1000,
      showConfirmButton: false,
      timerProgressBar: true,
    });
  }

  if (errorMessages.length > 0) {
    await Swal.fire({
      icon: "error",
      title: "Error al guardar",
      text: errorMessages.join("\n"),
    });
  }
}

var table = undefined;
$(document).ready(function () {
  table = $("#table").DataTable({
    ajax: route("groomings.list", Reception_Id),
    responsive: true,
    order: [0, "desc"],
    columns: [
      {
        data: "serv.NOMBRE",
        render: function (nombre) {
          if (!nombre) return "";
          return `<span class="neutral-chip">${nombre}</span>`;
        },
      },
      // {
      //     data: 'notes',
      // },
      {
        data: null,
        render: function (data) {
          const precio = data.service?.PRECIO ?? data.vaccine?.PRECIO ?? 0;
          return `$${parseFloat(precio).toFixed(2)}`;
        },
      },
      {
        data: null,
        render: function (data) {
          return `
                        <button type="button" class="btn btn-sm text-primary" onclick="showAlertWithCallback(() => deleteGrooming(${data.id}, table));">
                            <i class="fas fa-trash"></i>
                        </button>`;
        },
      },
    ],
  });
  table.on("draw", function () {
    calculateTotal(table);
  });
});

function calculateTotal(table) {
  let total = 0;

  table.rows({ page: "all" }).every(function () {
    const data = this.data();
    if (data.service && data.service.PRECIO) {
      total += parseFloat(data.service.PRECIO);
    }
  });

  $("#total-price").text(`Total Final: $${total.toFixed(2)}`);
}

async function generate(event) {
  event.preventDefault();
  Swal.fire({
    title: "Procesando...",
    text: "Por favor espera mientras procesamos la solicitud.",
    allowOutsideClick: false,
    didOpen: () => {
      Swal.showLoading();
    },
  });

  try {
    let url = route("general-groomings.store");
    let form = new FormData(document.getElementById("NewGrooming"));

    let response = await fetch(url, { method: "POST", body: form });
    if (!response.ok) throw new Error("Error al crear el servicio");

    await Swal.fire({
      icon: "success",
      title: "Servicio de grooming registrado correctamente",
      timer: 5000,
      showConfirmButton: true,
    });

    window.location.href = route("grooming.sign", Reception_Id);
  } catch (error) {
    console.error("Error:", error);
    Swal.fire({
      icon: "error",
      title: "Error inesperado",
      text: "Por favor, intenta nuevamente.",
    });
  }
}

window.onload = function () {
  // let fileRoute = Pic_route.startsWith('/') ? Pic_route.substring(1) : Pic_route;

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
  $("#lab_type_id").select2({
    theme: "bootstrap-5",
    dropdownParent: $("#ModalLab"),
  });
  $("#imaging_type_id").select2({
    theme: "bootstrap-5",
    dropdownParent: $("#ModalImg"),
  });
  // $("#product1").select2({
  //     theme: "bootstrap-5",
  //     dropdownParent: $('#ModalCertificate')
  // });
  // $("#product2").select2({
  //     theme: "bootstrap-5",
  //     dropdownParent: $('#ModalCertificate')
  // });
  // $("#product3").select2({
  //     theme: "bootstrap-5",
  //     dropdownParent: $('#ModalCertificate')
  // });
});

document.addEventListener("DOMContentLoaded", function () {
  const date1 = document.getElementById("application_date1");
  const date2 = document.getElementById("application_date2");
  const date3 = document.getElementById("application_date3");

  const diagnosis_appointment = document.getElementById("diagnosis");
  const diagnosis_prescrition = document.getElementById(
    "diagnosis_prescription",
  );
  if (date1 && date2 && date3) {
    date1.addEventListener("change", function () {
      date2.value = date1.value;
      date3.value = date1.value;
    });
  }
  if (diagnosis_appointment && diagnosis_prescrition) {
    diagnosis_appointment.addEventListener("change", function () {
      diagnosis_prescrition.value = diagnosis_appointment.value;
    });
  }
});

async function AddPrescription() {
  event.preventDefault();
  let url = route("prescriptions.store");
  let form = new FormData(document.getElementById("NewPrescription"));
  let pet = await fetch(url, { method: "POST", body: form });
  let resp = await pet.json();

  if (pet.ok) {
    Swal.fire({
      icon: "success",
      title: "Se guardo la receta médica",
      timer: 1000,
      showConfirmButton: false,
      timerProgressBar: true,
    });
    let prescription = resp.id;
    window.open(route("prescription.imprimir", prescription), "_blank");
  } else {
    let resp = await pet.json();
    Swal.fire({
      icon: "error",
      body: resp,
    });
  }
}

async function EndAppointment() {
  event.preventDefault();

  const dayNextCheck = document.getElementById("day_next_check").value;
  const interpretation = document.getElementById("diagnosis").value;
  const prescription = document.getElementById("medicine").value;

  if (!interpretation.trim()) {
    Swal.fire({
      icon: "warning",
      title: "Dato Obligatorio",
      text: "Por favor, en interpretación registra el diagnóstico.",
    });
    return;
  }

  const result = await Swal.fire({
    title: "¿Finalizar consulta?",
    text: "Los datos de la consulta y fórmula médica serán guardados.",
    icon: "question",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Sí, finalizar.",
    cancelButtonText: "No, continuar consulta.",
  });

  if (result.isConfirmed) {
    Swal.fire({
      title: "Procesando...",
      text: "Por favor espera mientras guardamos la consulta y la receta médica.",
      allowOutsideClick: false,
      didOpen: () => {
        Swal.showLoading();
      },
    });

    try {
      let url = route("appointments.store");
      let form = new FormData(document.getElementById("NewAppointment"));

      let pet = await fetch(url, { method: "POST", body: form });

      if (!pet.ok) {
        throw new Error("Error al guardar la cita");
      }

      // La cita ya quedó guardada en BD, el borrador local ya no sirve.
      if (typeof clearAppointmentDraft === "function") {
        clearAppointmentDraft(Reception_Id);
      }

      if (prescription.trim()) {
        let url2 = route("prescriptions.store");
        let form2 = new FormData(document.getElementById("NewPrescription"));
        const dateInput = document.getElementById("day_next_check");
        form2.append("day_next_check", dateInput.value);

        let pet2 = await fetch(url2, { method: "POST", body: form2 });
        let resp2 = await pet2.json();

        if (!pet2.ok) throw new Error("Error al guardar la prescripción");
        // Abre la receta si se creó
        if (resp2?.id) {
          let prescription = resp2.id;
          window.open(route("prescription.imprimir", prescription), "_blank");
        }
      }

      Swal.close();

      Swal.fire({
        icon: "success",
        title: "Consulta finalizada con éxito",
        text: "La consulta se finalizó correctamente.",
        timer: 1000,
        showConfirmButton: false,
        timerProgressBar: true,
      }).then(() => {
        window.location.href = route("assignment.index");
      });
    } catch (error) {
      console.error(error);
      Swal.fire({
        icon: "error",
        title: "Error",
        text: "Ocurrió un problema al procesar la solicitud.",
      });
    }
  }
}
function getServices(ServiceData) {
  return ServiceData.reduce((options, service) => {
    options[service.ARTICULO_ID] = service.NOMBRE;

    return options;
  }, {});
}

var table = undefined;
$(document).ready(function () {
  table = $("#table").DataTable({
    ajax: route("reception.historial", Pet_Id),
    responsive: true,
    order: [0, "desc"],
    columns: [
      {
        data: "entry_date",
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
          return data.reception_type ? data.reception_type.name : "";
        },
      },
      {
        data: null,
        render: function (data) {
          return data.reason ? data.reason.name : "";
        },
      },
      {
        data: null,
        render: function (data) {
          return `
                        <a class="btn btn-sm btn-primary"  title="Ver Detalles" href="#" onclick="Details(${data.reception_type_id}, ${data.id});">
                            <span class="mage--hospital-shield-fill"></span>
                        </a>`;
        },
      },
    ],
  });
});

async function Details(Type, ID) {
  event.preventDefault();
  let actual = Number(document.getElementById("reception_id").value);
  if (ID === actual) {
    Swal.fire({
      icon: "warning",
      title: "Este resgitro es la consulta actual",
      timer: 7000,
      showConfirmButton: true,
    });
  } else {
    if (Type === 1) {
      window.open(route("appointment.historic", ID), "_blank");
    }
  }
}

async function OpenCarnet() {
  document.getElementById("pet_id").value = Pet_Id;
  document.getElementById("reception_id").value = Reception_Id;
  $("#ModalCertificate").modal("show");
}

async function OpenLabs() {
  document.getElementById("reception_id_labs").value = Reception_Id;
  $("#ModalLab").modal("show");
}

async function OpenImgs() {
  document.getElementById("reception_id_img").value = Reception_Id;
  $("#ModalImg").modal("show");
}

async function AddLabs() {
  event.preventDefault();
  let url = route("appointment-services.store");
  let form = new FormData(document.getElementById("NewLab"));
  let pet = await fetch(url, { method: "POST", body: form });
  let resp = await pet.json();

  if (pet.ok) {
    Swal.fire({
      icon: "success",
      title: "Se guardo el laboratorio con exito",
      timer: 1000,
      showConfirmButton: false,
      timerProgressBar: true,
    });
    LabTable.ajax.reload();
    ServicesTable.ajax.reload();
    closeModalLabs();
  } else {
    let resp = await pet.json();
    Swal.fire({
      icon: "error",
      body: resp,
    });
  }
}

function closeModalLabs() {
  $("#lab_type_id").val("").trigger("change");
  $("#observations_labs").val("");
  $("#ModalLab").modal("hide");
}

async function AddImgs() {
  event.preventDefault();
  let url = route("appointment-services.store");
  let form = new FormData(document.getElementById("NewImg"));
  let pet = await fetch(url, { method: "POST", body: form });
  let resp = await pet.json();

  if (pet.ok) {
    Swal.fire({
      icon: "success",
      title: "Se guardo la imagenologia con exito",
      timer: 1000,
      showConfirmButton: false,
      timerProgressBar: true,
    });
    ImgTable.ajax.reload();
    ServicesTable.ajax.reload();
    closeModalImgs();
  } else {
    let resp = await pet.json();
    Swal.fire({
      icon: "error",
      body: resp,
    });
  }
}

function closeModalImgs() {
  $("#imaging_type_id").val("").trigger("change");
  $("#observations_img").val("");
  $("#ModalImg").modal("hide");
}

var LabTable = undefined;
$(document).ready(function () {
  LabTable = $("#DataLabs").DataTable({
    ajax: route("appointment-services.labs", Reception_Id),
    responsive: true,
    order: [0, "desc"],
    columns: [
      {
        data: null,
        render: function (data) {
          return data.lab ? data.lab.NOMBRE : "";
        },
      },

      {
        data: "observations",
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

var ImgTable = undefined;
$(document).ready(function () {
  ImgTable = $("#DataImgs").DataTable({
    ajax: route("appointment-services.imgs", Reception_Id),
    responsive: true,
    order: [0, "desc"],
    columns: [
      {
        data: null,
        render: function (data) {
          return data.imaging ? data.imaging.NOMBRE : "";
        },
      },

      {
        data: "observations",
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

// Colores fijos por categoría de servicio (no hay campo de color en BD para
// esto, a diferencia de Motivo/Estatus): valores de "tipo" tal como los
// devuelve AppointmentServiceController::getServices() ("Imagen", no
// "Imagenología"). Mismo tratamiento de chip que Motivo/Estatus, vía el
// helper compartido lightenColor() (global.js).
const serviceTypeColors = {
  Laboratorio: "#7C3AED",
  Imagen: "#EA580C",
  Vacuna: "#16A34A",
};

var ServicesTable = undefined;
$(document).ready(function () {
  ServicesTable = $("#DataServices").DataTable({
    ajax: route("appointment-services.registros", Reception_Id),
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
        data: "precio",
        render: function (precio) {
          return precio !== null && precio !== undefined
            ? `$${parseFloat(precio).toFixed(2)}`
            : "";
        },
      },
      {
        data: null,
        orderable: false,
        render: function (data) {
          if (
            data.source !== "appointment_service" ||
            data.es_almacenable !== "S"
          ) {
            return "";
          }

          let html = data.active_voucher_folio
            ? `<span class="badge bg-primary voucher-folio-badge" style="cursor:pointer;"
                        data-voucher-id="${data.active_voucher_id}">
                        ${data.active_voucher_folio}
                    </span>`
            : `<input type="checkbox"
                        class="form-check-input descontar-stock-appointment border border-primary"
                        data-service-id="${data.id}">`;

          if (data.has_cancelled_history) {
            html += `<span class="icon-btn-outline voucher-history-icon text-secondary" style="cursor:pointer; margin-left:6px;"
                        data-source-type="appointment_service" data-source-id="${data.id}" title="Ver historial de vales">
                        <i class="fas fa-history"></i>
                    </span>`;
          }

          return html;
        },
      },
      {
        data: null,
        orderable: false,
        render: function (data) {
          if (!data.id) {
            return "";
          }

          // Las vacunas/desparasitaciones nunca entraron al mecanismo de
          // vales (VoucherProduct no las contempla como sourceable) — su
          // botón de eliminar va siempre habilitado, sin el gating de
          // "vale Surtido" que sí aplica a appointment_service.
          if (data.source === "vaccine_certificate") {
            return `<button type="button" class="btn btn-sm icon-btn-outline text-danger"
                        onclick="removeVaccineCertificate(${data.id})">
                        <i class="fas fa-trash"></i>
                    </button>`;
          }

          if (data.source !== "appointment_service") {
            return "";
          }

          const blocked =
            data.es_almacenable === "S" &&
            data.active_voucher_status === "Surtido";

          if (blocked) {
            return `<button type="button" class="btn btn-sm btn-outline-secondary" disabled
                        title="Este servicio ya tiene un vale Surtido y no puede eliminarse.">
                        <i class="fas fa-trash"></i>
                    </button>`;
          }

          return `<button type="button" class="btn btn-sm icon-btn-outline text-danger"
                        onclick="removeAppointmentService(${data.id})">
                        <i class="fas fa-trash"></i>
                    </button>`;
        },
      },
    ],
  });

  // "Generar Vale" solo tiene sentido si al menos una fila tiene checkbox
  // disponible (mismo criterio que el render de la columna Vale de arriba:
  // fuente appointment_service, es_almacenable "S" y sin vale activo). Se
  // reevalúa en cada draw, no solo al cargar la página, para que reaccione
  // a altas/bajas de servicios y a generar/cancelar vales.
  ServicesTable.on("draw", function () {
    const hasIssuableRow = ServicesTable.rows()
      .data()
      .toArray()
      .some(
        (row) =>
          row.source === "appointment_service" &&
          row.es_almacenable === "S" &&
          !row.active_voucher_folio,
      );

    $("#btnGenerarValeAppointment").toggle(hasIssuableRow);
  });
});

// Las pantallas de cancelar/surtir/rechazar vale (abiertas en pestaña nueva
// desde el modal compartido, ver public/js/vouchers/detail-modal.js) llaman
// window.opener.voucherTableReload() al terminar, para que el checkbox
// vuelva a aparecer si el vale se canceló.
function voucherTableReload() {
  ServicesTable.ajax.reload();
}

async function removeAppointmentService(id) {
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

  const resp = await fetch(route("appointment-services.remove-service", id), {
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

  ServicesTable.ajax.reload();
}

// A diferencia de removeAppointmentService(), este endpoint (vaccine-certificates.destroy,
// ya existente — VoucherCertificateController::destroy()) es un soft-delete
// simple sin captura de motivo, por eso la confirmación no pide texto.
async function removeVaccineCertificate(id) {
  const result = await Swal.fire({
    title: "¿Eliminar este registro?",
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "Eliminar",
    cancelButtonText: "Cancelar",
  });

  if (!result.isConfirmed) return;

  const resp = await fetch(route("vaccine-certificates.destroy", id), {
    method: "DELETE",
    headers: {
      Accept: "application/json",
      "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
  });

  if (!resp.ok) {
    const data = await resp.json().catch(() => null);
    Swal.fire({
      icon: "error",
      title: "No se pudo eliminar",
      text: data?.message || "Ocurrió un problema al eliminar el registro.",
    });
    return;
  }

  ServicesTable.ajax.reload();
}

$(document).on("click", "#btnGenerarValeAppointment", function () {
  let serviceIds = [];

  $(".descontar-stock-appointment:checked").each(function () {
    serviceIds.push($(this).data("service-id"));
  });

  if (serviceIds.length === 0) {
    Swal.fire({
      icon: "warning",
      title: "Selecciona al menos un insumo",
    });
    return;
  }

  Swal.fire({
    title: "¿Generar vale?",
    text: "Se generará el documento para firma del médico",
    icon: "question",
    showCancelButton: true,
    confirmButtonText: "Sí, generar",
    cancelButtonText: "Cancelar",
  }).then((result) => {
    if (!result.isConfirmed) return;

    fetch(route("vouchers.store-products"), {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
      },
      body: JSON.stringify({
        reception_id: Reception_Id,
        source_type: "appointment_service",
        source_ids: serviceIds,
      }),
    })
      .then((res) => res.json())
      .then((resp) => {
        if (resp.success) {
          openVoucherSignModal("generar", resp.voucher_id);
        }
      });
  });
});

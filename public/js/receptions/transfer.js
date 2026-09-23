let currentTransferReceptionId = null;

// Área y Admisión solo aplican cuando el destino es Hospitalización (id=2);
// Motivo de consulta (reason_id) solo cuando el destino es Consulta (id=1) —
// mismos campos obligatorios que ya exige reception.form para cada tipo.
function toggleTransferAdmissionArea() {
  const destinationTypeId = $("#transfer_reception_type_id").val();
  const isHospitalizacion = destinationTypeId === "2";
  const isConsulta = destinationTypeId === "1";
  $("#transfer_adm_group").toggle(isHospitalizacion);
  $("#transfer_area_group").toggle(isHospitalizacion);
  $("#transfer_reason_id_group").toggle(isConsulta);
}

$(document).on(
  "change",
  "#transfer_reception_type_id",
  toggleTransferAdmissionArea,
);

function openTransferModal(receptionId, originTypeId) {
  currentTransferReceptionId = receptionId;

  const $select = $("#transfer_reception_type_id");

  // console.log("RECEPTION ID:", receptionId);
  // console.log("ORIGIN TYPE ID:", originTypeId);
  // console.log("SELECT:", $select.length);

  // Habilitar todas
  $select.find("option").prop("disabled", false);

  console.log(
    "Después de habilitar:",
    $select.find("option").map(function () {
      return {
        value: this.value,
        text: this.text,
        disabled: this.disabled
      };
    }).get()
  );

  // Bloquear únicamente el origen
  $select
    .find(`option[value="${originTypeId}"]`)
    .prop("disabled", true);

  console.log(
    "Después de bloquear origen:",
    $select.find("option").map(function () {
      return {
        value: this.value,
        text: this.text,
        disabled: this.disabled
      };
    }).get()
  );

  const $firstAvailable =
    $select.find("option:not(:disabled)").first();

  if ($firstAvailable.length) {
    $select.val($firstAvailable.val());
  }

  $("#transfer_reason").val("");
  $("#transfer_admission_type_id").val("");
  $("#transfer_area_id").val("");
  $("#transfer_reason_id").val("");
  $("#transfer_submit_btn").prop("disabled", false);

  toggleTransferAdmissionArea();

  bootstrap.Modal.getOrCreateInstance(
    document.getElementById("transferModal")
  ).show();
}

async function submitTransfer() {
  if (!currentTransferReceptionId) return;

  const reasonValue = $("#transfer_reason").val().trim();

  const destinationTypeId = $("#transfer_reception_type_id").val();
  const admissionTypeId = $("#transfer_admission_type_id").val();
  const areaId = $("#transfer_area_id").val();
  const reasonId = $("#transfer_reason_id").val();

  if (destinationTypeId === "2" && (!admissionTypeId || !areaId)) {
    showAlert(
      "Selecciona Área y Admisión para trasladar a Hospitalización.",
      "Falta información",
      "warning",
    );
    return;
  }

  const $btn = $("#transfer_submit_btn");
  $btn.prop("disabled", true);

  const payload = {
    reception_type_id: destinationTypeId,
    reason: reasonValue,
    admission_type_id: admissionTypeId || null,
    area_id: areaId || null,
    reason_id: reasonId || null,
  };

  // Si el traslado se dispara desde el formulario de Consulta
  // (appointment/create.blade.php, #NewAppointment), se manda lo que el
  // médico ya haya escrito ahí para que ReceptionTransferController::store()
  // lo guarde en el Appointment de la recepción de origen en vez de
  // perderlo. En cualquier otra pantalla (Hotel, Red Sheet, etc.) ese
  // formulario no existe en el DOM y el traslado sigue igual que antes.
  if ($("#NewAppointment").length) {
    payload.anamnesis = $("#anamnesis").val();
    payload.exam_details = $("#exam_details").val();
    payload.diagnosis = $("#diagnosis").val();
    payload.observations = $("#observations").val();
  }

  try {
    const response = await fetch(
      route("receptions.episodeTransfer", currentTransferReceptionId),
      {
        method: "POST",
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
          "X-CSRF-TOKEN": $("#csrf").attr("content"),
        },
        body: JSON.stringify(payload),
      },
    );

    const data = await response.json();

    if (!response.ok) {
      showAlert(data.message || "No se pudo trasladar la recepción.", "Error", "error");
      $btn.prop("disabled", false);
      return;
    }

    // El traslado ya guardó el Appointment (si aplicaba) en BD, el borrador
    // local ya no sirve. draft.js no siempre está cargado en la página
    // desde la que se dispara el traslado (Hotel, Red Sheet, etc.), de ahí
    // el typeof-check.
    if (typeof clearAppointmentDraft === "function") {
      clearAppointmentDraft(currentTransferReceptionId);
    }

    if (data.redirect) {
      window.location.href = data.redirect;
      return;
    }

    bootstrap.Modal.getOrCreateInstance(
      document.getElementById("transferModal"),
    ).hide();
  } catch (error) {
    showAlert("Ocurrió un error inesperado al trasladar la recepción.", "Error", "error");
    console.error("Error al trasladar recepción:", error);
    $btn.prop("disabled", false);
  }
}

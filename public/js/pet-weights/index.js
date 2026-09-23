// Registro/historial de peso de mascotas (ver PetWeight). "El peso no se
// edita, se registra una nueva medición": cada envío crea una fila nueva en
// pet_weights, la fecha la asigna el backend (now(), nunca el usuario). Solo
// se carga en pantallas donde components/pet-info.blade.php activa
// $showWeightActions (hoy únicamente appointment/create.blade.php).

let currentWeightPetId = null;
let currentWeightReceptionId = null;

// Recepción para la que ya se registró una medición en esta carga de
// página (ver AppointmentController::store(), que exige un PetWeight con
// reception_id = la consulta actual antes de poder finalizarla).
// InitialWeightRegisteredThisVisit/Reception_Id los define appointment/create.blade.php
// (única pantalla donde este script se carga hoy) para reflejar, ya al
// cargar, si esa medición existe desde antes (p.ej. tras un refresh).
let weightRegisteredForReceptionId =
  typeof InitialWeightRegisteredThisVisit !== "undefined" &&
  InitialWeightRegisteredThisVisit &&
  typeof Reception_Id !== "undefined"
    ? Reception_Id
    : null;

function isWeightRegisteredForReception(receptionId) {
  return weightRegisteredForReceptionId === receptionId;
}

function clearPetWeightErrors() {
  $("#pw_weight").removeClass("is-invalid");
  $("#pw_weight_error").removeClass("d-block").text("");
}

function openRegisterWeightModal(petId, receptionId, currentWeight) {
  currentWeightPetId = petId;
  currentWeightReceptionId = receptionId;

  document.getElementById("petWeightForm").reset();
  clearPetWeightErrors();

  // Prellenar con el peso actual solo para facilitar la captura: no es
  // "editar" ese registro, es únicamente un valor inicial sugerido.
  if (currentWeight) {
    $("#pw_weight").val(currentWeight);
  }

  // Puramente informativo (ver el modal): la fecha real la asigna el
  // backend con now() al guardar, este texto no se envía.
  $("#pw_measured_at_info").text(
    new Date().toLocaleString("es-MX", {
      day: "2-digit",
      month: "2-digit",
      year: "numeric",
      hour: "2-digit",
      minute: "2-digit",
    }),
  );

  bootstrap.Modal.getOrCreateInstance(document.getElementById("registerWeightModal")).show();
}

$(document).on("submit", "#petWeightForm", async function (e) {
  e.preventDefault();
  clearPetWeightErrors();

  const weight = $("#pw_weight").val();

  // Validación de UX (no autoritativa): el backend (PetWeightRequest)
  // sigue siendo quien valida de verdad.
  if (!weight || isNaN(Number(weight)) || Number(weight) <= 0) {
    $("#pw_weight").addClass("is-invalid");
    $("#pw_weight_error").addClass("d-block").text("El peso debe ser mayor a 0.");
    return;
  }

  const $btn = $("#petWeightSubmitBtn").prop("disabled", true);

  try {
    const response = await fetch(route("pet-weights.store"), {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
        "X-Requested-With": "XMLHttpRequest",
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
      },
      body: JSON.stringify({
        pet_id: currentWeightPetId,
        reception_id: currentWeightReceptionId,
        weight: weight,
      }),
    });

    const data = await response.json();

    if (!response.ok) {
      if (data.errors && data.errors.weight) {
        $("#pw_weight").addClass("is-invalid");
        $("#pw_weight_error").addClass("d-block").text(data.errors.weight[0]);
      } else {
        Swal.fire({
          icon: "error",
          title: "No se pudo registrar el peso",
          text: data.message || "Ocurrió un error inesperado.",
        });
      }
      return;
    }

    bootstrap.Modal.getInstance(document.getElementById("registerWeightModal"))?.hide();

    // Actualiza el peso mostrado en pantalla sin recargar la página.
    $("#currentPetWeight")
      .text(Number(data.weight).toFixed(1) + " kg")
      .removeClass("text-muted")
      .addClass("fw-semibold");

    // Marca esta recepción como "peso ya registrado" (obligatorio para
    // poder finalizar la consulta, ver EndAppointment() en appointments/create.js)
    // y oculta el aviso de pendiente.
    weightRegisteredForReceptionId = currentWeightReceptionId;
    $("#weightPendingBadge").hide();

    showAlert("Peso registrado correctamente");
  } catch (error) {
    console.error("Error al registrar el peso:", error);
    Swal.fire({
      icon: "error",
      title: "Error",
      text: "Ocurrió un error inesperado. Vuelve a intentar más tarde.",
    });
  } finally {
    $btn.prop("disabled", false);
  }
});

async function openWeightHistoryModal(petId) {
  const $tbody = $("#weightHistoryTableBody");
  $tbody.html('<tr><td colspan="4" class="text-center text-muted">Cargando...</td></tr>');

  bootstrap.Modal.getOrCreateInstance(document.getElementById("weightHistoryModal")).show();

  try {
    const response = await fetch(route("pet-weights.history", petId), {
      headers: { Accept: "application/json", "X-Requested-With": "XMLHttpRequest" },
    });
    const data = await response.json();

    if (!data.weights || data.weights.length === 0) {
      $tbody.html('<tr><td colspan="4" class="text-center text-muted">Sin mediciones registradas</td></tr>');
      return;
    }

    const rows = data.weights
      .map(function (item) {
        const currentBadge = item.is_current
          ? '<span class="badge bg-primary ms-1">Actual</span>'
          : "";
        const receptionCell = item.reception_label
          ? item.reception_label
          : '<span class="text-muted">No asociada</span>';

        return `<tr>
          <td>${item.measured_at}</td>
          <td>${Number(item.weight).toFixed(1)} kg ${currentBadge}</td>
          <td>${receptionCell}</td>
          <td>${item.registered_by ?? ""}</td>
        </tr>`;
      })
      .join("");

    $tbody.html(rows);
  } catch (error) {
    console.error("Error al cargar el historial de peso:", error);
    $tbody.html('<tr><td colspan="4" class="text-center text-danger">No se pudo cargar el historial</td></tr>');
  }
}

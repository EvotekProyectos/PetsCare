// Modal de creación rápida de anticipo (ver
// reception/partials/advance-payment-modal.blade.php). Reemplaza la
// navegación a advance-payments/add/{reception}: ya no se captura fecha (el
// backend la asigna con now()) ni account_id (AdvancePaymentController::store()
// lo resuelve desde reception_id -> episode -> account). routePrefix se usa
// solo para recargar la tabla correcta al terminar, reutilizando el mismo
// mapa que ya usa accountStatement.js (ACCOUNT_STATEMENT_TABLES) para no
// duplicar esa lógica.
//
// El mismo modal también se reutiliza para "Pago de consulta"
// (openConsultaPaymentModal, ver public/js/receptions/index.js, pestaña
// Hospitalización): currentAdvancePaymentMode distingue ambos casos sin
// duplicar markup ni JS. En modo 'consulta' el concepto queda fijo y el
// monto mínimo es el saldo pendiente de la consulta — ambos, de todas
// formas, se vuelven a exigir en el backend (AdvancePaymentController::
// payConsulta()) como fuente de verdad, esto es solo UX.

let currentAdvancePaymentReceptionId = null;
let currentAdvancePaymentRoutePrefix = null;
let currentAdvancePaymentMode = "advance";
let currentConsultaBalance = 0;
// Consulta + anticipo de servicios hospitalarios (ver
// AccountStatementService::paymentMinimumRequired()) — reemplaza a
// currentConsultaBalance como mínimo real para el modo "consulta"; se
// mantiene currentConsultaBalance por separado solo para el mensaje de
// error de "no cubre la consulta" si el anticipo requerido es 0.
let currentPaymentMinimumRequired = 0;

const ADVANCE_PAYMENT_FIELD_MAP = {
  concept: "ap_concept",
  amount: "ap_amount",
};

function clearAdvancePaymentErrors() {
  Object.values(ADVANCE_PAYMENT_FIELD_MAP).forEach((id) => {
    $("#" + id).removeClass("is-invalid");
    $("#" + id + "_error").removeClass("d-block").text("");
  });
}

function renderAdvancePaymentFieldErrors(errors) {
  Object.keys(errors).forEach((field) => {
    const id = ADVANCE_PAYMENT_FIELD_MAP[field];
    if (!id) return;
    $("#" + id).addClass("is-invalid");
    $("#" + id + "_error").addClass("d-block").text(errors[field][0]);
  });
}

// Restaura el modal a su modo "anticipo libre" normal (concepto editable,
// sin aviso de saldo). openConsultaPaymentModal() lo ajusta después.
function resetAdvancePaymentModalMode() {
  currentAdvancePaymentMode = "advance";
  currentConsultaBalance = 0;
  currentPaymentMinimumRequired = 0;
  $("#advancePaymentModalTitle").text("Crear anticipo");
  $("#ap_concept").prop("readonly", false);
  $("#ap_amount").attr("min", "0.01");
  $("#ap_balance_info").addClass("d-none").text("");
  $("#ap_hospitalizacion_section").addClass("d-none");
  $("#ap_hospitalizacion_services").empty();
}

function openAdvancePaymentModal(receptionId, routePrefix = "hotel") {
  currentAdvancePaymentReceptionId = receptionId;
  currentAdvancePaymentRoutePrefix = routePrefix;

  document.getElementById("advancePaymentForm").reset();
  clearAdvancePaymentErrors();
  resetAdvancePaymentModalMode();

  bootstrap.Modal.getOrCreateInstance(document.getElementById("advancePaymentModal")).show();
}

// Pago combinado de Recepción (Consulta + anticipo de servicios
// hospitalarios, ver AccountStatementService::paymentMinimumRequired()) para
// una hospitalización trasladada o directa. routePrefix fijo en 'redsheet':
// este botón solo vive en la pestaña Hospitalización. El nombre de la
// función se conserva (ya lo usa receptions/index.js) aunque ahora cubre
// también el anticipo hospitalario, para no tocar su único punto de llamada.
async function openConsultaPaymentModal(receptionId) {
  currentAdvancePaymentReceptionId = receptionId;
  currentAdvancePaymentRoutePrefix = "redsheet";

  document.getElementById("advancePaymentForm").reset();
  clearAdvancePaymentErrors();
  resetAdvancePaymentModalMode();

  currentAdvancePaymentMode = "consulta";
  $("#advancePaymentModalTitle").text("Pago de consulta");
  $("#ap_concept").val("Pago de consulta").prop("readonly", true);

  bootstrap.Modal.getOrCreateInstance(document.getElementById("advancePaymentModal")).show();

  try {
    // Se recalcula completo cada vez que se abre el modal -nunca un valor
    // guardado-: si el médico agregó un servicio nuevo en Red Sheet después
    // de la última vez que Recepción lo abrió, esta consulta ya lo incluye
    // (ver ReceptionController::paymentSummary()).
    const response = await fetch(route("receptions.paymentSummary", receptionId), {
      headers: { Accept: "application/json", "X-Requested-With": "XMLHttpRequest" },
    });
    const data = await response.json();

    currentConsultaBalance = Number(data.consulta?.balance) || 0;
    currentPaymentMinimumRequired = Number(data.minimum_required) || 0;

    // Lista única: Consulta + servicios hospitalarios juntos (ver
    // ReceptionController::paymentSummary(), campo "servicios") — la
    // Consulta ya es un renglón real de esta lista, con su propio nombre y
    // precio (item.description, mismo campo que ya devuelve
    // OrdenVentaService::previsualizar()), no un mensaje aparte.
    const servicios = data.servicios ?? [];
    const serviciosTotal = Number(data.servicios_total) || 0;
    const anticipoRequerido = Number(data.hospitalizacion?.anticipo_requerido) || 0;

    if (servicios.length > 0) {
      const $list = $("#ap_hospitalizacion_services").empty();
      servicios.forEach(function (item) {
        // item.description ya es el nombre real del servicio (mismo campo
        // que devuelve OrdenVentaService::previsualizar()) — para los
        // renglones de Consulta es el servicio real de la consulta
        // (ej. "Consulta General"), no una etiqueta genérica inventada.
        const nombre = item.description ?? "Servicio";

        $list.append(
          `<li class="d-flex justify-content-between">
            <span>${nombre}${item.quantity > 1 ? " x" + item.quantity : ""}</span>
            <span>$${Number(item.total || 0).toFixed(2)}</span>
          </li>`,
        );
      });
      $("#ap_hospitalizacion_total").text("$" + serviciosTotal.toFixed(2));
      $("#ap_hospitalizacion_anticipo").text("$" + anticipoRequerido.toFixed(2));
      $("#ap_hospitalizacion_section").removeClass("d-none");
    }

    $("#ap_amount").attr("min", currentPaymentMinimumRequired > 0 ? currentPaymentMinimumRequired.toFixed(2) : "0.01");
    $("#ap_balance_info")
      .removeClass("d-none")
      .text("Monto mínimo a pagar: $" + currentPaymentMinimumRequired.toFixed(2)
        );
  } catch (error) {
    console.error("Error al obtener el resumen de pago:", error);
  }
}

$(document).on("submit", "#advancePaymentForm", async function (e) {
  e.preventDefault();
  clearAdvancePaymentErrors();

  const concept = $("#ap_concept").val()?.trim() ?? "";
  const amount = $("#ap_amount").val();
  const isConsultaPayment = currentAdvancePaymentMode === "consulta";

  // Validación de UX (no autoritativa): evita el viaje al servidor para los
  // casos obvios, pero el backend sigue siendo quien valida de verdad.
  let hasClientError = false;
  if (!concept) {
    $("#ap_concept").addClass("is-invalid");
    $("#ap_concept_error").addClass("d-block").text("El concepto es obligatorio.");
    hasClientError = true;
  }
  if (!amount || isNaN(Number(amount)) || Number(amount) <= 0) {
    $("#ap_amount").addClass("is-invalid");
    $("#ap_amount_error").addClass("d-block").text("Ingresa un monto válido.");
    hasClientError = true;
  } else if (isConsultaPayment && Number(amount) < currentPaymentMinimumRequired) {
    $("#ap_amount").addClass("is-invalid");
    $("#ap_amount_error")
      .addClass("d-block")
      .text("El monto debe cubrir el mínimo requerido ($" + currentPaymentMinimumRequired.toFixed(2) + ").");
    hasClientError = true;
  }
  if (hasClientError) return;

  const result = await Swal.fire({
    title: isConsultaPayment ? "¿Registrar pago de consulta?" : "¿Registrar anticipo?",
    text: `Se registrará un pago de $${Number(amount).toFixed(2)}.`,
    icon: "question",
    showCancelButton: true,
    confirmButtonText: "Sí, registrar",
    cancelButtonText: "Cancelar",
  });

  if (!result.isConfirmed) return;

  const $btn = $("#advancePaymentSubmitBtn").prop("disabled", true);

  try {
    // payHospitalizacion cubre Consulta + anticipo hospitalario en un solo
    // envío (ver AdvancePaymentController::payHospitalizacion()): con la
    // regla de anticipo aún sin definir se comporta igual que payConsulta()
    // (crea un único registro "Pago de consulta"), pero queda listo para
    // cuando esa regla exista, sin tocar este archivo de nuevo.
    const url = isConsultaPayment
      ? route("advance-payments.payHospitalizacion", currentAdvancePaymentReceptionId)
      : route("advance-payments.store");

    const response = await fetch(url, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
        "X-Requested-With": "XMLHttpRequest",
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
      },
      body: JSON.stringify({
        reception_id: currentAdvancePaymentReceptionId,
        concept: concept,
        amount: amount,
      }),
    });

    const data = await response.json();

    if (!response.ok) {
      if (data.errors) {
        renderAdvancePaymentFieldErrors(data.errors);
      } else {
        Swal.fire({
          icon: "error",
          title: isConsultaPayment ? "No se pudo registrar el pago" : "No se pudo registrar el anticipo",
          text: data.message || "Ocurrió un error inesperado.",
        });
      }
      return;
    }

    bootstrap.Modal.getInstance(document.getElementById("advancePaymentModal"))?.hide();

    const tbl = typeof ACCOUNT_STATEMENT_TABLES !== "undefined"
      ? ACCOUNT_STATEMENT_TABLES[currentAdvancePaymentRoutePrefix]?.()
      : null;
    if (tbl) {
      tbl.ajax.reload(null, false);
    }

    showAlert(isConsultaPayment ? "Pago de consulta registrado correctamente" : "Anticipo registrado correctamente");
  } catch (error) {
    console.error("Error al registrar el pago:", error);
    Swal.fire({
      icon: "error",
      title: "Error",
      text: "Ocurrió un error inesperado. Vuelve a intentar más tarde.",
    });
  } finally {
    $btn.prop("disabled", false);
  }
});

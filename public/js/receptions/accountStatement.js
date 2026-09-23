let currentAccountStatementReceptionId = null;
let currentAccountStatementRoutePrefix = null;
// Última respuesta de {routePrefix}.account-statement cargada en el modal —
// permite que el botón de WhatsApp (y cualquier otro consumidor) use el
// mismo teléfono/datos que el usuario está viendo, sin una petición aparte.
let currentAccountStatementData = null;

// Cada tab de reception/index.blade.php tiene su propio prefijo de rutas
// (appointment.*, redsheet.*, grooming.*, hotel.*, cremation.*) y su propia
// variable global de DataTable (declaradas en index.js) para recargar al cerrar.
const ACCOUNT_STATEMENT_TABLES = {
  appointment: () => (typeof table !== "undefined" ? table : null),
  redsheet: () => (typeof table2 !== "undefined" ? table2 : null),
  grooming: () => (typeof table3 !== "undefined" ? table3 : null),
  hotel: () => (typeof table4 !== "undefined" ? table4 : null),
  cremation: () => (typeof table5 !== "undefined" ? table5 : null),
};

function formatCurrency(value) {
  return "$" + Number(value ?? 0).toFixed(2);
}

const ACCOUNT_STATUS_LABELS = {
  OPEN: "Abierta",
  CLOSED: "Cerrada",
  PAID: "Pagada",
  CANCELLED: "Cancelada",
};

const SALES_ORDER_STATUS_LABELS = {
  GENERATED: "Generada",
  CANCELLED: "Cancelada",
};

function formatDateShort(value) {
  if (!value) return "—";
  const date = new Date(value);
  return isNaN(date) ? value : date.toLocaleDateString("es-MX");
}

// Puramente informativo: no se resta del Total (eso depende de que el
// anticipo esté "pagado" -status=1-, flujo que todavía no existe).
function renderAdvancePayments(advancePayments, total) {
  const $section = $("#as_advance_payments_section");

  if (!advancePayments || advancePayments.length === 0) {
    $section.hide();
    return;
  }

  const rows = advancePayments
    .map(
      (ap) => `
        <tr>
          <td>${ap.reference ?? "—"}</td>
          <td>${ap.concept ?? "—"}</td>
          <td>${formatDateShort(ap.date)}</td>
          <td class="text-end">${formatCurrency(ap.amount)}</td>
        </tr>`,
    )
    .join("");

  $("#as_advance_payments_body").html(rows);
  $("#as_advance_payments_total").text(formatCurrency(total));
  $section.show();
}

// Si la cuenta ya no está abierta, el botón "Cerrar cuenta" se oculta (no solo
// se deshabilita) y en su lugar se muestra el folio/estatus de la ODV y el
// estatus de la cuenta.
// estatus de la cuenta.
function renderAccountStatusInfo(data) {
  const $btn = $("#as_close_account_btn");
  const $info = $("#as_account_info");

  if (data.account_status && data.account_status !== "OPEN") {
    $btn.hide();

    const accountLabel = ACCOUNT_STATUS_LABELS[data.account_status] ?? data.account_status;
    let html = `<span class="fw-bold ms-3">Cuenta:</span> <span class="text-muted">${accountLabel}</span>`;

    if (data.sales_order) {
      // const soLabel = SALES_ORDER_STATUS_LABELS[data.sales_order.status] ?? data.sales_order.status;
      html += ` <span class="fw-bold ms-3">Folio:</span> <span class="text-primary">${data.sales_order.folio ?? "s/folio"}</span>`;
    }

    $info.html(html).show();
  } else {
    $btn.show().prop("disabled", !data.can_close);
    $info.hide().empty();
  }
}

async function openAccountStatementModal(receptionId, routePrefix = "appointment") {
  currentAccountStatementReceptionId = receptionId;
  currentAccountStatementRoutePrefix = routePrefix;
  currentAccountStatementData = null;

  const $container = $("#as_items_container");
  $container.html('<div class="text-center text-muted py-3">Cargando...</div>');
  $("#as_total").text(formatCurrency(0));
  $("#as_pet_name").text("—");
  $("#as_family_name").text("");
  $("#as_close_account_btn").show().prop("disabled", true);
  $("#as_account_info").hide().empty();
  $("#as_close_warning").hide();
  $("#as_advance_payments_section").hide();
  $("#as_advance_payments_body").empty();

  bootstrap.Modal.getOrCreateInstance(
    document.getElementById("accountStatementModal"),
  ).show();

  await reloadAccountStatementModal();
}

/**
 * Carga (o vuelve a cargar) el estado de cuenta de la recepción/routePrefix
 * actuales y repinta todo el contenido del modal. Extraído de
 * openAccountStatementModal() para poder reutilizarlo tal cual después de
 * cerrar la cuenta (closeAccountFromModal()) sin duplicar el fetch/render:
 * misma fuente de información para el modal, el "Mostrar PDF" y WhatsApp.
 */
async function reloadAccountStatementModal() {
  const $container = $("#as_items_container");

  try {
    const response = await fetch(
      route(`${currentAccountStatementRoutePrefix}.account-statement`, currentAccountStatementReceptionId),
      {
        headers: {
          Accept: "application/json",
          "X-Requested-With": "XMLHttpRequest",
        },
      },
    );

    if (!response.ok) {
      throw new Error("No se pudo cargar el estado de cuenta.");
    }

    const data = await response.json();
    currentAccountStatementData = data;

    $("#as_pet_name").text(data.reception.pet_name ?? "—");
    $("#as_family_name").text(data.reception.family_name ?? "—");

    if (!data.groups || data.groups.length === 0) {
      $container.html('<div class="text-center text-muted py-3">Sin servicios registrados</div>');
    } else {
      const showSectionHeader = data.groups.length > 1;

      $container.html(
        data.groups
          .map((group) => {
            const header = showSectionHeader
              ? `<h6 class="fw-bold mt-2 mb-1">${group.reception_type ?? "Otro"}</h6>`
              : "";

            const rows = group.items.length
              ? group.items
                  .map(
                    (item) => `
                      <tr>
                        <td>${item.description ?? "N/A"}</td>
                        <td class="text-center">${item.quantity}</td>
                        <td class="text-end">${formatCurrency(item.unit_price)}</td>
                        <td class="text-end">${formatCurrency(item.total)}</td>
                      </tr>`,
                  )
                  .join("")
              : '<tr><td colspan="4" class="text-center text-muted">Sin servicios</td></tr>';

            return `
              ${header}
              <div class="table-responsive mb-2">
                <table class="table table-sm table-striped mb-0">
                  <thead class="table-primary">
                    <tr>
                      <th>Servicio</th>
                      <th class="text-center">Cantidad</th>
                      <th class="text-end">Precio unitario</th>
                      <th class="text-end">Total</th>
                    </tr>
                  </thead>
                  <tbody>${rows}</tbody>
                </table>
              </div>`;
          })
          .join(""),
      );
    }

    $("#as_total").text(formatCurrency(data.total));
    renderAdvancePayments(data.advance_payments ?? [], data.advance_payments_total);
    renderAccountStatusInfo(data);
    $("#as_close_warning").text(data.close_requirement ?? "").toggle(!data.can_close && data.account_status === "OPEN");

    return data;
  } catch (error) {
    $container.html('<div class="text-center text-danger py-3">Error al cargar el estado de cuenta</div>');
    console.error("Error al cargar estado de cuenta:", error);
    return null;
  }
}

/**
 * Mismo mecanismo que sendDocumentWhatsApp() (modal de Documentos, ver
 * receptions/index.js): enlace wa.me con el PDF ya generado, sin backend
 * nuevo. El teléfono se pasa explícito (currentAccountStatementData, el
 * mismo objeto que ya pinta este modal) para no depender de la variable
 * global currentFamilyPhone del otro modal, que podría corresponder a una
 * recepción/familia distinta.
 */
function sendAccountStatementWhatsApp() {
  if (!currentAccountStatementReceptionId || !currentAccountStatementRoutePrefix) {
    return;
  }

  const phone = currentAccountStatementData?.reception?.family_phone;

  if (!phone) {
    Swal.fire({
      icon: "warning",
      title: "Sin teléfono",
      text: "Esta familia no tiene un número de teléfono registrado.",
    });
    return;
  }

  const $btn = $("#as_whatsapp_btn");
  if ($btn.prop("disabled")) {
    return;
  }

  const originalHtml = $btn.html();
  $btn.prop("disabled", true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Enviando...');

  // El mismo PDF que ya usa "Mostrar PDF": nunca un documento distinto al
  // que el usuario está viendo en el modal.
  const pdfUrl = route(`${currentAccountStatementRoutePrefix}.account-statement.pdf`, currentAccountStatementReceptionId);

  sendDocumentWhatsApp(pdfUrl, "Estado de cuenta", phone).finally(function () {
    $btn.prop("disabled", false).html(originalHtml);
  });
}

function showAccountStatementPdf() {
  if (!currentAccountStatementReceptionId || !currentAccountStatementRoutePrefix) return;
  window.open(
    route(`${currentAccountStatementRoutePrefix}.account-statement.pdf`, currentAccountStatementReceptionId),
    "_blank",
  );
}

async function closeAccountFromModal() {
  if (!currentAccountStatementReceptionId || !currentAccountStatementRoutePrefix) return;

  const result = await Swal.fire({
    title: "¿Cerrar cuenta?",
    text: "Se generará la ODV en Microsip y la cuenta quedará cerrada. Esta acción no se puede deshacer.",
    icon: "question",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Sí, cerrar cuenta",
    cancelButtonText: "Cancelar",
  });

  if (!result.isConfirmed) return;

  const $btn = $("#as_close_account_btn");
  $btn.prop("disabled", true);

    Swal.fire({
    title: "Generando folio...",
    text: "Estamos creando la ODV, por favor espera.",
    allowOutsideClick: false,
    allowEscapeKey: false,
    showConfirmButton: false,
    didOpen: () => {
      Swal.showLoading();
    },
  });

  try {
    const response = await fetch(
      route(`${currentAccountStatementRoutePrefix}.account-statement.close`, currentAccountStatementReceptionId),
      {
        method: "POST",
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
          "X-CSRF-TOKEN": $("#csrf").attr("content"),
        },
      },
    );

    const data = await response.json();

    if (!response.ok) {
      Swal.fire({ icon: "error", title: "Error", text: data.message || "No se pudo cerrar la cuenta." });
      $btn.prop("disabled", false);
      return;
    }

    // El modal Estado de Cuenta NO se cierra: el folio (AccountStatementService::
    // close(), ['folio' => ...], nunca calculado aquí) se muestra en el
    // SweetAlert de éxito y además queda visible en el propio modal, no solo
    // en el SweetAlert.
    await Swal.fire({
      icon: "success",
      title: "Cuenta cerrada correctamente",
      text: "El folio del pago es: " + (data.folio ?? "—"),
      confirmButtonText: "Aceptar",
    });

    // Misma fuente/render que la carga inicial (reloadAccountStatementModal()):
    // ahora la cuenta ya está CLOSED, así que renderAccountStatusInfo() -sin
    // cambios- ya sabe mostrar "Folio: X" dentro del modal con estos mismos
    // datos, sin necesitar un elemento/markup nuevo para el folio.
    await reloadAccountStatementModal();

    const tbl = ACCOUNT_STATEMENT_TABLES[currentAccountStatementRoutePrefix]?.();
    if (tbl) {
      tbl.ajax.reload(null, false);
    }
  } catch (error) {
    showAlert("Ocurrió un error inesperado al cerrar la cuenta.", "Error", "error");
    console.error("Error al cerrar cuenta:", error);
    $btn.prop("disabled", false);
  }
}

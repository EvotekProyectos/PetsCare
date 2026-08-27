// Modal compartido de detalle/historial de vale — usado por Consulta y
// Hospitalización (ver voucher/partials/detail-modal.blade.php). El botón
// "Cancelar vale" no reimplementa la cancelación: cierra este modal y abre
// el modal único de firma (ver public/js/vouchers/sign-modal.js) en modo
// "cancelar", que ya sabe hablar con vouchers.cancelFormat/cancel.

function voucherStatusColor(status) {
  const colores = {
    Creado: "secondary",
    Pendiente: "warning",
    Surtido: "success",
    Rechazado: "danger",
    Cancelado: "dark",
  };
  return colores[status] ?? "secondary";
}

function voucherReasonHtml(voucher) {
  if (voucher.status === "Cancelado" && voucher.cancellation_reason) {
    return `<div><strong>Motivo de cancelación:</strong> ${voucher.cancellation_reason}</div>`;
  }
  if (voucher.status === "Rechazado" && voucher.rejection_reason) {
    return `<div><strong>Motivo de rechazo:</strong> ${voucher.rejection_reason}</div>`;
  }
  return "";
}

function resetVoucherDetailModal() {
  $("#voucherDetailContent").hide();
  $("#voucherDetailCancelBtn").hide();
  $("#voucherDetailSingle").hide();
  $("#voucherHistoryList").hide().empty();
  $("#voucherDetailLoader").show();

  bootstrap.Modal.getOrCreateInstance(
    document.getElementById("voucherDetailModal"),
  ).show();
}

// Detalle de UN vale por su id (ej. clic en el badge de folio activo).
function openVoucherDetailModal(voucherId) {
  if (!voucherId) return;

  resetVoucherDetailModal();
  $("#voucherDetailModalTitle").text("Detalle del vale");

  fetch(route("vouchers.show", voucherId), {
    headers: { Accept: "application/json" },
  })
    .then((res) => res.json())
    .then((voucher) => {
      $("#voucherDetailFolio").text(voucher.folio);
      $("#voucherDetailStatus")
        .attr("class", `badge bg-${voucherStatusColor(voucher.status)}`)
        .text(voucher.status ?? "Sin estatus");

      const $products = $("#voucherDetailProducts").empty();
      (voucher.voucher_products || []).forEach((vp) => {
        $products.append(
          `<li class="list-group-item">${vp.product?.NOMBRE ?? "—"}</li>`,
        );
      });

      $("#voucherDetailExtra").html(voucherReasonHtml(voucher));

      // Mismo criterio que ya usa VoucherController::cancel(): solo se puede
      // cancelar mientras está Pendiente.
      if (voucher.status === "Pendiente") {
        $("#voucherDetailCancelBtn")
          .off("click")
          .on("click", function () {
            bootstrap.Modal.getInstance(document.getElementById("voucherDetailModal"))?.hide();
            openVoucherSignModal("cancelar", voucher.id);
          })
          .show();
      }

      $("#voucherDetailSingle").show();
      $("#voucherDetailLoader").hide();
      $("#voucherDetailContent").show();
    });
}

// Historial completo (activos e históricos) de una fila puntual, ej. clic en
// el ícono de historial de un servicio con un vale cancelado en el pasado.
function openVoucherHistoryModal(sourceType, sourceId) {
  if (!sourceType || !sourceId) return;

  resetVoucherDetailModal();
  $("#voucherDetailModalTitle").text("Historial de vales");

  const url = `${route("vouchers.history")}?source_type=${sourceType}&source_id=${sourceId}`;

  fetch(url, { headers: { Accept: "application/json" } })
    .then((res) => res.json())
    .then((vouchers) => {
      const $list = $("#voucherHistoryList").empty();

      if (vouchers.length === 0) {
        $list.append(
          '<p class="text-muted mb-0">No hay vales registrados para este servicio.</p>',
        );
      }

      vouchers.forEach((voucher) => {
        $list.append(`
          <div class="border rounded p-2 mb-2">
            <div class="d-flex justify-content-between align-items-center">
              <span class="fw-bold">${voucher.folio}</span>
              <span class="badge bg-${voucherStatusColor(voucher.status)}">${voucher.status ?? "Sin estatus"}</span>
            </div>
            <div class="small text-muted mt-1">${voucherReasonHtml(voucher)}</div>
          </div>
        `);
      });

      $("#voucherHistoryList").show();
      $("#voucherDetailLoader").hide();
      $("#voucherDetailContent").show();
    });
}

$(document).on("click", ".voucher-folio-badge", function () {
  openVoucherDetailModal($(this).data("voucher-id"));
});

$(document).on("click", ".voucher-history-icon", function () {
  openVoucherHistoryModal($(this).data("source-type"), $(this).data("source-id"));
});

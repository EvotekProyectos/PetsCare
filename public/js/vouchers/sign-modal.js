// Modal único de firma de vale (generar/cancelar/surtir/rechazar), usado por
// Consulta, Hospitalización y la pantalla global de Vales (ver
// voucher/partials/sign-modal.blade.php). Reemplaza las 4 pantallas
// completas que antes se abrían en pestaña nueva; el orden firma-primero,
// PDF-después lo sigue decidiendo el backend (VoucherController), este
// modal solo evita la navegación.

const VOUCHER_SIGN_ACTIONS = {
  generar: {
    title: "Firmar vale",
    confirmLabel: "Firmar y generar",
    getUrl: (id) => route("vouchers.format", id),
    postUrl: (id) => route("vouchers.generate", id),
    editableQuantity: true,
    showObservaciones: false,
    observacionesLabel: null,
    observacionesRequired: false,
    confirmQuestion: {
      title: "¿Generar vale?",
      text: "Se registrará el vale con los insumos solicitados.",
      icon: "question",
      confirmButtonText: "Sí, generar",
    },
    successTitle: "Vale generado",
    successText: "El vale fue generado correctamente.",
  },
  cancelar: {
    title: "Cancelar vale",
    confirmLabel: "Confirmar cancelación",
    getUrl: (id) => route("vouchers.cancelFormat", id),
    postUrl: (id) => route("vouchers.cancel", id),
    editableQuantity: false,
    showObservaciones: true,
    observacionesLabel: "Motivo de cancelación",
    observacionesRequired: true,
    confirmQuestion: {
      title: "¿Cancelar vale?",
      text: "Esta acción no se puede deshacer.",
      icon: "warning",
      confirmButtonText: "Sí, cancelar",
      cancelButtonText: "Volver",
    },
    successTitle: "Vale cancelado",
    successText: "El vale fue cancelado correctamente.",
  },
  surtir: {
    title: "Surtir vale",
    confirmLabel: "Confirmar surtido",
    getUrl: (id) => route("vouchers.issueFormat", id),
    postUrl: (id) => route("vouchers.issue", id),
    editableQuantity: false,
    showObservaciones: true,
    observacionesLabel: "Observaciones del almacén",
    observacionesRequired: false,
    confirmQuestion: {
      title: "¿Confirmar surtido?",
      text: "Se registrará la entrega de los insumos solicitados.",
      icon: "question",
      confirmButtonText: "Sí, surtir",
    },
    successTitle: "Vale surtido",
    successText: "El vale fue surtido correctamente.",
  },
  rechazar: {
    title: "Rechazar vale",
    confirmLabel: "Confirmar rechazo",
    getUrl: (id) => route("vouchers.rejectFormat", id),
    postUrl: (id) => route("vouchers.reject", id),
    editableQuantity: false,
    showObservaciones: true,
    observacionesLabel: "Motivo de rechazo",
    observacionesRequired: true,
    confirmQuestion: {
      title: "¿Rechazar vale?",
      text: "Esta acción no se puede deshacer.",
      icon: "warning",
      confirmButtonText: "Sí, rechazar",
      cancelButtonText: "Volver",
      confirmButtonColor: "#dc3545",
    },
    successTitle: "Vale rechazado",
    successText: "El vale fue rechazado correctamente.",
  },
};

let voucherSignCurrentAction = null;
let voucherSignCurrentId = null;

// Mismo mecanismo de captura de firma que ya usan vouchers/format.js,
// vouchers/cancel.js, budgets/pdf.js, etc.: trazo en <canvas> por mouse/touch,
// toDataURL('image/png') al enviar. Se inicializa una sola vez (el modal es
// un único elemento reutilizado para las 4 acciones).
function initVoucherSignCanvas() {
  const canvas = document.getElementById("voucherSignCanvas");
  if (!canvas || canvas.dataset.bound === "1") return;
  canvas.dataset.bound = "1";

  const ctx = canvas.getContext("2d");
  let dibujando = false;
  let m;

  const oMousePos = (elmnt, e) => {
    const client = elmnt.getBoundingClientRect();
    e = e.touches ? e.touches[0] : e;
    return {
      x: Math.round(e.clientX - client.left),
      y: Math.round(e.clientY - client.top),
    };
  };

  const onStart = function (e) {
    m = oMousePos(this, e);
    ctx.beginPath();
    dibujando = true;
  };

  const onMove = function (e) {
    if (dibujando) {
      ctx.moveTo(m.x, m.y);
      m = oMousePos(this, e);
      ctx.lineTo(m.x, m.y);
      ctx.stroke();
    }
  };

  const onEnd = function () {
    dibujando = false;
  };

  canvas.onmousedown = onStart;
  canvas.ontouchstart = onStart;
  canvas.onmousemove = onMove;
  canvas.ontouchmove = onMove;
  canvas.onmouseup = onEnd;
  canvas.onmouseout = onEnd;
  canvas.ontouchend = onEnd;

  $("#voucherSignClearBtn").on("click", clearVoucherSignCanvas);
}

function clearVoucherSignCanvas() {
  const canvas = document.getElementById("voucherSignCanvas");
  const ctx = canvas.getContext("2d");
  ctx.fillStyle = "white";
  ctx.fillRect(0, 0, canvas.width, canvas.height);
}

function isVoucherSignCanvasEmpty() {
  const canvas = document.getElementById("voucherSignCanvas");
  const ctx = canvas.getContext("2d");
  const pixels = ctx.getImageData(0, 0, canvas.width, canvas.height).data;

  for (let i = 0; i < pixels.length; i += 4) {
    if (
      pixels[i] !== 255 ||
      pixels[i + 1] !== 255 ||
      pixels[i + 2] !== 255 ||
      pixels[i + 3] !== 255
    ) {
      return false;
    }
  }
  return true;
}

function voucherStatusBadgeColor(status) {
  const colores = {
    Creado: "secondary",
    Pendiente: "warning",
    Surtido: "success",
    Rechazado: "danger",
    Cancelado: "dark",
  };
  return colores[status] ?? "secondary";
}

function voucherSignPatientLine(voucher) {
  const parts = [];
  if (voucher.reception?.type) parts.push(`Servicio: ${voucher.reception.type}`);
  if (voucher.vet?.name) parts.push(`Médico solicitante: MVZ. ${voucher.vet.name}`);
  return parts.join(" · ");
}

async function openVoucherSignModal(action, voucherId) {
  const config = VOUCHER_SIGN_ACTIONS[action];
  if (!config || !voucherId) return;

  voucherSignCurrentAction = action;
  voucherSignCurrentId = voucherId;

  $("#voucherSignModalTitle").text(config.title);
  $("#voucherSignConfirmBtn").text(config.confirmLabel).prop("disabled", false);
  $("#voucherSignContent").hide();
  $("#voucherSignLoader").show();
  $("#voucherSignObservaciones").val("");

  bootstrap.Modal.getOrCreateInstance(document.getElementById("voucherSignModal")).show();

  try {
    const res = await fetch(config.getUrl(voucherId), {
      headers: { Accept: "application/json" },
    });
    const voucher = await res.json();

    if (!res.ok) {
      throw new Error(voucher.message || "No se pudo cargar el vale.");
    }

    $("#voucherSignFolio").text(voucher.folio);
    $("#voucherSignStatus")
      .attr("class", `badge bg-${voucherStatusBadgeColor(voucher.status)}`)
      .text(voucher.status ?? "Sin estatus");
    $("#voucherSignPatientInfo").text(voucherSignPatientLine(voucher));

    const $body = $("#voucherSignProductsBody").empty();
    (voucher.products || []).forEach((product) => {
      const cantidadHtml = config.editableQuantity
        ? `<input type="number" name="cantidad[${product.id}]" min="1" step="1"
                class="form-control form-control-sm cantidad-insumo"
                value="${product.requested_quantity ?? ""}" required>`
        : product.requested_quantity == Math.floor(product.requested_quantity)
          ? parseInt(product.requested_quantity, 10)
          : product.requested_quantity;

      $body.append(`<tr><td>${product.name}</td><td>${cantidadHtml}</td></tr>`);
    });

    if (config.showObservaciones) {
      $("#voucherSignObservacionesLabel").text(config.observacionesLabel);
      $("#voucherSignObservacionesWrap").show();
    } else {
      $("#voucherSignObservacionesWrap").hide();
    }

    initVoucherSignCanvas();
    clearVoucherSignCanvas();

    $("#voucherSignLoader").hide();
    $("#voucherSignContent").show();
  } catch (error) {
    console.error(error);
    bootstrap.Modal.getInstance(document.getElementById("voucherSignModal"))?.hide();
    Swal.fire({
      icon: "error",
      title: "No se pudo abrir el vale",
      text: error.message || "Ocurrió un error inesperado.",
    });
  }
}

$("#voucherSignConfirmBtn").on("click", async function () {
  const config = VOUCHER_SIGN_ACTIONS[voucherSignCurrentAction];
  if (!config || !voucherSignCurrentId) return;

  if (config.editableQuantity) {
    let cantidadesValidas = true;
    $("#voucherSignProductsBody .cantidad-insumo").each(function () {
      if ($(this).val() === "" || $(this).val() <= 0) {
        cantidadesValidas = false;
      }
    });

    if (!cantidadesValidas) {
      Swal.fire({
        icon: "warning",
        title: "Cantidad faltante",
        text: "Debe ingresar la cantidad de todos los insumos.",
      });
      return;
    }
  }

  const observaciones = $("#voucherSignObservaciones").val()?.trim() ?? "";
  if (config.showObservaciones && config.observacionesRequired && observaciones === "") {
    Swal.fire({
      icon: "warning",
      title: "Dato requerido",
      text: `Debe ingresar el ${config.observacionesLabel.toLowerCase()}.`,
    });
    return;
  }

  if (isVoucherSignCanvasEmpty()) {
    Swal.fire({
      icon: "error",
      title: "Firma requerida",
      text: "Se necesita la firma para continuar.",
    });
    return;
  }

  const result = await Swal.fire({
    title: config.confirmQuestion.title,
    text: config.confirmQuestion.text,
    icon: config.confirmQuestion.icon,
    showCancelButton: true,
    confirmButtonText: config.confirmQuestion.confirmButtonText,
    cancelButtonText: config.confirmQuestion.cancelButtonText || "Cancelar",
    confirmButtonColor: config.confirmQuestion.confirmButtonColor,
  });

  if (!result.isConfirmed) return;

  const $confirmBtn = $("#voucherSignConfirmBtn").prop("disabled", true);

  Swal.fire({
    title: "Procesando...",
    text: "Por favor espera.",
    allowOutsideClick: false,
    allowEscapeKey: false,
    didOpen: () => Swal.showLoading(),
  });

  const canvas = document.getElementById("voucherSignCanvas");
  const formData = new FormData();
  formData.append("signature", canvas.toDataURL("image/png"));

  if (config.editableQuantity) {
    $("#voucherSignProductsBody .cantidad-insumo").each(function () {
      const match = $(this).attr("name").match(/cantidad\[(\d+)\]/);
      if (match) formData.append(`cantidad[${match[1]}]`, $(this).val());
    });
  }

  if (config.showObservaciones) {
    formData.append("observaciones", observaciones);
  }

  try {
    const res = await fetch(config.postUrl(voucherSignCurrentId), {
      method: "POST",
      headers: { "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content") },
      body: formData,
    });
    const resp = await res.json();

    if (!res.ok || !resp.success) {
      throw new Error(resp.message || "No se pudo completar la acción.");
    }

    Swal.close();
    bootstrap.Modal.getInstance(document.getElementById("voucherSignModal"))?.hide();

    if (typeof voucherTableReload === "function") {
      voucherTableReload();
    }

    Swal.fire({
      icon: "success",
      title: config.successTitle,
      text: config.successText,
      showCancelButton: true,
      confirmButtonText: "Ver PDF",
      cancelButtonText: "Cerrar",
    }).then((result) => {
      if (result.isConfirmed) {
        window.open(resp.pdf_url, "_blank");
      }
    });
  } catch (error) {
    console.error(error);
    Swal.fire({
      icon: "error",
      title: "Error",
      text: error.message || "No se pudo completar la acción.",
    });
  } finally {
    $confirmBtn.prop("disabled", false);
  }
});

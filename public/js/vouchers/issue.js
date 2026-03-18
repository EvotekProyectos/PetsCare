const colorFondo = "white";
let dibujando;

$("canvas").each(function () {
  let m;
  const ctx = this.getContext("2d");
  const canvas = this;

  const oMousePos = (elmnt, e) => {
    let Client = elmnt.getBoundingClientRect();
    e = e.touches ? e.touches[0] : e;
    return {
      x: Math.round(e.clientX - Client.left),
      y: Math.round(e.clientY - Client.top),
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

  const clear = () => {
    ctx.fillStyle = colorFondo;
    ctx.fillRect(0, 0, canvas.width, canvas.height);
  };

  this.onmousedown = onStart;
  this.ontouchstart = onStart;
  this.onmousemove = onMove;
  this.ontouchmove = onMove;
  this.onmouseup = onEnd;
  this.onmouseout = onEnd;
  this.ontouchend = onEnd;

  $(".btnLimpiar[data-target=" + this.id + "]").on("click", clear);

  clear();
});

$("#formVale").on("submit", function (e) {
  e.preventDefault();

  // Validar firma
  const canvas = document.getElementById("firmaSurtido");
  const signatureData = canvas.toDataURL("image/png");
  const ctx = canvas.getContext("2d");
  const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
  const pixels = imageData.data;

  let isSignatureEmpty = true;

  for (let i = 0; i < pixels.length; i += 4) {
    if (
      pixels[i] !== 255 ||
      pixels[i + 1] !== 255 ||
      pixels[i + 2] !== 255 ||
      pixels[i + 3] !== 255
    ) {
      isSignatureEmpty = false;
      break;
    }
  }

  if (isSignatureEmpty) {
    Swal.fire({
      icon: "error",
      title: "Firma requerida",
      text: "Se necesita la firma para confirmar el surtido.",
    });
    return;
  }

  Swal.fire({
    title: "¿Confirmar surtido?",
    text: "Se registrará la entrega de los insumos solicitados.",
    icon: "question",
    showCancelButton: true,
    confirmButtonText: "Sí, surtir",
    cancelButtonText: "Cancelar",
  }).then((result) => {
    if (!result.isConfirmed) return;

    Swal.fire({
      title: "Procesando surtido...",
      text: "Por favor espere.",
      allowOutsideClick: false,
      allowEscapeKey: false,
      didOpen: () => {
        Swal.showLoading();
      },
    });

    const formData = new FormData(document.getElementById("formVale"));
    formData.append("signature", signatureData);

    fetch(ISSUE_VOUCHER_URL, {
      method: "POST",
      headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
      },
      body: formData,
    })
      .then((res) => res.json())
      .then((resp) => {
        if (resp.success) {
          Swal.fire({
            icon: "success",
            title: "Vale surtido",
            text: "El vale fue surtido correctamente.",
          }).then(() => {
            window.open(resp.pdf_url, "_blank");

            if (window.opener && !window.opener.closed) {
              window.opener.voucherTableReload();
            }
            window.close();
          });
        } else {
          Swal.fire({
            icon: "error",
            title: "Error",
            text: resp.message ?? "No se pudo surtir el vale.",
          });
        }
      })
      .catch((error) => {
        console.error(error);
        Swal.fire({
          icon: "error",
          title: "Error",
          text: "No se pudo surtir el vale.",
        });
      });
  });
});

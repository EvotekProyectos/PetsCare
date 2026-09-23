const colorFondo = "white";
let dibujando;

$("canvas").each(function(index) {
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
    
    const onStart = function(e) {
        m = oMousePos(this, e);
        ctx.beginPath();
    
        dibujando = true;
    };
    
    const onMove = function(e) {
        if (dibujando) {
            ctx.moveTo(m.x, m.y);
            m = oMousePos(this, e);
            ctx.lineTo(m.x, m.y);
            ctx.stroke();
        }
    };
    
    const onEnd = function(e) {
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

$("canvas2").each(function(index) {
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
    
    const onStart = function(e) {
        m = oMousePos(this, e);
        ctx.beginPath();
    
        dibujando = true;
    };
    
    const onMove = function(e) {
        if (dibujando) {
            ctx.moveTo(m.x, m.y);
            m = oMousePos(this, e);
            ctx.lineTo(m.x, m.y);
            ctx.stroke();
        }
    };
    
    const onEnd = function(e) {
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

/**
 * true si el canvas sigue completamente en blanco (nadie dibujó encima) —
 * mismo criterio de escaneo de píxeles que ya usan auth_surgery.js/
 * alta_voluntaria.js para su única firma; aquí se aplica a las 2 firmas
 * (médico y propietario) de este documento.
 */
function isCanvasEmpty(canvas) {
    const ctx = canvas.getContext("2d");
    const { data } = ctx.getImageData(0, 0, canvas.width, canvas.height);

    for (let i = 0; i < data.length; i += 4) {
        if (data[i] !== 255 || data[i + 1] !== 255 || data[i + 2] !== 255 || data[i + 3] !== 255) {
            return false;
        }
    }

    return true;
}

$("form").on("submit", function (e) {
    e.preventDefault();

    const $btn = $(this).find(".btnEnviar");

    // Defensa extra contra doble envío: si ya hay una petición en curso
    // (botón deshabilitado más abajo), ignora cualquier submit adicional.
    if ($btn.prop("disabled")) {
        return;
    }

    // No se genera el PDF sin AMBAS firmas (médico y propietario) — la
    // validación real/definitiva es la del backend (ver
    // BudgetController::budgetpdf()); esto solo evita el viaje al servidor
    // cuando ya es obvio que falta alguna.
    if (isCanvasEmpty(canvas) || isCanvasEmpty(canvas2)) {
        Swal.fire({
            icon: "warning",
            title: "Firmas requeridas",
            text: "Se necesitan la firma del médico y la del propietario para generar el presupuesto.",
        });
        return;
    }

    const formData = new FormData(this);
    formData.append("signature", canvas.toDataURL("image/png"));
    formData.append("signature2", canvas2.toDataURL("image/png"));

    // Mismo patrón que hospital_auth.js/auth_surgery.js: botón deshabilitado
    // con spinner (.btn-spinner, de documento-base.css) + SweetAlert de
    // carga bloqueante mientras se procesa la firma.
    const originalBtnHtml = $btn.html();
    $btn.prop("disabled", true).html('<span class="btn-spinner"></span>Firmando...');

    function resetSubmitButton() {
        $btn.prop("disabled", false).html(originalBtnHtml);
    }

    Swal.fire({
        title: "Firmando presupuesto...",
        allowOutsideClick: false,
        allowEscapeKey: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
        },
    });

    $.ajax({
        url: route('budget.pdf', BUDGET_ID),
        type: "post",
        headers: {
            "X-CSRF-Token": $('meta[name="csrf-token"]').attr('content'),
        },
        contentType: false,
        processData: false,
        data: formData,
        success: function (response) {
            Swal.close();

            // Mismo patrón "Ver PDF / Cerrar" que ya usa Autorización
            // Hospitalaria (hospital_auth.js): el PDF solo se abre si el
            // usuario lo pide, y en cualquier caso se continúa el flujo.
            Swal.fire({
                icon: 'question',
                title: '¿Deseas ver el PDF generado?',
                showCancelButton: true,
                confirmButtonText: 'Ver PDF',
                cancelButtonText: 'Cerrar',
            }).then(function (result) {
                if (result.isConfirmed) {
                    window.open(response.url, '_blank');
                }
                window.location.href = route('appointment.consultation', RECEPTION_ID);
            });
        },
        error: function (error) {
            console.error("Error:", error);
            Swal.close();

            // El presupuesto queda tal cual estaba (sin firmar): no se
            // marca nada como firmado a menos que el backend lo confirme.
            const message = error.status === 422 && error.responseJSON?.message
                ? error.responseJSON.message
                : 'Ocurrió un error al procesar la solicitud. Inténtalo de nuevo.';

            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: message,
            });
            resetSubmitButton();
        },
    });
});
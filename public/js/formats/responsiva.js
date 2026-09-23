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

$("form").on("submit", function (e) {
    e.preventDefault();

    const $btn = $(this).find(".btnEnviar");

    // Defensa extra contra doble envío: si ya hay una petición en curso
    // (botón deshabilitado más abajo), ignora cualquier submit adicional
    // -por ejemplo, Enter en un input- mientras se procesa. Mismo criterio
    // que hospital_auth.js/auth_surgery.js.
    if ($btn.prop("disabled")) {
        return;
    }

    const name = $("#name").val();
    const reason = $("#reason").val();
    const canvas = document.getElementById("canvas");
    const ctx = canvas.getContext("2d");
    const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
    const pixels = imageData.data;
    let isSignatureEmpty = true;

    // Comprobar si todos los píxeles son blancos
    for (let i = 0; i < pixels.length; i += 4) {
        if (pixels[i] !== 255 || pixels[i + 1] !== 255 || pixels[i + 2] !== 255 || pixels[i + 3] !== 255) {
            isSignatureEmpty = false;
            break;
        }
    }
    // Validación de UX (no autoritativa): el backend
    // (FormatController::responsivaPdf()) sigue siendo quien valida de
    // verdad que name/reason/signature vengan completos.
    if (!name || !reason || isSignatureEmpty) {
        Swal.fire({
            icon: 'error',
            title: '¡Error!',
            text: 'Completa todos los campos requeridos.',
        });
        return;
    }
    const formData = new FormData(this);
    formData.append("signature", canvas.toDataURL("image/png"));
    formData.append("name", name);
    formData.append("reason", reason);
    // RECEPTION_ID (definida en format/responsivaEG.blade.php) solo existe
    // cuando esta responsiva se abrió desde una Consulta (ver
    // FormatController::responsivaEg()); en el flujo genérico de Formatos
    // queda vacío, sin cambiar nada de lo que ya hacía.
    if (typeof RECEPTION_ID !== "undefined" && RECEPTION_ID) {
        formData.append("reception_id", RECEPTION_ID);
    }

    // Deshabilita el botón y muestra el loading ANTES del $.ajax (todo esto
    // corre síncrono dentro del mismo handler), así que un segundo clic no
    // alcanza a disparar otra petición aunque llegue muy rápido.
    const originalBtnHtml = $btn.html();
    $btn.prop("disabled", true).html('<span class="btn-spinner"></span>Firmando...');

    function resetSubmitButton() {
        $btn.prop("disabled", false).html(originalBtnHtml);
    }

    Swal.fire({
        title: "Firmando responsiva...",
        allowOutsideClick: false,
        allowEscapeKey: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
        },
    });

    // A dónde volver al terminar (Consulta si esta responsiva se abrió desde
    // ahí, o el listado de documentos de siempre). Se resuelve con route()
    // (Ziggy) y, si por lo que sea fallara, cae a la URL cruda de la misma
    // ruta -nunca debe quedarse sin destino-.
    function resolveReturnUrl() {
        const hasReception = typeof RECEPTION_ID !== "undefined" && RECEPTION_ID;

        try {
            return hasReception
                ? route('appointment.consultation', RECEPTION_ID)
                : route('formats.created', PET_ID);
        } catch (e) {
            console.error("No se pudo resolver la ruta de regreso con route(), usando URL cruda:", e);
            return hasReception
                ? '/appointments/consultation/' + RECEPTION_ID
                : '/formats/created/' + PET_ID;
        }
    }

    $.ajax({
        url: route('format-responsiva.pdf', PET_ID),
        type: "post",
        headers: {
            "X-CSRF-Token": $('meta[name="csrf-token"]').attr('content'),
        },
        contentType: false,
        processData: false,
        data: formData,
        success: function (response) {
            Swal.close();

            // En vez de abrir el PDF automáticamente, se deja que el usuario
            // decida: "Ver PDF" lo abre en pestaña nueva, "Cerrar" solo
            // continúa. En ambos casos se regresa al mismo destino de
            // siempre (Consulta si vino de ahí, o el listado de documentos).
            // returnUrl se resuelve aquí (no antes de firmar) para no
            // depender de nada calculado varios pasos atrás.
            Swal.fire({
                icon: "success",
                title: "Responsiva firmada",
                text: "El documento se guardó correctamente.",
                showCancelButton: true,
                confirmButtonText: "Ver PDF",
                cancelButtonText: "Cerrar",
            }).then(function (result) {
                if (result.isConfirmed) {
                    window.open(response.url, "_blank");
                }
                window.location.href = resolveReturnUrl();
            });
        },
        error: function (error) {
            console.error("Error:", error);
            Swal.close();

            // 422: validación del backend (FormatController::responsivaPdf()),
            // con el primer mensaje de error específico si viene disponible;
            // cualquier otro error usa el mensaje genérico de siempre.
            let message = "Ocurrió un error al procesar la solicitud. Inténtalo de nuevo.";
            if (error.status === 422 && error.responseJSON?.errors) {
                const firstError = Object.values(error.responseJSON.errors)[0];
                message = Array.isArray(firstError) ? firstError[0] : message;
            } else if (error.status === 422 && error.responseJSON?.message) {
                message = error.responseJSON.message;
            }

            Swal.fire({
                icon: "error",
                title: "Error",
                text: message,
            });
            resetSubmitButton();
        },
    });
});

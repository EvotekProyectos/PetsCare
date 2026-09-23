const colorFondo = "white";
let dibujando;

$("canvas").each(function(index) {
    let m;
    const ctx = this.getContext("2d");
    const canvas = this;

    const oMousePos = (elmnt, e) => {
        let Client = elmnt.getBoundingClientRect();
        e = e.touches ? e.touches[0] : e;

        // Si el tamaño CSS del canvas (Client.width/height) llega a diferir
        // de su buffer de dibujo (elmnt.width/height, los atributos HTML),
        // el trazo se dibuja en el lugar equivocado. Con scale = 1 (caso
        // actual, sin CSS que fuerce otro ancho) esto no cambia nada.
        const scaleX = elmnt.width / Client.width;
        const scaleY = elmnt.height / Client.height;

        return {
            x: Math.round((e.clientX - Client.left) * scaleX),
            y: Math.round((e.clientY - Client.top) * scaleY),
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
    // -por ejemplo, Enter en un input del form- mientras se procesa.
    if ($btn.prop("disabled")) {
        return;
    }

    const total = $("#total").val();
    const canvas = document.getElementById("canvas");
    const ctx = canvas.getContext("2d");
    const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
    const pixels = imageData.data;
    let isSignatureEmpty = true;

    for (let i = 0; i < pixels.length; i += 4) {
        if (pixels[i] !== 255 || pixels[i + 1] !== 255 || pixels[i + 2] !== 255 || pixels[i + 3] !== 255) {
            isSignatureEmpty = false;
            break;
        }
    }

        if ( !total || isSignatureEmpty) {
        Swal.fire({
            icon: 'error',
            title: '¡Error!',
            text: 'Completa todos los campos requeridos.',
        });
        return;
    }

    const formData = new FormData(this);
    formData.append("signature", canvas.toDataURL("image/png"));
    formData.append("total", total);

    // Deshabilita el botón y muestra el loading ANTES del $.ajax (todo esto
    // corre síncrono dentro del mismo handler), así que un segundo clic no
    // alcanza a disparar otra petición aunque llegue muy rápido.
    const originalBtnHtml = $btn.html();
    $btn.prop("disabled", true).html('<span class="btn-spinner"></span>Firmando...');

    function resetSubmitButton() {
        $btn.prop("disabled", false).html(originalBtnHtml);
    }

    Swal.fire({
        title: "Firmando autorización...",
        allowOutsideClick: false,
        allowEscapeKey: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
        },
    });

$.ajax({
    url: route('hospital.pdf', RECEPTION_ID),
    type: "post",
    headers: {
        "X-CSRF-Token": $('meta[name="csrf-token"]').attr('content'),
    },
    contentType: false,
    processData: false,
    data: formData,
    success: function (response) {
        // Iniciado desde Recepción (ver ReceptionController::documents()/
        // store(), que arman la URL de este formulario con ?from=reception):
        // nunca se auto-abre el PDF ni se decide el siguiente paso por área
        // -eso ya lo resolvió el backend (ver next_format_url,
        // ReceptionDocumentService::nextMissingFormat())-, y jamás termina
        // en Hospitalizaciones (assignment.hospital, panel exclusivo de
        // médico).
        if (FROM_RECEPTION) {
            Swal.close();
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
                    // Si todavía falta la Autorización de Procedimientos
                    // Anestésicos y Quirúrgicos (área Quirúrgicos), se
                    // encadena directo a firmarla; si no, de vuelta a
                    // Recepciones, en la pestaña de Hospital.
                    window.location.href = response.next_format_url
                        || route('receptions.index', { tab: 'hospitalizaciones' });
                });
            return;
        }

        // Flujo nativo de Hospital (no iniciado desde Recepción): sin
        // cambios, mismo comportamiento de siempre.
        window.open(response.url, '_blank'); // Abre el PDF en una nueva pestaña

        // Obtener el área de la recepción antes de redirigir
        $.ajax({
            url: route('receptions.getArea', RECEPTION_ID), // Asegúrate de tener una ruta para esto
            type: "get",
            success: function (res) {
                Swal.close();
                if (res.area_id === 1) {
                    window.location.href = route('surgery.auth', RECEPTION_ID);
                } else {
                    window.location.href = CAME_FROM_TRANSFER
                        ? route('assignment.hospital')
                        : route('receptions.index');
                }
            },
            error: function (error) {
                console.error("Error obteniendo el área:", error);
                Swal.close();
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No se pudo verificar el área de la recepción.',
                });
                resetSubmitButton();
            }
        });
    },
    error: function (error) {
        console.error("Error:", error);
        Swal.close();
        // 422: precondición de negocio no cumplida (ej. falta firmar la
        // Autorización de Procedimientos Anestésicos y Quirúrgicos antes de
        // continuar - ver ReceptionController::hospital_authorizationpdf()),
        // con mensaje específico del servidor. Cualquier otro error usa el
        // mensaje genérico de siempre.
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
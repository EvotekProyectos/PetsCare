const colorFondo = "white";
let dibujando;

// Buscador del procedimiento (ARTICULO_ID de Firebird, puede ser un catálogo
// largo) — select2.min.js ya viene cargado antes que este script (ver
// aut_quirurgica.blade.php). Sin theme "bootstrap-5": este documento no
// carga Bootstrap, usa su propio estilo (documento-base.css + el <style>
// propio del documento), así que aquí va el tema default de select2.
$("#procedure").select2({
    placeholder: "Buscar el procedimiento a realizar",
    width: "resolve",
    allowClear: true,
});

$("canvas").each(function(index) {
    let m;
    const ctx = this.getContext("2d");
    const canvas = this;

    const oMousePos = (elmnt, e) => {
        let Client = elmnt.getBoundingClientRect();
        e = e.touches ? e.touches[0] : e;

        // El canvas ahora usa la clase .signature-box (documento-base.css),
        // que puede encogerse en pantallas angostas. Si el tamaño CSS
        // (Client.width/height) llega a diferir del buffer de dibujo
        // (elmnt.width/height), hay que escalar el trazo o queda mal ubicado.
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

$('#procedure').on('change', function () {
    const price = $(this).find(':selected').data('price');
    document.getElementById('total').value = price ? parseFloat(price).toFixed(2) : '';
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

    const $selected = $("#procedure option:selected");
    const procedure = $selected.data("nombre") || ""; // nombre legible, no el ID
    const total = $("#total").val();
    const include = $("#include").val();
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
    if (!procedure || !total || !include || isSignatureEmpty) {
        Swal.fire({
            icon: 'error',
            title: '¡Error!',
            text: 'Completa todos los campos requeridos.',
        });
        return;
    }

    const formData = new FormData(this);
    formData.append("signature", canvas.toDataURL("image/png"));
    formData.append("procedure", procedure);
    formData.append("total", total);
    formData.append("include", include);

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
        url: route('surgery_authorization.pdf', RECEPTION_ID),
        type: "post",
        headers: {
            "X-CSRF-Token": $('meta[name="csrf-token"]').attr('content'),
        },
        contentType: false,
        processData: false,
        data: formData,
        success: function (response) {
            Swal.close();

            // Iniciada desde Recepción (ver SurgeryController::
            // surgery_authorization(), que recibe ?from=reception desde el
            // modal de Documentos o encadenada desde hospital_auth.js):
            // nunca se auto-abre el PDF, y nunca termina en Hospitalizaciones
            // (assignment.hospital, panel exclusivo de médico) — mismo
            // criterio que hospital_auth.js.
            if (FROM_RECEPTION) {
                
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
                        // Si todavía falta la Autorización de Hospital (esta
                        // quirúrgica se firmó primero desde el modal de
                        // Documentos), se encadena directo a firmarla; si no,
                        // de vuelta a Recepciones, en la pestaña de Hospital.
                        window.location.href = response.next_format_url
                            || route('receptions.index', { tab: 'hospitalizaciones' });
                    });
                return;
            }

            // Flujo nativo (no iniciado desde Recepción): sin cambios.
            window.open(response.url, '_blank');
            window.location.href = CAME_FROM_TRANSFER
                ? route('assignment.hospital')
                : route('receptions.index');
        },
        error: function (error) {
            console.error("Error:", error);
            Swal.close();
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Ocurrió un error al procesar la solicitud. Inténtalo de nuevo.',
            });
            resetSubmitButton();
        },
    });
});
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

$('#procedure').on('change', function () {
    const price = $(this).find(':selected').data('price');
    document.getElementById('total').value = price ? parseFloat(price).toFixed(2) : '';
});

$("form").on("submit", function (e) {
    e.preventDefault();

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
            window.open(response.url, '_blank');
            window.location.href = CAME_FROM_TRANSFER
                ? route('assignment.hospital')
                : route('receptions.index');
        },
        error: function (error) {
            console.error("Error:", error);
            alert("Ocurrió un error al procesar la solicitud. Inténtalo de nuevo.");
        },
    });
});
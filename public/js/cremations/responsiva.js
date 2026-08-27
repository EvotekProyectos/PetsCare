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

$("form").on("submit", async function (e) {
    e.preventDefault();
    const nameFamily = $("#name_family").val();
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

    if (!nameFamily || isSignatureEmpty) {
        Swal.fire({
            icon: 'error',
            title: '¡Error!',
            text: 'Completa todos los campos requeridos.',
            //confirmButtonText: 'Entendido',
        });
        return;
    }
    
    Swal.fire({
        title: 'Procesando...',
        text: 'Por favor espera mientras procesamos la solicitud.',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    try {
        const formData = new FormData(this);
        formData.append("signature", canvas.toDataURL("image/png"));
        formData.append("name_family", nameFamily);

        const response = await $.ajax({
            url: route('responsivaPdf.cremation', RECEPTION_ID),
            type: "post",
            headers: {
                "X-CSRF-Token": $('meta[name="csrf-token"]').attr('content'),
            },
            contentType: false,
            processData: false,
            data: formData,
        });

        Swal.close();

        // El cobro/folio de pago ya no se genera aquí — todo se hace desde
        // el flujo de "Estado de cuenta" en Recepciones (AccountStatementService::close()),
        // igual que ya se hizo para Hospitalización (ver death() en
        // createredsheets.js). Firmar la responsiva solo registra la firma.
        Swal.fire({
            icon: "success",
            title: "Responsiva firmada correctamente",
            timer: 1500,
            showConfirmButton: false,
            timerProgressBar: true,
        }).then(() => {
            window.open(response.url, '_blank');
            window.location.href = route('receptions.index');
        });

    } catch (error) {
        console.error("Error:", error);
        Swal.fire({
            icon: "error",
            title: "Error",
            text: "Ocurrió un error al procesar la solicitud. Inténtalo de nuevo.",
        });
    }
});

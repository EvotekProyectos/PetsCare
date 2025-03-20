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
        window.open(response.url, '_blank'); // Abre el PDF en una nueva pestaña
        
        // Obtener el área de la recepción antes de redirigir
        $.ajax({
            url: route('receptions.getArea', RECEPTION_ID), // Asegúrate de tener una ruta para esto
            type: "get",
            success: function (res) {
                if (res.area_id === 1) {
                    window.location.href = route('surgery.auth', RECEPTION_ID);
                } else {
                    window.location.href = route('receptions.index');
                }
            },
            error: function (error) {
                console.error("Error obteniendo el área:", error);
                alert("No se pudo verificar el área de la recepción.");
            }
        });
    },
    error: function (error) {
        console.error("Error:", error);
        alert("Ocurrió un error al procesar la solicitud. Inténtalo de nuevo.");
    },
});
});
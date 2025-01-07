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

// $("form").on("submit", function (e) {
//     e.preventDefault();

//     const nameFamily = $("#name_family").val();
//     const reason = $("#reason").val();

//     const formData = new FormData(this);
//     formData.append("signature", canvas.toDataURL("image/png"));
//      formData.append("name_family", nameFamily);
//      formData.append("reason", reason);

//     $.ajax({
//         //url: route('altaVoluntaria.pdf' + RECEPTION_ID), 
//         url: route('altaVoluntaria.pdf',  RECEPTION_ID ),
//         type: "post",
//         headers: {
//             "X-CSRF-Token": $('meta[name="csrf-token"]').attr('content'),
//         },
//         contentType: false,
//         processData: false,
//         data: formData,
//         success: function (response) {
//             let url3 = route('redsheet.pay', reception);
//             let pet3 = await fetch(url3, {
//                 method: 'GET',
//                 headers: {
//                     'Content-Type': 'application/json',
//                 },
//             });
//             let resp3 = await pet3.json();
//             Swal.close();
//             Swal.fire({
//                 icon: "success",
//                 title: "El folio para pagar el servicio es " + resp3,
//                 timer: 27000,
//                 showConfirmButton: true
//             }).then(() => {
//                 window.location.href = route('assignment.hospital');
//             });
//             // window.open(response.url, '_blank');
//             // window.location.href = route('hospitalization.historic' , { id: RECEPTION_ID });
//         },
//         error: function (error) {
//             console.error("Error:", error);
//             alert("Ocurrió un error al procesar la solicitud. Inténtalo de nuevo.");
//         },
//     });
// });

$("form").on("submit", async function (e) {
    e.preventDefault();
    Swal.fire({
        title: 'Procesando...',
        text: 'Por favor espera mientras procesamos la solicitud.',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    try {
        const nameFamily = $("#name_family").val();
        const reason = $("#reason").val();

        const formData = new FormData(this);
        formData.append("signature", canvas.toDataURL("image/png"));
        formData.append("name_family", nameFamily);
        formData.append("reason", reason);

        const response = await $.ajax({
            url: route('altaVoluntaria.pdf', RECEPTION_ID),
            type: "post",
            headers: {
                "X-CSRF-Token": $('meta[name="csrf-token"]').attr('content'),
            },
            contentType: false,
            processData: false,
            data: formData,
        });

        const url3 = route('redsheet.pay', RECEPTION_ID);
        const pet3 = await fetch(url3, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
            },
        });

        if (!pet3.ok) {
            throw new Error('Error al obtener el folio de pago.');
        }

        const resp3 = await pet3.json();
        Swal.close();
        
        Swal.fire({
            icon: "success",
            title: "El folio para pagar el servicio es " + resp3,
            timer: 27000,
            showConfirmButton: true,
        }).then(() => {
            window.open(response.url, '_blank');
            window.location.href = route('assignment.hospital');
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

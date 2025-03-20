//Funcion para redirigir a wpp y confirmar envio de video
async function video(phone, id) {
    event.preventDefault();

    const result = await Swal.fire({
        icon: "info",
        title: "Enviar video",
        text: "Será redirigido a WhatsApp para enviar un mensaje.",
        showConfirmButton: true,
        confirmButtonText: "Continuar",
        showCancelButton: true,
        cancelButtonText: "Cancelar",
    });

    if (result.isConfirmed) {
        // Enlace de WhatsApp
        const whatsappURL = `https://wa.me/${phone}?text=Hola,%20necesito%20enviar%20un%20video.`;
        window.open(whatsappURL, '_blank');

        // Espera la confirmación de que el video fue enviado
        const confirmResult = await Swal.fire({
            icon: "question",
            title: "Video enviado",
            text: "¿Deseas confirmar que el video fue enviado?",
            showConfirmButton: true,
            confirmButtonText: "Confirmar",
            showCancelButton: true,
            cancelButtonText: "Cancelar",
        });

        if (confirmResult.isConfirmed) {
            try {

                const url = route('video-send', id);

                const response = await fetch(url, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                    },
                });
            
                if (!response.ok) {
                    throw new Error("Error al registrar el video.");
                }
            
                const data = await response.json();
            

                Swal.fire({
                    icon: "success",
                    title: "¡Confirmado!",
                    text: "El video se ha registrado correctamente.",
                    timer: 2000,
                    showConfirmButton: false
                });

                // Si usas DataTables, recarga la tabla
                $('#tableVideo').DataTable().ajax.reload(); // Opcional

            } catch (error) {
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "No se pudo registrar el video.",
                });
                console.error("Error:", error);
            }
        }
    }
}


window.onload = function () {
    if (Pic_id !== null) {
        let fileRoute = Pic_route.startsWith('/') ? Pic_route.substring(1) : Pic_route;
        $("#preview").attr("src", ruta + fileRoute);
    }
    else {
        $("#preview").attr("src", imgDefault);
    }

}

//Tabla del servicio registrado
var table = undefined;
$(document).ready(function () {
    table = $('#table').DataTable({
        ajax: route('hotel.list', Reception_Id),
        responsive: true,
        order: [0, 'desc'],
        columns: [
            {
                data: null,
                render: function (data) {
                    return data.serv ? data.serv.NOMBRE : '';
                }
            },
            {
                data: 'number_days',
            },
            {
                data: null,
                render: function (data) {
                    return data.cubicle ? data.cubicle.name : '';
                }
            },
        ],
    });
     table.on('draw', function () {
          calculateTotal(table);
      });
});


function calculateTotal(table) {
    let total = 0;

    table.rows({ page: 'all' }).every(function () {
        const data = this.data();
        if (data.servicie && data.servicie.PRECIO && data.number_days) {
            total += parseFloat(data.servicie.PRECIO) * parseInt(data.number_days, 10);
        }
    });

    $('#total-price').text(`Total Final: $${total.toFixed(2)}`);
}


//Funcion para la tabla de los videos enviados
var table = undefined;

$(document).ready(function () {
    table = $('#tableVideo').DataTable({
        ajax: {
            url: route('hotel-video', Reception_Id),
        dataSrc: ''},
        responsive: true,
        order: [0, 'desc'],
        columns: [
            {
                data: 'send_date',
                render: function(data, type, row) {
                    return dayjs(data).format('dddd DD-MM-YYYY'); // Ejemplo: "Martes, 18/02/2025"
                }
            },
            {
                data: 'send_date',
                render: function(data, type, row) {
                    return dayjs(data).format('HH:mm'); // Ejemplo: "15:05"
                }
            },
            {
                data: null,
                render: function (data) {
                    return data.user ? data.user.name : '';
                }
            },
        ],
    });
});


// async function AddExtension(Reception_Id) {
//     event.preventDefault();

//         // Realiza una petición para verificar si ya existe una extensión
//         const response = await fetch(`/api/check-extension/${Reception_Id}`);
//         const data = await response.json();

//         if (data.exists) {
//             Swal.fire({
//                 icon: "warning",
//                 title: "Extensión existente",
//                 text: "Ya existe una extensión activa para esta recepción.",
//                 confirmButtonText: "Aceptar",
//             });
//             return; // Detener la ejecución si ya hay una extensión

//     const result = await Swal.fire({
//         icon: "question",
//         title: "Agregar extensión de servicio",
//         text: "Esto generará un nuevo cargo",
//         showConfirmButton: true,
//         confirmButtonText: "Continuar",
//         showCancelButton: true,
//         cancelButtonText: "Cancelar",
//     });

//     if (result.isConfirmed) {
//         //window.location.href = route;
//         //window.open(route('hotel.extension', Reception_Id));
//         window.location.href = route('hotel.extension', Reception_Id);

//     }

// }

async function AddExtension(Reception_Id) {
    event.preventDefault();

    try {
        let url = route('exist-extension', Reception_Id);

        // Realiza una petición para verificar si ya existe una extensión activa
        const response = await fetch(url);
        const data = await response.json();

        // Si ya existe una extensión, mostrar la alerta y detener la ejecución
        if (data == true) {
          
            await Swal.fire({
                icon: "warning",
                title: "Extensión existente",
                text: "Ya existe una extensión activa para esta recepción",
                confirmButtonText: "Aceptar",
            });
            return; // IMPORTANTE: Detener la ejecución aquí
        }
       
        // Mostrar la alerta de confirmación
        const result = await Swal.fire({
            icon: "question",
            title: "Agregar extensión de servicio",
            text: "Esto generará un nuevo cargo",
            showConfirmButton: true,
            confirmButtonText: "Continuar",
            showCancelButton: true,
            cancelButtonText: "Cancelar",
        });

        if (result.isConfirmed) {
            window.location.href = route('hotel.extension', Reception_Id);
        }

    } catch (error) {
        console.error("Error al verificar la extensión:", error);
        await Swal.fire({
            icon: "error",
            title: "Error",
            text: "Ocurrió un error al verificar la extensión.",
        });
    }
}

// //Select del servicio de pension para que en base a su servicio se muestren los cubiculos
// $(document).ready(function () {
//     $('#service_type_id').select2({
//         placeholder: 'Buscar Servicio',
//         width: 'resolve'
//     });

//     // Deshabilitar el select de cubículos al inicio
//     $('#cubicle_id').prop('disabled', true);

//     // Evento cuando cambia el select de servicio
//     $('#service_type_id').on('change', function () {
//         let articleId = $(this).val(); 
//         let cubicleSelect = $('#cubicle_id');

//         // Si no hay un servicio seleccionado, deshabilitar el select de cubículos
//         if (!articleId) {
//             cubicleSelect.prop('disabled', true).html('<option value="">Selecciona el número de cubículo</option>');
//             return;
//         }

//         let url = route("cubicles.article", articleId)

//         $.ajax({
//             url: url,
//             type: 'GET',
//             dataType: 'json',
//             success: function (response) {
//                 cubicleSelect.prop('disabled', false); // Habilitar select
//                 cubicleSelect.html('<option value="">Selecciona el número de cubículo</option>');

//                 response.forEach(cubicle => {
//                     cubicleSelect.append(`<option value="${cubicle.id}">${cubicle.name}</option>`);
//                 });
//             },
//             error: function () {
//                 cubicleSelect.prop('disabled', true).html('<option value="">No hay cubículos disponibles</option>');
//             }
//         });
//     });
// });

$(document).ready(function () {
$('#service_type_id').on('change', function () {
    let articleId = $(this).val(); 
    let cubicleSelect = $('#cubicle_id');

    // Si no hay un servicio seleccionado, deshabilitar el select de cubículos
    if (!articleId) {
        cubicleSelect.prop('disabled', true).html('<option value="">Selecciona el número de cubículo</option>');
        return;
    }

    // Si hay un servicio seleccionado, habilitar el select de cubículos
    cubicleSelect.prop('disabled', false);

    // Si ya tienes los cubículos cargados por defecto, no necesitas hacer una solicitud AJAX
    // Verifica si ya existe una opción seleccionada
    if (cubicleSelect.find('option').length <= 1) { // Si solo tienes la opción por defecto
        let url = route("cubicles.article", articleId);

        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                cubicleSelect.html('<option value="">Selecciona el número de cubículo</option>'); // Resetear el select
                response.forEach(cubicle => {
                    cubicleSelect.append(`<option value="${cubicle.id}">${cubicle.name}</option>`);
                });
            },
            error: function () {
                cubicleSelect.prop('disabled', true).html('<option value="">No hay cubículos disponibles</option>');
            }
        });
    }
});
});

async function NewExtension(event) {
    event.preventDefault(); // Evita el comportamiento por defecto del formulario

    let url = route('hotels.storeExtension');
    let form = new FormData(document.getElementById("ExtensionService"));

    let response = await fetch(url, {
        method: "POST",
        body: form,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    });

    let resp = await response.json();

    if (response.ok) {
        Swal.fire({
            icon: "success",
            title: "Extensión registrada exitosamente",
            timer: 5000,
            showConfirmButton: true
        });

        // Llamada para actualizar la tabla con el nuevo registro
        actualizarTabla();

    } else {
        Swal.fire({
            icon: "error",
            text: resp.message || "Ocurrió un error al registrar la extensión"
        });
    }
}

// Función para actualizar la tabla con el último registro agregado
function actualizarTabla() {
    $.ajax({
        url: route('list.extension', Reception_Id),
        type: 'GET',
        success: function (response) {
            if (response.length > 0) {
                let ultimoRegistro = response[0]; // Obtener el último registro agregado
                table.row.add(ultimoRegistro).draw(); // Agregar a la tabla
            }
        },
        error: function (error) {
            console.error('Error al obtener los datos:', error);
        }
    });
}


// async function NewExtension(event) {
//     event.preventDefault();

//     let url = route('hotels.storeExtension');
//     let form = new FormData(document.getElementById("ExtensionService"));

//     let response = await fetch(url, {
//         method: "POST",
//         body: form,
//         headers: {
//             'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
//             'Accept': 'application/json'
//         }
//     });

//     let resp = await response.json();

//     if (response.ok) {
//         Swal.fire({
//             icon: "success",
//             title: "Extensión registrada exitosamente",
//             timer: 5000,
//             showConfirmButton: true
//         });

//         // document.getElementById("start_date").value = resp.hotel.start_date;
//         // document.getElementById("end_date").value = resp.hotel.end_date;

//     } else {
//         Swal.fire({
//             icon: "error",
//             text: resp.message || "Ocurrió un error al registrar la extensión"
//         });
//     }
// }

// Actualizar fechas dinámicamente al cambiar el cubículo
document.getElementById("cubicle_id").addEventListener("change", function () {
    let cubicleEndDate = document.getElementById("cubicle_end_date").value;
    let days = parseInt(document.getElementById("number_days").value) || 1;

    if (cubicleEndDate) {
        let startDate = new Date(cubicleEndDate);
        let endDate = new Date(startDate);
        endDate.setDate(startDate.getDate() + days);

        document.getElementById("start_date").value = startDate.toISOString().split("T")[0];
        document.getElementById("end_date").value = endDate.toISOString().split("T")[0];
    }
});


//Registro de servicio en la tabla y bd y que solo se pueda registrar un servicio a la vez
async function NewEntry() {
    event.preventDefault();

    let url = route('hotels.store');
    let form = new FormData(document.getElementById("NewService"));

    let pet = await fetch(url, { method: "POST", body: form,  headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        'Accept': 'application/json'
    }
});
    let resp = await pet.json();

    if (pet.ok) {
        Swal.fire({
            icon: "success",
            title: "Se registraron los servicios con exito",
            timer: 7000,
            showConfirmButton: true
        })
        table.ajax.reload();
        $('#service_type_id').val('').trigger('change')
        $('#number_days').val('')
        $('#cubicle_id').val('')
    } else {
        let resp = await pet.json();
        Swal.fire({
            icon: "error",
            body: resp
        })
    }
}

//Tabla del servicio registrado
//Tabla del servicio registrado
// var table = undefined;
// $(document).ready(function () {
//     table = $('#table').DataTable({
//         ajax: route('list.extension', Reception_Id),
//         responsive: true,
//         order: [0, 'desc'],
//         columns: [
//             {
//                 data: null,
//                 render: function (data) {
//                     return data.serv ? data.serv.NOMBRE : '';
//                 }
//             },
//             {
//                 data: 'number_days',
//             },
//             {
//                 data: null,
//                 render: function (data) {
//                     return data.cubicle ? data.cubicle.name : '';
//                 }
//             },
//             {
//                 data: null,
//                 render: function (data) {
//                     const precio = parseFloat(data.servicie.PRECIO) || 0;
//                     return precio === null ? '$0' : `$${precio.toFixed(2)}`;
//                 }
//             }
//             ,            
//             {
//                 data: null,
//                 render: function (data) {
//                     return `
//                         <button type="button" class="btn btn-sm text-primary" onclick="showAlertWithCallback(() => deleteServicieHotel (${data.id}, table));">
//                             <i class="fas fa-trash"></i>
//                         </button>`;
//                 }
//             },

//         ],
//     });
//      table.on('draw', function () {
//           calculateTotal(table);
//       });
// });
var table = undefined;
$(document).ready(function () {
    table = $('#table').DataTable({
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
            {
                data: null,
                render: function (data) {
                    const precio = parseFloat(data.servicie?.PRECIO) || 0;
                    return `$${precio.toFixed(2)}`;
                }
            },
            {
                data: null,
                render: function (data) {
                    return `
                        <button type="button" class="btn btn-sm text-primary" onclick="showAlertWithCallback(() => deleteServicieHotel (${data.id}, table));">
                            <i class="fas fa-trash"></i>
                        </button>`;
                }
            },
        ],
    });

    table.on('draw', function () {
        calculateTotal(table);
    });

    // // Evento para agregar nuevo registro al dar clic en el botón
    // $('.btn-agregar-servicio').on('click', function (e) {
    //     e.preventDefault();
    //     agregarNuevoRegistro();
    // });
});

// // Función para agregar el último registro registrado
// function agregarNuevoRegistro() {
//     $.ajax({

//         url: route('list.extension', Reception_Id),
//         type: 'GET',
//         success: function (response) {
//             if (response.length > 0) {
//                 let ultimoRegistro = response[0]; // Obtener el último registro agregado
//                 table.row.add(ultimoRegistro).draw(); // Agregar a la tabla
//             }
//         },
//         error: function (error) {
//             console.error('Error al obtener los datos:', error);
//         }
//     });
// }

//Calcular el total del servicio 
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

//Generar orden de venta y responsiva
 async function generate(event) {
     event.preventDefault();
     Swal.fire({
         title: 'Procesando...',
         text: 'Por favor espera mientras procesamos la solicitud.',
         allowOutsideClick: false,
         didOpen: () => {
             Swal.showLoading();
         }
     });

     try {
         let url3 = route('hotel.pay', Reception_Id);
         let pet3 = await fetch(url3, {
             method: 'GET',
             headers: {
                 'Content-Type': 'application/json',
             },
         });
         let resp3 = await pet3.json();

         Swal.close();

         Swal.fire({
             icon: "success",
             title: "El folio para pagar el servicio es " + resp3,
             timer: 27000,
             showConfirmButton: true
         }).then(() => {
             window.location.href = route('hotels.index');
             //window.location.href = route('hotel.format', Reception_Id);
         });

     } catch (error) {
         console.error("Error:", error);
         Swal.fire({
             icon: "error",
             title: "Error inesperado",
             text: "Por favor, intenta nuevamente."
         });
     } finally {
         Swal.close();
     }
 }




        
//Select del servicio de pension para que en base a su servicio se muestren los cubiculos
$(document).ready(function () {
    $('#service_type_id').select2({
        placeholder: 'Buscar Servicio',
        width: 'resolve'
    });

    // Deshabilitar el select de cubículos al inicio
    $('#cubicle_id').prop('disabled', true);

    // Evento cuando cambia el select de servicio
    $('#service_type_id').on('change', function () {
        let articleId = $(this).val(); 
        let cubicleSelect = $('#cubicle_id');

        // Si no hay un servicio seleccionado, deshabilitar el select de cubículos
        if (!articleId) {
            cubicleSelect.prop('disabled', true).html('<option value="">Selecciona el número de cubículo</option>');
            return;
        }

        let url = route("cubicles.article", articleId)

        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                cubicleSelect.prop('disabled', false); // Habilitar select
                cubicleSelect.html('<option value="">Selecciona el número de cubículo</option>');

                response.forEach(cubicle => {
                    cubicleSelect.append(`<option value="${cubicle.id}">${cubicle.name}</option>`);
                });
            },
            error: function () {
                cubicleSelect.prop('disabled', true).html('<option value="">No hay cubículos disponibles</option>');
            }
        });
    });
});


//Registro de servicio en la tabla y bd y que solo se pueda registrar un servicio a la vez
async function NewEntry() {
    event.preventDefault();

    // Verificar si ya hay un registro en la tabla
    if (table.data().count() > 0) {
        Swal.fire({
            icon: "warning",
            title: "Solo se permite un servicio a la vez",
            text: "Elimina el servicio actual para agregar uno nuevo.",
            timer: 5000,
            showConfirmButton: true
        });
        return;
    }

    let url = route('hotels.store');
    
    let form = new FormData(document.getElementById("NewService"));

    let pet = await fetch(url, { 
        method: "POST",
        body: form,  
        headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        'Accept': 'application/json'
    }
});

let resp;
try {
    resp = await pet.json();
} catch (error) {
    console.error("Error al leer JSON:", error);
    Swal.fire({
        icon: "error",
        text: "Error al procesar la respuesta del servidor."
    });
    return;
}

if (pet.ok) {
    document.getElementById('number_days').value = resp.number_days ?? 0;

    Swal.fire({
        icon: "success",
        title: "Se registraron los servicios con éxito",
        timer: 7000,
        showConfirmButton: true
    });

    table.ajax.reload();
    $('#service_type_id').val('').trigger('change');
    $('#number_days').val('');
    $('#cubicle_id').val('');
} else {
    console.error("Error en la solicitud:", resp);
    Swal.fire({
        icon: "error",
        text: resp.message || "Ocurrió un error al registrar el servicio"
    });
}
}
//     let resp = await pet.json();
    
//     if (pet.ok) {

//         //document.getElementById('number_days').value = resp.number_days;
//         document.getElementById('number_days').value = resp.number_days ?? 0;

//         Swal.fire({
//             icon: "success",
//             title: "Se registraron los servicios con exito",
//             timer: 7000,
//             showConfirmButton: true
//         });

//         table.ajax.reload();
//         $('#service_type_id').val('').trigger('change')
//         $('#number_days').val('')
//         $('#cubicle_id').val('')
//     } else {
//         let errorResp = await pet.json();
//         console.log(errorResp);
//         Swal.fire({
//             icon: "error",
//             text: errorResp.message || "Ocurrió un error al registrar el servicio"
//         });
//     }
// }

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
            {
                data: null,
                render: function (data) {
                    const precio = parseFloat(data.servicie.PRECIO) || 0;
                    return precio === null ? '$0' : `$${precio.toFixed(2)}`;
                }
            }
            ,            
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
});

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
             window.location.href = route('hotel.format', Reception_Id);
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




        
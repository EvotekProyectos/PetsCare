
$(document).ready(function () {
    $('#service_type_id').select2({
        placeholder: 'Buscar Servicio',
        width: 'resolve'
    });
});

async function NewEntry() {
    event.preventDefault();
    let url = route('hotels.store');
    let form = new FormData(document.getElementById("NewService"));
    let pet = await fetch(url, { method: "POST", body: form });
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

// function calculateTotal(table) {
//     let total = 0;

//     table.rows({ page: 'all' }).every(function () {
//         const data = this.data();
//         if (data.servicie && data.servicie.PRECIO) {
//             total += parseFloat(data.servicie.PRECIO);
//         }
//     });

//     $('#total-price').text(`Total Final: $${total.toFixed(2)}`);
// }

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



    // document.getElementById('cubicle_id').addEventListener('change', function () {
    //     const cubicleId = this.value;

    //     if (cubicleId) {
    //         fetch("{{ route('cubicles.updateState') }}", {
    //             method: "POST",
    //             headers: {
    //                 "Content-Type": "application/json",
    //                 "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
    //             },
    //             body: JSON.stringify({ cubicle_id: cubicleId }),
    //         })
    //         .then(response => response.json())
    //         .then(data => {
    //             if (data.success) {
    //                 alert(data.message);
    //             } else {
    //                 alert('Error: ' + data.message);
    //             }
    //         })
    //         .catch(error => console.error('Error:', error));
    //     }
    // });





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



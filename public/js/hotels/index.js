const Statuses = {
    "Entregado": "#56BF2F",
    "En espera de realizar": "#FF2C2C",
    "Listo para entregar": "#FFBE33"
};

var table = undefined;
$(document).ready(function () {
    table = $('#table').DataTable({
        ajax: { url: route('hotel.all'),
            dataSrc: function (json) {return json || [];}
        },
        responsive: true,
        order: [[0, 'desc']],
        columns: [
            {
                data: null,
                render: function (data) {
                    return data.reception && data.reception.entry_date ? data.reception.entry_date : 'Sin fecha';
                }
            },
            {
                data: null,
                render: function (data) {
                    return data.reception ? data.reception.pet.name : '';
                }
            },
            {
                data: null,
                render: function (data) {
                    return data.reception ? data.reception.num : '';
                }
            },
            {
                data: null,
                render: function (data) {
                    return data.serv ? data.serv.NOMBRE : '';
                }
            },
            {
                data: null,
                render: function (data) {
                    return data.cubicle ? data.cubicle.name : '';
                }
            },
            {
                data: 'video',
                render: function(data) {
                    if (data === 0) {
                        return '<span style="background-color: orange; padding: 5px; color: white; border-radius: 5px;">Pendiente</span>';
                    } else {
                        return '<span style="background-color: green; padding: 5px; color: white; border-radius: 5px;">Enviado</span>';
                    }
                }
            }
            ,            
            {
                data: null,
                render: function (data) {
                    return data.reception && data.reception.exit_date ? data.reception.exit_date : 'Sin fecha';
                }
            }, 
            {
                    data: null,
                     render: function (data) {
                         return `
                                <a type="button" href="${route('pension.inf', data.reception.id)}" class="btn btn-sm text-primary">
                                 <span class="mdi--eye"></span>
                             </a>
                            
                              <a type="button" class="btn btn-sm text-primary" onclick="exit(${ data.id})">
                                  <span class="mingcute--exit-line"></span>
                             </a>
                            `;
                            
         
            },
        //     {
        //         data: null,
        //          render: function (data) {
        //              return `
        //                     <a type="button" class="btn btn-sm text-primary" onclick="Attend(${data.id}, '${data.status}', '${data.updated_at}');">
        //                     <span class="icon-park-twotone--correct"></span> 
        //                  </a>
        //                     <a type="button" href="${route('pension.inf', data.reception.id)}" class="btn btn-sm text-primary">
        //                      <span class="mdi--eye"></span>
        //                  </a>
                         
        //                   <a type="button" class="btn btn-sm text-primary" onclick="video(${data.reception.family.phone}');">
        //                   <span class="ri--whatsapp-fill"></span>
        //                 </a>`;
     
        // },
        }
        ]
    });
});

async function Attend(phone) {
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
        // enlace de WhatsApp
        const whatsappURL = `https://wa.me/${phone}?text=Hola,%20necesito%20enviar%20un%20video.`;
        
        window.open(whatsappURL, '_blank');
    }
}

async function exit(id) {
   
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        const result = await Swal.fire({
            title: 'Confirmar salida de la mascota',
            text: '¿Desea confirmar que la mascota ya será entregada?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí, continuar',
            cancelButtonText: 'No, regresar',
        });

        if(!result.isConfirmed) return;

        let formData = new FormData();
        formData.append('hotel', id);
        formData.append('_token', csrfToken);

        let url = route('hotel-exit', id);

        let response = await fetch(url, {
            method: "POST",
            body: formData,
        
        });

        let data=await response.json();
}

// // async function updateStatus(ID, newStatus) {
// //     try {
// //         const url = route('cremation.updateStatus', ID);

// //         const response = await fetch(url, {
// //             method: 'POST',
// //             headers: {
// //                 'Content-Type': 'application/json',
// //                 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
// //             },
// //             body: JSON.stringify({ status: newStatus })
// //         });

// //         if (response.ok) {
// //             console.log("Estado actualizado correctamente");
// //         } else {
// //             console.error("Error al actualizar el estado");
// //         }
// //     } catch (error) {
// //         console.error("Error de conexión", error);
// //     }
// // }


// // async function Attend( ID, Status, fecha) {
// //     event.preventDefault();

// //     if (Status === "Entregado") {
// //         Swal.fire({
// //             icon: "info",
// //             title: "Proceso finalizado",
// //             text: "La cremación ya fue entregada el día:"+ fecha,
// //             showConfirmButton: true,
// //         });
// //         return;
// //     }

// //     if (Status === "En espera de realizar") {
// //         const result = await Swal.fire({
// //             title: '¿La cremación ha sido completada?',
// //             text: "Confirma que la cremación está lista para entregar.",
// //             icon: 'question',
// //             showCancelButton: true,
// //             confirmButtonColor: '#3085d6',
// //             cancelButtonColor: '#d33',
// //             confirmButtonText: 'Sí, completar cremación.',
// //             cancelButtonText: 'No, sigue en espera.'
// //         });

        
// //         if (result.isConfirmed) {
// //             await updateStatus(ID, "Listo para entregar");

// //             Swal.fire({
// //                 icon: "success",
// //                 title: "Cremación completada",
// //                 text: "El estado se ha actualizado.",
// //                 showConfirmButton: true
// //             }).then(() => {
// //                 location.reload();
// //             });

// //         }
// //     }

// //     if (Status === "Listo para entregar") {
// //         const result = await Swal.fire({
// //             title: '¿Ya fue entregado al propietario?',
// //             text: "Confirma que la cremación ha sido entregada.",
// //             icon: 'question',
// //             showCancelButton: true,
// //             confirmButtonColor: '#3085d6',
// //             cancelButtonColor: '#d33',
// //             confirmButtonText: 'Sí, entregar.',
// //             cancelButtonText: 'No, sigue en espera.'
// //         });

// //         if (result.isConfirmed) {
// //             await updateStatus(ID, "Entregado");

// //             Swal.fire({
// //                 icon: "success",
// //                 title: "Cremación entregada",
// //                 text: "El estado se ha actualizado.",
// //                 showConfirmButton: true
// //             }).then(() => {
// //                 location.reload();
// //             });

// //         }
// //     }
// // }

var table = undefined;
$(document).ready(function () {
    table = $('#table').DataTable({
        ajax: route('schedules-surgery.list'),
        responsive: true,
        order: [0, 'desc'],
        columns: [
            {
                data: null,
                render: function (data) {
                    return data.family ? data.family.name : '';
                }
            },
            {
                data: null,
                render: function (data) {
                    return data.pet ? data.pet.name : '';
                }
            },
            {
                data: null,
                render: function (data) {
                    return data.producto ? data.producto.NOMBRE : '';
                }
            },
            {
                data: 'day',
            },
            {
                data: 'hour',
            },
            {
                data: null,
                render: function (data) {
                    return data.user ? data.user.name : '';
                }
            },
            {
                data: null,
                render: function (data) {
                    // return data.status_surgery ? data.status_surgery.name : '';
                    if(data.status_surgery){
                          return `<span style="background-color: ${data.status_surgery.color}; color: black; padding: 5px; border-radius: 5px;">
                           ${data.status_surgery.name}
                                </span>`;
                    }
                }
            },


            {
                data: null,
                render: function (data) {
                    return `
                        <a type="button" class="btn btn-sm text-primary" onclick="Attend(${data.id}, ${data.status_surgery.id});">
                            <span class="mage--hospital-shield-fill"></span>
                        </a>`;
                    
                }
            },
        ],
    });
}); 

async function updateStatus(ID, currentStatus) {
    const url = route('surgery.status', ID);

        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ status: currentStatus })
        });

        if (response.ok) {
            const data = await response.json();
            console.log("Respuesta del servidor:", data);
        } else {
            console.error("Error en la solicitud:", response.statusText);
        }
}



async function Attend( ID, Status) {
    event.preventDefault();

    if (Status === 3) {
        Swal.fire({
            icon: "success",
            title: "Cirugía completada",
            text: "La cirugía ya fue realizada.",
            showConfirmButton: true,
        });
        return;
    }

    if (Status === 2) {
        const result = await Swal.fire({
            title: '¿La cirugía se completó?',
            text: "Confirma que la cirugía se realizó.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, cirugía completada.',
            cancelButtonText: 'No, regresar.'
        });

        
        if (result.isConfirmed) {
            await updateStatus(ID, Status);

            Swal.fire({
                icon: "success",
                title: "Cirugía terminada.",
                text: "El estado se ha actualizado.",
                showConfirmButton: true
            }).then(() => {
                location.reload();
            });

        }
    }

    if (Status === 1) {
        const result = await Swal.fire({
            title: '¿Realizar la cirugía?',
            text: "Confirma si realizarás el procedimiento quirúrgico.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, comenzar.',
            cancelButtonText: 'No, continuar en espera.'
        });

        if (result.isConfirmed) {
            await updateStatus(ID, Status);

            Swal.fire({
                icon: "success",
                title: "Cirugía en proceso",
                text: "El estado se ha actualizado.",
                showConfirmButton: true
            }).then(() => {
                location.reload();
            });

        }
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar')
    const calendar = new FullCalendar.Calendar(calendarEl, {
        headerToolbar:{
            left: 'prev,next,today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        initialView: 'timeGridWeek',
        timeZone: 'GMT',
        locale: 'es',
        events: route('surgery-schedules.getEvents'), 
        eventClick: function (info) {
            Swal.fire({
                title: 'Detalle de la cirugía',
                html: `
                    <strong>Cirugía:</strong> ${info.event.title}<br>
                    <strong>M.V.Z:</strong> ${info.event.extendedProps.medice || 'Sin veterinario'}<br>
                    <strong>Horario:</strong> ${info.event.start?.toISOString() || 'Sin horario'}<br>
                    <strong>Estado:</strong> ${info.event.extendedProps.description || 'Sin descripción'}`,
                icon: 'info',
                confirmButtonText: 'Cerrar',
            });
        },
        
    });
    calendar.render();
});

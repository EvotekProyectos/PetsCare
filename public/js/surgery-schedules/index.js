$(document).ready(function () {
    $('#family_id').select2({
        placeholder: 'Buscar Familia',
        width: 'resolve'
    });
    $('#pet_id').select2({
        placeholder: 'Buscar Mascota',
        width: 'resolve'
    });
});

let isUpdating = false;

async function getpets(family_id) {
    if (isUpdating) return;
    isUpdating = true;

    let url = route("pets.preview", family_id)
    let peticion = await fetch(url)
    if (peticion.ok) {
        document.getElementById("pet_id").value
        let respuesta = await peticion.json()
        let html = ""
        respuesta.forEach(pet => {
            html += `<option value="${pet.id}">${pet.name} #${pet.number_chip}</option>`;
        });
        document.getElementById("pet_id").innerHTML = html

    }
    isUpdating = false;
}

async function getFamily(pet_id) {
    if (isUpdating) return;
    isUpdating = true;
    let url = route("families.getFamilyByPet", pet_id);
    let peticion = await fetch(url);
    if (peticion.ok) {
        let family = await peticion.json();
        if (family) {
            $('#family_id').val(family.id).trigger('change');
            //  getPets(family.id); 
            isUpdating = false;
        }
    }
    isUpdating = false;
}



var table = undefined;
$(document).ready(function () {
    table = $('#table').DataTable({
        ajax: route('schedules-surgery.list'),
        responsive: true,
        order: [0, 'desc'],
        columns: [
            {
                data: 'number_ticket',
            },
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
                        <a type="button" href="${route('surgery-schedules.edit', data.id)}" class="btn btn-sm text-primary">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button type="button" class="btn btn-sm text-primary" onclick="showAlertWithCallback(() => deletesurgerySchedule(${data.id}, table));">
                            <i class="fas fa-trash"></i>
                        </button>`;
                }
            },
        ],
    });
}); 


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
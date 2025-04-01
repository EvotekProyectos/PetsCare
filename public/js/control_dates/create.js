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
        ajax: route('dates.list'),
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
                    return data.date_type ? data.date_type.name : '';
                }
            },
            {
                data: 'date',
            },
            {
                data: null,
                render: function (data) {
                    return data.status_date ? data.status_date.name : '';
                }
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
                    return `
                        ${data.status_date_id !== 3 ? `
                            <button type="button" class="btn btn-sm text-primary" onclick="confirmed('${data.id}')">
                                <i class="lucide--calendar-check"></i>
                            </button>
                        ` : ''}
                        <button type="button" class="btn btn-sm text-primary" onclick="message('${data.family.phone}')">
                            <i class="ri--whatsapp-fill"></i>
                        </button>
                        ${data.status_date_id === 1 ? `
                            <a type="button" href="${route('control-dates.edit', data.id)}" class="btn btn-sm text-primary">
                                <i class="fas fa-edit"></i>
                            </a>
                        ` : ''}
                    `;
                }
            },            
        ],
    });
}); 


async function message(phone) {
    event.preventDefault();
  
    const result = await Swal.fire({
      icon: "question",
      title: "Confirmar cita",
      text: "Será redirigido a WhatsApp para enviar un mensaje.",
      showConfirmButton: true,
      confirmButtonText: "Continuar",
      showCancelButton: true,
      cancelButtonText: "Cancelar",
    });
  
    if (result.isConfirmed) {
      // enlace de WhatsApp
      const whatsappURL = `https://wa.me/${phone}?text=Hola,%20me%20gustaría%20confirmar%20una%20cita.`;
  
      window.open(whatsappURL, "_blank");
    }
  }

async function confirmed(id) {
    const result = await Swal.fire({
        icon: "question",
        title: "¿Se confirmó la cita?",
        text: "Indica cuál es el estado de la cita.",
        showConfirmButton: true,
        confirmButtonText: "Sí, cita confirmada",
        showCancelButton: true,
        cancelButtonText: "No, reagendar cita",
    });

    if (result.isConfirmed) {
        let url = route('control-dates.updateStatus', id);
        try {
            const response = await fetch(url, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
                },
                body: JSON.stringify({ status_date_id: 3 })
            });

            const data = await response.json();

            if (data.success) {
                let url = route('schedules.date', id)
                fetch(url)
                .then(response => response.json())
                .then(data => {
                    if (data.length > 0) {
              
                        let options = data.map(schedule => 
                            `<option value="${schedule.id}">${schedule.user.name}-${schedule.cover_area.name}</option>`
                        ).join('');
        
                // Mostrar SweetAlert con el select
                Swal.fire({
                    title: 'Selecciona un médico',
                    html: `
                        <select id="scheduleSelect" class="swal2-input">
                            ${options}
                        </select>
                    `,
                    showCancelButton: true,
                    confirmButtonText: 'Aceptar',
                    preConfirm: () => {
                        let selectedSchedule = document.getElementById('scheduleSelect').value;
                        return selectedSchedule;
                    }
                }).then(result => {
                    if (result.isConfirmed) {
                        let selectedScheduleId = result.value;
                    }
                });

            } else {
                Swal.fire({
                    icon: 'warning',
                    title: 'No hay horarios disponibles',
                    text: 'Intenta con otra fecha.'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error al obtener los horarios',
                text: 'Inténtalo de nuevo más tarde.'
            });
        });
            } else {
                Swal.fire("Error", data.message, "error");
            }
        } catch (error) {
            Swal.fire("Error", "Hubo un problema al actualizar el estado de la cita.", "error");
            console.error(error);
        }
    } else {
        // Si se elige reagendar, mostrar input de fecha
        const { value: nuevaFecha } = await Swal.fire({
            title: "Reagendar cita",
            html: `
                <label for="fecha" class="swal2-label">Selecciona la nueva fecha y hora:</label>
                <input type="datetime-local" id="fecha" class="swal2-input">
            `,
            showCancelButton: true,
            confirmButtonText: "Aceptar",
            cancelButtonText: "Cancelar",
            preConfirm: () => {
                const fechaSeleccionada = document.getElementById("fecha").value;
                if (!fechaSeleccionada) {
                    Swal.showValidationMessage("Por favor, selecciona una fecha y hora.");
                }
                return fechaSeleccionada;
            }
        });

        if (nuevaFecha) {
            let url = route('control-dates.updateDate', id);
            // Enviar la nueva fecha al servidor usando AJAX
            try {
                const response = await fetch(url, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
                    },
                    body: JSON.stringify({ date: nuevaFecha, status_date_id: 2 }) // Se cambia el status a 3 cuando se reagenda
                });

                const data = await response.json();

                if (data.success) {
                    Swal.fire("¡Éxito!", "La fecha se han actualizado correctamente.", "success");
                    // window.open(route('dates.calendar'));
                } else {
                    Swal.fire("Error", data.message, "error");
                }
            } catch (error) {
                Swal.fire("Error", "Hubo un problema al actualizar la fecha.", "error");
                console.error(error);
            }
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
        events: route('control-dates.getEvents'),
        eventClick: function (info) {
            Swal.fire({
                title: 'Detalle de la cita',
                html: `
                     ${info.event.title} 
                     <br>
                    <strong>Horario:</strong> ${info.event.start?.toISOString() || 'Sin horario'}<br>
                    `,
                icon: 'info',
                confirmButtonText: 'Cerrar',
            });
        },
        
    });
    calendar.render();
});


var table = undefined;
$(document).ready(function () {
    table = $('#table').DataTable({
        ajax: route('schedules.list'),
        responsive: true,
        order: [0, 'desc'],
        columns: [
            {
                data: 'begin',
            },
            {
                data: 'end',
            },
            {
                data: null,
                render: function (data) {
                    return data.shift ? data.shift.name : '';
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
                    return data.cover_area ? data.cover_area.name : '';
                }
            },
            {
                data: null,
                render: function (data) {
                    return `
                        <a type="button" href="${route('schedules.edit', data.id)}" class="btn btn-sm text-primary">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button type="button" class="btn btn-sm text-primary" onclick="showAlertWithCallback(() => deleteSchedule(${data.id}, table));">
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
      events: route('schedules.getEvents'),
    // events: [
    //     {
    //         title: 'Grooming- Happy',
    //         start: '2025-03-16T09:00:00', // 16 de marzo a las 10 AM
    //         end: '2025-03-16T11:00:00',   // Termina a las 11 AM
    //         description: 'Revisión general',
    //         color: '#007bff' // Color opcional
    //     },
    //     {
    //         title: 'Consulta Preventiva- Galleta',
    //         start: '2025-03-20T09:00:00', // 20 de marzo a las 10 AM
    //         end: '2025-03-20T10:00:00',   // Termina a las 11 AM
    //         description: 'Revisión general',
    //         color: '#007bff' // Color opcional
    //     },
    //     {
    //         title: 'Grooming- Happy',
    //         start: '2025-03-16T09:00:00', // 16 de marzo a las 10 AM
    //         end: '2025-03-16T11:00:00',   // Termina a las 11 AM
    //         description: 'Revisión general',
    //         color: '#007bff' // Color opcional
    //     }
    // ],
      locale: 'es',
    });
    calendar.render()
  })
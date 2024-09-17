

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
      events: '/schedules/get-events',
      locale: 'es',
    });
    calendar.render()
  })
var table = undefined;
$(document).ready(function () {
  table = $("#table").DataTable({
    ajax: {
      url: route("confirmed.individual"),
      dataSrc: "", // Asegúrate de que dataSrc esté vacío ya que el array es directamente la respuesta.
    },
    responsive: true,
    order: [0, "desc"],
    columns: [
      {
        data: null,
        render: function (data) {
          return data.family ? data.family.name : "";
        },
      },
      {
        data: null,
        render: function (data) {
          return data.pet ? data.pet.name : "";
        },
      },
      {
        data: null,
        render: function (data) {
          return data.date_type ? data.date_type.name : "";
        },
      },
      {
        data: "date",
      },
      {
        data: null,
        render: function (data) {
          return data.schedule ? data.schedule.user.name : "";
        },
      },
      {
        data: "status",
        render: function (data) {
          return data === 0 ? "Pendiente" : data === 1 ? "Asistió" : ""; // Devuelve 'No asistió' o 'Asistió'
        },
      },
    ],
  });
});


document.addEventListener("DOMContentLoaded", function () {
  const calendarEl = document.getElementById("calendar");
  const selectFilter = document.getElementById("veterinarioFilter");
  const loggedUser = selectFilter.getAttribute("data-user"); // Obtener usuario autenticado dinámicamente

  let calendar = new FullCalendar.Calendar(calendarEl, {
    headerToolbar: {
      left: "prev,next,today",
      center: "title",
      right: "dayGridMonth,timeGridWeek,timeGridDay",
    },
    initialView: "timeGridWeek",
    timeZone: "GMT",
    locale: "es",
    events: function (fetchInfo, successCallback, failureCallback) {
      fetch(route("control-dates.getEvents"))
        .then((response) => response.json())
        .then((data) => {
          let filterValue = selectFilter.value;

          let filteredEvents = data.filter((event) => {
            if (filterValue === "1") {
              return event.mvz === loggedUser; // Filtra citas del usuario autenticado
            }
            return true; // Muestra todas las citas
          });

          successCallback(filteredEvents);
        })
        .catch((error) => failureCallback(error));
    },
    eventClick: function (info) {
      Swal.fire({
        title: "Detalle de la cita",
        html: `
                   <strong>Cita de tipo:</strong> ${info.event.title}
                     <br><strong>M.V.Z:</strong> ${info.event.extendedProps.mvz}
                     <br><strong>Horario:</strong> ${
                       info.event.start?.toISOString() || "Sin horario"
                     }<br>
                `,
        icon: "info",
        confirmButtonText: "Cerrar",
      });
    },
  });

  calendar.render();

  // Actualizar eventos al cambiar la opción del select
  selectFilter.addEventListener("change", function () {
    calendar.refetchEvents();
  });
});

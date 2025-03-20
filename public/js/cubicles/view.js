//Alerta al darle click al cubiculo con info más detallada
function showCubicleInfo(state, exit, pet, collar) {
  if (state == "Ocupado") {
    Swal.fire({
      title: `Información del Cubículo`,
      html: `
            <p><strong>Estado:</strong> ${state}</p>
              <p><strong>Salida:</strong> ${exit}</p>
              <p><strong>Mascota:</strong> ${pet}</p>
               <p><strong>No.Collar:</strong> ${collar}</p>
        `,
      icon: "info",
      confirmButtonText: "Cerrar",
    });
  } else {
    Swal.fire({
      title: `Información del Cubículo`,
      html: `
           <p><strong>Estado:</strong> ${state}</p>
        `,
      icon: "info",
      confirmButtonText: "Cerrar",
    });
  }
}

//Info que se muestra al pasar el cursor por el icono
function showInfo(event, size, exit) {
  const infoBox = document.getElementById("info-box");

  const offsetX = -300;
  const offsetY = -50;

  infoBox.style.display = "block";
  infoBox.style.left = event.pageX + offsetX + "px";
  infoBox.style.top = event.pageY + offsetY + "px";

 
    infoBox.innerHTML = `
    <br><strong>Medidas:</strong> ${size}`;
    // <br><strong>Salida:</strong> ${exit ? exit : "N/A"}
}

//Ocultar info del cursor
function hideInfo() {
  const infoBox = document.getElementById("info-box");
  infoBox.style.display = "none";
}


var table = undefined;
var calendar = undefined;  // Asegúrate de que el calendario esté accesible desde fuera del evento document ready.

$(document).ready(function () {
  // Inicializa la tabla
  table = $("#table").DataTable({
    ajax: {
      url: route("hotel.unavailable"),
      dataSrc: function (json) {
        return json || [];
      },
    },
    responsive: true,
    order: [0, "desc"],
    columns: [
      {
        data: null,
        render: function (data) {
          return data.reception ? data.reception.pet.name : "";
        },
      },
      {
        data: null,
        render: function (data) {
          return data.serv ? data.serv.NOMBRE : "";
        },
      },
      // {
      //   data: null,
      //   render: function (data) {
      //     return data.cubicle ? data.cubicle.start_date : "";
      //   },
      // },
      // {
      //   data: null,
      //   render: function (data) {
      //     return data.cubicle ? data.cubicle.end_date : "";
      //   },
      // },
      {
        data: null,
        render: function (data) {
            if (data.extension == 0) {
                return data.reception ? data.reception.entry_date : "";
            } else if (data.extension == 1) {
                return data.cubicle ? data.cubicle.start_date : "";
            }
            return ""; 
        }
    },
    {
        data: null,
        render: function (data) {
            if (data.extension == 0) {
                return data.reception ? data.reception.exit_date : "";
            } else if (data.extension == 1) {
                return data.cubicle ? data.cubicle.end_date : "";
            }
            return ""; 
        }
    },
    
    ],
    createdRow: function (row, data) {
      const today = new Date(); // Fecha actual

       let exitDate = null;
    if (data.extension === 0 && data.reception) {
        exitDate = new Date(data.reception.exit_date);
    } else if (data.extension === 1 && data.cubicle) {
        exitDate = new Date(data.cubicle.end_date);
    } 
  
    if (exitDate && exitDate < today) {
      $(row).addClass('table-danger'); // Rojo si exitDate es menor a hoy
    }
  },
    // Este es el evento que se dispara cuando la tabla se actualiza.
    drawCallback: function () {
      if (calendar) {
        calendar.refetchEvents();  // Recarga los eventos en el calendario
      }
    }
  });

  // Inicializa el calendario
  const calendarEl = document.getElementById('calendar');
  calendar = new FullCalendar.Calendar(calendarEl, {
    headerToolbar: {
      left: "prev,next,today",
      center: "title",
      right: "dayGridMonth,timeGridWeek,timeGridDay",
    },
    initialView: "dayGridMonth",
    timeZone: "GMT",
    locale: "es",
    events: route("hotel.getEvents"),  // Obtiene los eventos

    eventClick: function (info) {
      Swal.fire({
        title: "Detalles",
        html: `
          <strong>Servicio:</strong> ${info.event.title}<br>
          <strong>Pet:</strong> ${info.event.extendedProps.pet}<br>`,
        icon: "info",
        confirmButtonText: "Cerrar",
      });
    },
  });
  calendar.render();
});


const Statuses = {
  Entregado: "#56BF2F",
  "En espera de realizar": "#FF2C2C",
  "Listo para entregar": "#FFBE33",
};

var table = undefined;
$(document).ready(function () {
  table = $("#table").DataTable({
    ajax: {
      url: route("hotel.all"),
      dataSrc: function (json) {
        return json || [];
      },
    },
    responsive: true,
    order: [[0, "desc"]],
    columns: [
      {
        data: null,
        render: function (data) {
          return data.reception ? data.reception.num : "";
        },
      },
      {
        data: null,
        render: function (data) {
          return data.reception ? data.reception.pet.name : "";
        },
      },
      {
        data: null,
        render: function (data) {
          return data.reception ? data.reception.pet.raza : "";
        },
      },
      {
        data: null,
        render: function (data) {
          return data.reception ? data.reception.family.name : "";
        },
      },
      {
        data: null,
        render: function (data) {
          return data.serv ? data.serv.NOMBRE : "";
        },
      },
      {
        data: null,
        render: function (data) {
            return `<span style="display: block; text-align: center;">${data.cubicle ? data.cubicle.name : ""}</span>`;
        },
    },     
      {
        data: "videoExists",
        render: function (data) {
          if (data === false) {
            return '<span style="background-color: #ffa24a; padding: 5px; color: white; border-radius: 5px;">Pendiente</span>';
          } else {
            return '<span style="background-color: green; padding: 5px; color: white; border-radius: 5px;">Enviado</span>';
          }
        },
      },
      {
        data: null,
        render: function (data) {
          return data.reception && data.reception.entry_date
            ? data.reception.entry_date
            : "Sin fecha";
        },
      },

      {
        data: null,
        render: function (data) {
          return data.reception && data.reception.exit_date
            ? data.reception.exit_date
            : "Sin fecha";
        },
      },
      {
        data: null,
        render: function (data) {
          let eyeButton = `
            <a type="button" href="${route("hotels.show", data.reception.id)}" 
               class="btn btn-sm text-primary">
                <span class="mdi--eye"></span>
            </a>`;
          
            let exitButton = "";
        
            // Solo mostrar el botón de salida si extension es 0
            if (data.extension === 0) {
                exitButton = `
                    <a type="button" class="btn btn-sm text-primary" onclick="exit(${data.id});">
                        <span class="mingcute--exit-fill"></span>
                    </a>`;
            }

         return eyeButton + exitButton;
        },
      },
    ],
    createdRow: function (row, data) {
      const today = new Date(); // Fecha actual
      const exitDate = new Date(data.reception.exit_date); // Convertir exit_date a fecha
      const ready = new(data.hotel.finish_date);
  
      if (data.extension === 1) {
          $(row).addClass('table-warning');
      } else if (exitDate < today) {
          $(row).addClass('table-danger'); // Rojo si exit_date es menor a hoy
      }
  }
  
  });
});

async function video(phone) {
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

    window.open(whatsappURL, "_blank");
  }
}

async function exit(id) {
  let url=route('hotel-exit', id);

  const csrfToken = document
    .querySelector('meta[name="csrf-token"]')
    .getAttribute("content");

  const result = await Swal.fire({
    title: "Salida de la mascota",
    text: "¿Desea confirmar que la mascota ya será entregada con su familia?",
    icon: "question",
    showCancelButton: true,
    confirmButtonText: "Sí, continuar",
    cancelButtonText: "No, regresar",
  });

  if (!result.isConfirmed) return;


  try {
    const response = await fetch(url, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "X-CSRF-TOKEN": csrfToken,
      },
      body: JSON.stringify({ hotel: id }),
    });

    const data = await response.json();

    if (response.ok) {
      Swal.fire({
        title: "Éxito",
        text: data.message,
        icon: "success",
      }).then(() => {
        location.reload(); // Recargar la página para actualizar la información
      });
    } else {
      Swal.fire({
        title: "Error",
        text: data.message || "Hubo un problema al registrar la salida",
        icon: "error",
      });
    }
  } catch (error) {
    Swal.fire({
      title: "Error",
      text: "Ocurrió un error inesperado. Inténtelo nuevamente.",
      icon: "error",
    });
    console.error("Error:", error);
  }
}
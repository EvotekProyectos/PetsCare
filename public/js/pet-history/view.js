window.onload = function () {
  let fileRoute = Pic_route.startsWith("/")
    ? Pic_route.substring(1)
    : Pic_route;

  if (Pic_id !== null) {
    $("#preview").attr("src", ruta + fileRoute);
  } else {
    $("#preview").attr("src", imgDefault);
  }
};

// Mismos colores por tipo de recepción usados en el resto del sistema para
// pintar el fondo de la columna "Tipo" (ver el patrón de `areas` en
// receptions/index.js: color sólido + lightenColor() para el fondo).
const receptionTypeColors = {
  1: "#0455A0", // Consulta
  2: "#157A3D", // Hospitalización
  3: "#A13A1F", // Grooming
  4: "#2F3F9E", // Hotel
  5: "#7A6A00", // Cremación
};

var table = undefined;
var petAccountsTable = undefined;

$(document).ready(function () {
  table = $("#DataServices").DataTable({
    ajax: {
      url: route("reception.historial", Pet_Id),
      data: function (d) {
        d.type = $("#filterHistorialTipo").val();
        d.vet_id = $("#filterHistorialVet").val();
      },
    },
    responsive: true,
    order: [0, "desc"],
    columns: [
      { data: "entry_date", render: formatDate },

      {
        data: null,
        render: function (data) {
          return data.vet ? data.vet.name : "";
        },
      },

      {
        data: null,
        render: function (data) {
          if (!data.reception_type) {
            return "";
          }
          const color = receptionTypeColors[data.reception_type_id];
          return `<span style="background-color: ${lightenColor(color)}; padding: 5px 10px; color: ${color}; border-radius: 5px; font-weight: 600;">
                ${data.reception_type.name}
              </span>`;
        },
      },
      // {
      //     data: null,
      //     render: function (data) {
      //         return data.reason ? data.reason.name : '';
      //     }
      // },
      {
        data: null,
        render: function (data) {
          return `
                        <a class="btn btn-sm btn-primary"  title="Ver Detalles" href="#" onclick="Details(${data.reception_type_id}, ${data.id});">
                            <span class="mage--hospital-shield-fill"></span>
                        </a>`;
        },
      },
    ],
  });

  $("#filterHistorialTipo, #filterHistorialVet").on("change", function () {
    table.ajax.reload();
  });

  $("#btnClearHistorialFilters").on("click", function () {
    $("#filterHistorialTipo").val("");
    $("#filterHistorialVet").val("");
    table.ajax.reload();
  });

  // Estados de cuenta: mismo endpoint/columnas que account/index.blade.php
  // (ver initAccountsTable() en accounts/table.js), acotado a esta mascota.
  petAccountsTable = initAccountsTable("#petAccountsTable", function (d) {
    d.pet_id = Pet_Id;
    d.status = $("#filterPetAccountEstatus").val();
    d.date_from = $("#filterPetAccountFechaDesde").val();
    d.date_to = $("#filterPetAccountFechaHasta").val();
  });

  $(
    "#filterPetAccountEstatus, #filterPetAccountFechaDesde, #filterPetAccountFechaHasta",
  ).on("change", function () {
    petAccountsTable.ajax.reload(null, false);
  });

  $("#btnClearPetAccountFilters").on("click", function () {
    $("#filterPetAccountEstatus").val("");
    $("#filterPetAccountFechaDesde").val("");
    $("#filterPetAccountFechaHasta").val("");
    petAccountsTable.ajax.reload(null, false);
  });
});

async function Details(Type, ID) {
  event.preventDefault();
  if (Type === 1) {
    window.location.href = route("appointment.show", ID);
  }
  if (Type === 2) {
    window.location.href = route("redsheet.show", ID);
  }
  if (Type === 3) {
    window.location.href = route("grooming.history", ID);
  }
  if (Type === 4) {
    window.location.href = route("hotel.history", ID);
  }
  if (Type === 5) {
    window.location.href = route("cremation.history", ID);
  }
}

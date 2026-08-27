// Inicialización de #DataServices para la vista de solo lectura
// (appointment/show.blade.php). Reutiliza el mismo endpoint que create.js
// (appointment-services.registros), pero con columnas más simples: sin
// checkbox de vale, sin columna de Acciones/Eliminar — nada de eso aplica
// en modo lectura.

// Mismo mapa de colores que appointments/create.js (duplicado a propósito:
// este archivo es independiente y más simple, no una extensión de create.js).
const serviceTypeColors = {
  Laboratorio: "#7C3AED",
  Imagen: "#EA580C",
  Vacuna: "#16A34A",
};

$(document).ready(function () {
  $("#DataServices").DataTable({
    ajax: route("appointment-services.registros", Reception_Id),
    responsive: true,
    order: [[0, "asc"]],
    columns: [
      {
        data: "tipo",
        render: function (tipo) {
          if (!tipo) return "";
          const color = serviceTypeColors[tipo] || "#6c757d";
          return `<span style="color: ${color}; background-color: ${lightenColor(color)}; padding: 5px 10px; border-radius: 5px; font-weight: 600;">
                ${tipo}
              </span>`;
        },
      },
      {
        data: "nombre",
        render: function (nombre) {
          if (!nombre) return "";
          return `<span class="neutral-chip">${nombre}</span>`;
        },
      },
      { data: "observations" },
      { data: "vet" },
      {
        data: "precio",
        render: function (precio) {
          return precio !== null && precio !== undefined
            ? `$${parseFloat(precio).toFixed(2)}`
            : "";
        },
      },
      {
        data: null,
        orderable: false,
        render: function (data) {
          if (!data.active_voucher_folio) {
            return "";
          }
          // Badge no clickable a propósito: esta vista es de solo lectura,
          // no debe ofrecer ninguna acción sobre el vale.
          return `<span class="badge bg-primary">${data.active_voucher_folio}</span>`;
        },
      },
    ],
  });
});

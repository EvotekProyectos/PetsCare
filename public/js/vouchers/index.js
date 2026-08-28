var table = undefined;
$(document).ready(function () {
  table = $("#table").DataTable({
    ajax: route("vouchers.list"),
    order: [[0, "desc"]],
    columns: [
      {
        data: "id",
        visible: false, // no se muestra
        searchable: false, // no afecta búsquedas
      },
      {
        data: "folio",
      },
      {
        data: "created_at",
      },
      {
        data: null,
        render: function (data) {
          return data.vet ? data.vet.name : "";
        },
      },
      {
        data: null,
        render: function (data) {
          return data.reception ? data.reception.pet.name : "";
        },
      },
      {
        data: "voucher_products",
        render: function (data) {
          if (!data || data.length === 0) return "Sin insumos";

          const visibles = data.slice(0, 2);
          const resto = data.slice(2);

          let html = visibles
            .map(
              (p) =>
                `<span class="badge bg-light text-dark border">${p.product?.NOMBRE ?? "—"}</span>`,
            )
            .join(" ");

          if (resto.length > 0) {
            const todosHtml = data
              .map((p) => `<li>${p.product?.NOMBRE ?? "—"}</li>`)
              .join("");

            html += `
                <a href="#" class="badge bg-primary ms-1 ver-mas-insumos"
                   data-bs-toggle="popover"
                   data-bs-html="true"
                   data-bs-content="<ul class='mb-0 ps-3'>${todosHtml}</ul>"
                   title="Todos los insumos">
                   +${resto.length} más
                </a>`;
          }

          return html;
        },
      },
      {
        data: "status",
        render: function (data) {
          const colores = {
            Creado: "secondary",
            Pendiente: "warning",
            Surtido: "success",
            Rechazado: "danger",
            Cancelado: "dark",
          };
          const color = colores[data] ?? "secondary";
          return `<span class="badge bg-${color}">${data}</span>`;
        },
      },
      {
        data: null,
        render: function (data) {
          let botones = "";

          if (!esAlmacenista) {
            return botones;
          }

          if (data.generated_document_url) {
            botones += `
                <a href="${data.generated_document_url}" 
                   target="_blank"
                   class="btn btn-sm text-primary" 
                   title="Ver vale">
                    <span class="mdi--eye"></span>
                </a>`;
          }

          if (data.status === "Pendiente") {
            botones += `
                <button type="button" 
                        class="btn btn-sm text-success btnAccionVale"
                        data-id="${data.id}" 
                        title="Surtir o rechazar vale">
                    <span class="icon-park--check-correct"></span>
                </button>`;
          }

          return botones;
        },
      },
    ],
  });
  startPollingAlmacen();
});

$("#table").on("draw.dt", function () {
  $('[data-bs-toggle="popover"]').popover({
    trigger: "focus",
  });
});

$(document).on("click", ".btnAccionVale", function () {
  const voucherId = $(this).data("id");

  Swal.fire({
    title: "Selecciona cómo deseas proceder con este vale",
    icon: "question",
    showDenyButton: true,
    showCancelButton: false,
    confirmButtonText: "Surtir",
    denyButtonText: "Rechazar",
    confirmButtonColor: "#198754",
    denyButtonColor: "#dc3545",
    reverseButtons: true,
  }).then((result) => {
    if (result.isConfirmed) {
      openVoucherSignModal("surtir", voucherId);
    } else if (result.isDenied) {
      openVoucherSignModal("rechazar", voucherId);
    }
  });
});

function voucherTableReload() {
  table.ajax.reload(null, false);
}

let pollingAlmacen = null;
let lastUpdateAlmacen = null;

function startPollingAlmacen() {
  if (pollingAlmacen) return;

  pollingAlmacen = setInterval(function () {
    $.ajax({
      url: route("vouchers.lastUpdateGlobal"),
      method: "GET",
      success: function (response) {
        if (lastUpdateAlmacen === null) {
          lastUpdateAlmacen = response.last_update;
          return;
        }

        if (response.last_update !== lastUpdateAlmacen) {
          lastUpdateAlmacen = response.last_update;
          table.ajax.reload(null, false);
        }
      },
    });
  }, 30000);
}

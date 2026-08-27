// Tabla de cuentas compartida entre account/index.blade.php (pantalla
// principal) y la sección "Estados de cuenta" de pet_history/view.blade.php
// — un solo lugar que arma las columnas, para no duplicar el render entre
// ambas pantallas (ver AccountController::list(), el mismo endpoint para las 2).

// Account.status es un enum plano (sin catálogo con color propio, a
// diferencia de AttentionStatus/HospitalizationStatus/etc.) — mismo criterio
// que ya se usó para Cremation.status en cremations/index.js.
const ACCOUNT_STATUS_COLORS = {
  OPEN: "#0455A0",
  CLOSED: "#997404",
  PAID: "#198754",
  CANCELLED: "#dc3545",
};
const ACCOUNT_STATUS_LABELS = {
  OPEN: "Abierta",
  CLOSED: "Cerrada",
  PAID: "Pagada",
  CANCELLED: "Cancelada",
};

function renderAccountStatusBadge(status) {
  if (!status) return "";
  const color = ACCOUNT_STATUS_COLORS[status] || "#6c757d";
  const label = ACCOUNT_STATUS_LABELS[status] || status;
  return `<span style="color: ${color}; background-color: ${lightenColor(color)}; padding: 5px 10px; border-radius: 5px; font-weight: 600;">
        ${label}
      </span>`;
}

/**
 * @param {string} selector - id del <table> (ej. "#table", "#petAccountsTable")
 * @param {function} dataCallback - arma los filtros de ajax.data(), igual
 *   patrón que el resto de las tablas del proyecto (ver receptions/index.js).
 */
function initAccountsTable(selector, dataCallback) {
  return $(selector).DataTable({
    ajax: {
      url: route("accounts.list"),
      data: dataCallback,
    },
    responsive: true,
    order: [[2, "desc"]],
    columns: [
      {
        data: null,
        render: function (data) {
          return data.episode && data.episode.pet ? data.episode.pet.name : "";
        },
      },
      {
        data: null,
        render: function (data) {
          return data.episode && data.episode.pet && data.episode.pet.family
            ? data.episode.pet.family.name
            : "";
        },
      },
     {
    data: null,
    render: function (data) {
        if (!data.episode || !data.episode.opened_at) {
            return "";
        }

        const date = new Date(data.episode.opened_at);

        return date.toLocaleDateString("es-MX", {
            day: "2-digit",
            month: "2-digit",
            year: "numeric"
        }) + " " + date.toLocaleTimeString("es-MX", {
            hour: "2-digit",
            minute: "2-digit",
            hour12: false
        });
    },
},
      {
        data: "status",
        render: renderAccountStatusBadge,
      },
      {
        data: "total",
        render: function (total) {
          return `$${parseFloat(total || 0).toFixed(2)}`;
        },
      },
      {
        data: null,
        render: function (data) {
          return data.latest_sales_order ? data.latest_sales_order.folio : "";
        },
      },
      {
        data: null,
        orderable: false,
        render: function (data) {
          return `<button type="button" class="btn btn-sm icon-btn-outline text-primary" title="Ver detalle"
                        onclick="openAccountDetailModal(${data.id})">
                        <i class="fas fa-eye"></i>
                    </button>`;
        },
      },
    ],
  });
}

async function openAccountDetailModal(id) {
  $("#accountDetailLoader").show();
  $("#accountDetailContent").hide();
  $("#accountDetailEmpty").hide();

  bootstrap.Modal.getOrCreateInstance(
    document.getElementById("accountDetailModal"),
  ).show();

  try {
    const resp = await fetch(route("accounts.detail", id));
    if (!resp.ok) throw new Error("request failed");
    const data = await resp.json();

    $("#accountDetailLoader").hide();

    $("#accountDetailPet").text(data.pet?.name || "");
    $("#accountDetailFamily").text(data.family?.name || "");
    $("#accountDetailStatus")
      .attr("class", "badge")
      .html(renderAccountStatusBadge(data.status));

    const extraParts = [];
    if (data.opened_at) extraParts.push(`Abierta: ${formatDate(data.opened_at, true)}`);
    if (data.closed_at) extraParts.push(`Cerrada: ${formatDate(data.closed_at, true)}`);
    if (data.sales_order?.folio) extraParts.push(`Folio ODV: ${data.sales_order.folio}`);
    $("#accountDetailExtra").text(extraParts.join(" · "));

    if (!data.charges || data.charges.length === 0) {
      $("#accountDetailContent").hide();
      $("#accountDetailEmpty").show();
      return;
    }

    const $charges = $("#accountDetailCharges").empty();
    data.charges.forEach((charge) => {
      $charges.append(`
        <li class="list-group-item d-flex justify-content-between align-items-center">
            <span>${charge.description || ""} <span class="text-muted">x${charge.quantity}</span></span>
            <span>$${parseFloat(charge.total || 0).toFixed(2)}</span>
        </li>
      `);
    });

    $("#accountDetailTotal").text(`Total: $${parseFloat(data.total || 0).toFixed(2)}`);
    $("#accountDetailContent").show();
  } catch (error) {
    $("#accountDetailLoader").hide();
    Swal.fire({
      icon: "error",
      title: "No se pudo cargar el detalle de la cuenta",
    });
    console.error("Error al cargar detalle de cuenta:", error);
  }
}

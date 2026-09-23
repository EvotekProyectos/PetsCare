let BudgetId = null;
let BudgetTable = undefined;
let budgetRowSeq = 0;

// Colores fijos por categoría, mismo criterio que serviceTypeColors en
// appointments/create.js (helper compartido lightenColor() de global.js).
// "Servicio médico" es nuevo aquí (no existe en ServicesTable) y usa teal,
// no verde, ya que ese color está reservado para "Vacuna" en esa otra tabla.
const budgetTypeColors = {
  "Servicio médico": "#0D9488",
  Laboratorio: "#7C3AED",
  Imagenología: "#EA580C",
};


/**
 * Abre el modal para crear un NUEVO presupuesto.
 *
 * Importante:
 * - NO recupera el presupuesto anterior.
 * - NO carga los servicios anteriores en la DataTable.
 * - SÍ muestra el historial de presupuestos con sus PDFs.
 */
async function openBudgetModal() {
  // Siempre comenzar con el formulario completamente limpio.
  resetBudgetForm();

  // Crear las filas iniciales para el nuevo presupuesto.
  clearBudgetRows();

  // Mostrar el modal inmediatamente.
  $("#ModalBudget").modal("show");

  // El historial es independiente del presupuesto nuevo.
  // Se carga en paralelo y no bloquea la apertura.
  loadPreviousBudgets();
}


/**
 * Carga los presupuestos anteriores de la recepción.
 *
 * Estos solamente se muestran como:
 * Folio + fecha + total + PDF.
 *
 * No se cargan sus servicios.
 */
async function loadPreviousBudgets() {
  try {
    let url = route("budgets.reception-history", Reception_Id);
    let peticion = await fetch(url);

    if (peticion.ok) {
      let items = await peticion.json();
      renderPreviousBudgets(items);
    }
  } catch (error) {
    console.error(error);
  }
}


/**
 * Renderiza el historial de presupuestos.
 */
function renderPreviousBudgets(items) {
  const $section = $("#previousBudgetsSection");
  const $list = $("#previousBudgetsList").empty();

  if (!items || items.length === 0) {
    $section.hide();
    return;
  }

  items.forEach(function (item) {
    const folio = "#" + String(item.id).padStart(4, "0");
    const fecha = new Date(item.created_at).toLocaleDateString("es-MX");
    const total = (parseFloat(item.total) || 0).toFixed(2);

    $list.append(`
      <div
        class="d-flex justify-content-between align-items-center py-2"
        style="border-bottom: 1px solid #E5E7EB;"
      >
        <div>
          <span class="fw-bold">Folio ${folio}</span>
          <span class="text-muted ms-2">${fecha}</span>
        </div>

        <div class="d-flex align-items-center gap-3">
          <span class="fw-bold" style="color:#0455A0;">
            $${total}
          </span>

          <a
            href="${item.pdf_url}"
            target="_blank"
            class="action-link"
          >
            Ver PDF
          </a>
        </div>
      </div>
    `);
  });

  $section.show();
}


/**
 * Limpia únicamente el formulario del nuevo presupuesto.
 *
 * NO toca el historial.
 */
function resetBudgetForm() {
  BudgetId = null;
  CurrentBudgetTotal = 0;
  budgetRowSeq = 0;

  ["service", "lab", "img"].forEach(function (category) {
    $(`#${category}Rows`).empty();
  });

  $("#budget-total-price").text("$0.00");
}


/**
 * Reinicia completamente el modal al cerrarse.
 *
 * El historial se limpia del DOM porque se volverá a consultar
 * en la siguiente apertura.
 */
function resetBudgetModal() {
  resetBudgetForm();

  $("#previousBudgetsSection").hide();
  $("#previousBudgetsList").empty();

  // Si por alguna razón existe una DataTable inicializada,
  // también se limpia para evitar estado residual.
  if ($.fn.DataTable.isDataTable("#BudgetTable")) {
    $("#BudgetTable").DataTable().clear().destroy();
  }

  BudgetTable = undefined;

  $("#BudgetTable tbody").empty();
}


$(function () {
  $("#ModalBudget").on("hidden.bs.modal", function () {
    resetBudgetModal();
  });
});


/**
 * Si el usuario navega al flujo de firma (generateBudget) y vuelve
 * con el botón Atrás, algunos navegadores restauran la página
 * desde bfcache.
 */
window.addEventListener("pageshow", function (event) {
  if (event.persisted) {
    resetBudgetModal();
  }
});


/**
 * Agrega una fila dinámica.
 */
function addBudgetRow(category) {
  const rowId = ++budgetRowSeq;

  // Mismo input-group con ícono que budget-detail/form.blade.php (Edit
  // Budget) para el select/precio/notas -solo apariencia, el select sigue
  // siendo el mismo <select> que select2()/updateBudgetRowPrice() de abajo
  // inicializan y leen igual que antes-.
  const row = $(`
    <div
      class="row align-items-center mb-2 budget-row"
      data-row-id="${rowId}"
    >
      <div class="col-md-5 col-12">
        <div class="input-group">
          <span class="input-group-text bg-primary-subtle">
            <span class="vaadin--lines-list"></span>
          </span>

          <select
            class="form-control budget-select"
            id="budget_${category}_${rowId}"
          ></select>
        </div>
      </div>

      <div class="col-md-3 col-6 mt-2 mt-md-0">
        <div class="input-group">
          <span class="input-group-text bg-primary-subtle">
            <span class="f7--money-dollar"></span>
          </span>

          <input
            type="text"
            class="form-control"
            id="budget_${category}_price_${rowId}"
            placeholder="Precio"
            readonly
          >
        </div>
      </div>

      <div class="col-md-3 col-5 mt-2 mt-md-0">
        <div class="input-group">
          <span class="input-group-text bg-primary-subtle">
            <span class="vaadin--lines-list"></span>
          </span>

          <input
            type="text"
            class="form-control"
            id="budget_${category}_notes_${rowId}"
            placeholder="Notas"
          >
        </div>
      </div>

      <div class="col-md-1 col-1 mt-2 mt-md-0 text-end">
        <button
          type="button"
          class="btn-delete-budget"
          onclick="removeBudgetRow('${category}', ${rowId})"
          title="Eliminar fila"
        >
          <i class="fas fa-trash"></i>
        </button>
      </div>
    </div>
  `);

  $(`#${category}Rows`).append(row);

  const select = row.find("select");

  select.append(
    '<option value="">Selecciona el servicio a registrar</option>',
  );

  services.forEach(function (product) {
    select.append(
      `<option value="${product.ARTICULO_ID}">${product.NOMBRE}</option>`,
    );
  });

  select.on("change", function () {
    updateBudgetRowPrice(category, rowId, this.value);
  });

  select.select2({
    theme: "bootstrap-5",
    placeholder: "Buscar Servicio",

    // 'resolve' calcula el ancho leyendo el elemento original:
    // si el modal todavía está oculto (display:none), el cálculo
    // puede dar un ancho incorrecto.
    width: "100%",

    dropdownParent: $("#ModalBudget"),
  });
}


/**
 * Elimina una fila.
 */
function removeBudgetRow(category, rowId) {
  $(`#${category}Rows .budget-row[data-row-id="${rowId}"]`).remove();
}


/**
 * Obtiene el precio del servicio.
 *
 * Esta función se mantiene igual que en tu código original
 * para no afectar el tiempo de respuesta que ya tenías.
 */
async function updateBudgetRowPrice(category, rowId, productId) {
  const priceInput = document.getElementById(
    `budget_${category}_price_${rowId}`,
  );

  if (!productId) {
    priceInput.value = "";
    return;
  }

  let url = route("budget-details.price", productId);
  let peticion = await fetch(url);

  if (peticion.ok) {
    let respuesta = await peticion.json();

    priceInput.value = (
      parseFloat(respuesta.PRECIO) || 0
    ).toFixed(2);
  }
}


/**
 * Limpia y crea las filas iniciales.
 */
function clearBudgetRows() {
  ["service", "lab", "img"].forEach(function (category) {
    $(`#${category}Rows`).empty();
  });

  ["service", "lab", "img"].forEach(function (category) {
    addBudgetRow(category);
  });
}


/**
 * Obtiene las líneas seleccionadas.
 */
function collectBudgetLines() {
  const lines = [];

  ["service", "lab", "img"].forEach(function (category) {
    $(`#${category}Rows .budget-row`).each(function () {
      const rowId = $(this).data("row-id");

      const productId = $(`#budget_${category}_${rowId}`).val();

      if (!productId) return;

      lines.push({
        type: category,
        product_id: productId,

        price:
          $(`#budget_${category}_price_${rowId}`).val() || "0",

        notes:
          $(`#budget_${category}_notes_${rowId}`).val() || "",
      });
    });
  });

  return lines;
}


/**
 * Guarda el nuevo presupuesto.
 *
 * El mensaje de éxito se muestra apenas responde storeBatch() (~40-100ms
 * medido, no es lento) — el refresco de #BudgetTable (ensureBudgetTable())
 * corre después, sin bloquear ese mensaje. Limpiar las filas capturadas
 * (clearBudgetRows()) evita que un segundo clic en "Añadir"/"Agregar"
 * reenvíe las líneas que ya se guardaron.
 */
async function AddBudgetBatch(event) {
  const lines = collectBudgetLines();

  if (lines.length === 0) {
    Swal.fire({
      icon: "warning",
      title:
        "Agrega al menos un servicio, laboratorio o imagen antes de continuar.",
    });

    return;
  }

  // Evita doble envío mientras la petición sigue en curso.
  const $btn = $(event.currentTarget);
  if ($btn.prop("disabled")) {
    return;
  }

  const originalBtnHtml = $btn.html();
  // spinner-border-sm es de Bootstrap (ya cargado en toda la app), a
  // diferencia de .btn-spinner que solo existe en documento-base.css -no
  // cargado en esta pantalla (appointment/create.blade.php usa appointment.css)-.
  $btn.prop("disabled", true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Agregando...');

  try {
    let url = route("budget-details.store-batch");

    let response = await fetch(url, {
      method: "POST",

      headers: {
        "Content-Type": "application/json",
        "X-CSRF-TOKEN":
          $('meta[name="csrf-token"]').attr("content"),
      },

      body: JSON.stringify({
        reception_id: Reception_Id,
        // null en el primer "Agregar a la lista" de esta apertura del modal
        // (resetBudgetForm() ya puso BudgetId = null): el backend debe
        // crear un Budget NUEVO, nunca reutilizar uno de una sesión
        // anterior. Si ya se guardó un lote antes en esta misma sesión
        // (BudgetId ya tiene valor), los lotes siguientes se suman a ese
        // mismo Budget en vez de crear uno por cada clic.
        budget_id: BudgetId,
        lines,
      }),
    });

    let respData = await response.json();

    if (!response.ok) {
      throw new Error(
        respData.message ||
          "Ocurrió un error al guardar el presupuesto.",
      );
    }

    // Guardamos el ID del NUEVO presupuesto.
    // generateBudget() lo utilizará posteriormente.
    BudgetId = respData.budget_id;

    // Mensaje inmediato, ANTES de refrescar la tabla -no depende de ella-.
    Swal.fire({
      icon: "success",
      title: "Servicios agregados al presupuesto",
      timer: 1500,
      showConfirmButton: false,
      timerProgressBar: true,
    });

    // Limpia las filas de captura (evita reenviarlas en un próximo clic) y
    // muestra en #BudgetTable lo que realmente quedó guardado -esto también
    // dispara calculateBudgetTotal() vía el evento 'draw' de la tabla, que
    // es lo único que actualiza CurrentBudgetTotal (si nunca se llama, el
    // total que generateBudget() envía queda en $0.00 sin importar lo
    // guardado)-.
    clearBudgetRows();
    ensureBudgetTable();

  } catch (error) {
    console.error(error);

    Swal.fire({
      icon: "error",
      title: "Error al guardar",
      text:
        error.message ||
        "Ocurrió un error al guardar el presupuesto.",
    });
  } finally {
    $btn.prop("disabled", false).html(originalBtnHtml);
  }
}


/**
 * Esta función se conserva por compatibilidad con el resto de tu código.
 *
 * Ya NO se llama al abrir el modal ni después de guardar,
 * porque no quieres mostrar los servicios en una DataTable.
 */
function ensureBudgetTable() {
  const url = route("budget-details.list", BudgetId);

  if ($.fn.DataTable.isDataTable("#BudgetTable") && BudgetTable) {
    BudgetTable.ajax.url(url).load();
    return;
  }

  if ($.fn.DataTable.isDataTable("#BudgetTable")) {
    $("#BudgetTable").DataTable().destroy();
  }

  BudgetTable = $("#BudgetTable").DataTable({
    ajax: url,
    responsive: true,
    // Sin orden propio: respeta el orden que ya entrega el backend
    // (BudgetDetailController::list(), ordenado por id/orden de inserción).
    // [0, "desc"] reordenaba alfabéticamente por la columna "Tipo Servicio",
    // no por el orden real en que se agregaron los conceptos.
    order: [],

    language: {
      emptyTable:
        "No hay servicios agregados al presupuesto",

      zeroRecords:
        "No se encontraron servicios",
    },

    columns: [
      {
        data: null,

        render: function (data) {
          const tipo = data.serv
            ? "Servicio médico"
            : data.img
              ? "Imagenología"
              : data.lab
                ? "Laboratorio"
                : "";

          if (!tipo) return "";

          const color =
            budgetTypeColors[tipo] || "#6c757d";

          return `
            <span
              style="
                color: ${color};
                background-color: ${lightenColor(color)};
                padding: 5px 10px;
                border-radius: 5px;
                font-weight: 600;
              "
            >
              ${tipo}
            </span>
          `;
        },
      },

      {
        data: null,

        render: function (data) {
          return data.serv
            ? data.serv.NOMBRE
            : data.img
              ? data.img.NOMBRE
              : data.lab
                ? data.lab.NOMBRE
                : "";
        },
      },

      {
        data: "notes",

        render: function (data) {
          return data ? data : "sin notas";
        },
      },

      {
        data: null,

        render: function (data) {
          const precio =
            parseFloat(data.price) || 0;

          return `$${precio.toFixed(2)}`;
        },
      },

      {
        data: null,

        render: function (data) {
          return `
            <button
              type="button"
              class="btn btn-sm text-primary"
              onclick="
                showAlertWithCallback(
                  () => deleteBudgetDetail(
                    ${data.id},
                    BudgetTable
                  )
                );
              "
            >
              <i class="fas fa-trash"></i>
            </button>
          `;
        },
      },
    ],
  });

  BudgetTable.on("draw", function () {
    calculateBudgetTotal(BudgetTable);
  });
}


let CurrentBudgetTotal = 0;


/**
 * Se conserva porque puede seguir siendo utilizada
 * por deleteBudgetDetail() u otro código existente.
 */
function calculateBudgetTotal(table) {
  if (!table || !table.rows) return;

  let total = 0;

  table.rows({ page: "all" }).every(function () {
    const data = this.data();

    if (data.price) {
      total += parseFloat(data.price) || 0;
    }
  });

  CurrentBudgetTotal = total;

  $("#budget-total-price").text(
    `$${total.toFixed(2)}`
  );
}


/**
 * Continúa con el proceso de generación/firma del presupuesto.
 */
async function generateBudget(event) {
  event.preventDefault();

  if (!BudgetId) {
    Swal.fire({
      icon: "warning",
      title:
        "Agrega al menos un servicio antes de continuar.",
    });

    return;
  }

  Swal.fire({
    title: "Procesando...",
    text:
      "Por favor espera mientras procesamos la solicitud.",
    allowOutsideClick: false,

    didOpen: () => {
      Swal.showLoading();
    },
  });

  try {
    let url = route("budget.new.total", BudgetId);

    let form = new FormData();

    form.append("pet_id", Pet_Id);
    form.append("vet_id", vet_id);
    form.append(
      "date",
      new Date().toISOString().slice(0, 10),
    );
    form.append(
      "total",
      CurrentBudgetTotal.toFixed(2),
    );

    let response = await fetch(url, {
      method: "POST",

      headers: {
        "X-CSRF-TOKEN":
          $('meta[name="csrf-token"]').attr("content"),
      },

      body: form,
    });

    if (!response.ok) {
      throw new Error(
        "Error al actualizar el presupuesto"
      );
    }

    Swal.close();

    window.location.href = route(
      "budget.sign",
      BudgetId
    );

  } catch (error) {
    console.error("Error:", error);

    Swal.fire({
      icon: "error",
      title: "Error inesperado",
      text: "Por favor, intenta nuevamente.",
    });
  }
}
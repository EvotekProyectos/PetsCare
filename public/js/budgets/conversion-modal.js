// Conversión de un Presupuesto (creado en la Consulta) a servicios reales de
// Hospitalización (RedSheet) — ver BudgetConversionController/
// BudgetConversionService. El Presupuesto (Budget/BudgetDetail) no se toca
// desde aquí: este archivo solo lee sus líneas para poder generar RedSheet
// reales sin volver a capturarlas a mano. Solo se carga en
// red-sheet/create.blade.php (Hospitalización), donde Reception_Id ya está
// definido como variable global.

/**
 * Se ejecuta al cargar la pantalla de Hospitalización: el botón "Convertir
 * presupuesto a servicios" solo se muestra si de verdad existe al menos un
 * presupuesto FIRMADO (Budget.signed_at) con algo que convertir en el
 * episodio — ya no depende de "el último presupuesto creado" (ver
 * BudgetConversionService::hasEligibleBudget()).
 */
async function checkBudgetConversionEligibility() {
  try {
    const response = await fetch(route("budget-conversions.eligible", Reception_Id), {
      headers: { Accept: "application/json", "X-Requested-With": "XMLHttpRequest" },
    });
    const data = await response.json();

    if (data.eligible) {
      $("#btnConvertBudget").show();
    }
  } catch (error) {
    console.error("Error al verificar el presupuesto convertible:", error);
  }
}

$(document).ready(function () {
  checkBudgetConversionEligibility();
});

function budgetConversionStatusBadge(item) {
  if (item.is_converted) {
    return '<span class="badge bg-success">Convertido</span>';
  }
  if (!item.is_available) {
    return '<span class="badge bg-secondary">No disponible</span>';
  }
  if (item.possible_duplicate) {
    return '<span class="badge bg-warning text-dark">Posible duplicado</span>';
  }
  return '<span class="badge bg-primary">Disponible</span>';
}

async function openBudgetConversionModal() {
  const $tbody = $("#budgetConversionTableBody");
  $tbody.html('<tr><td colspan="5" class="text-center text-muted">Cargando...</td></tr>');
  $("#budgetConversionSelectAll").prop("checked", false);

  bootstrap.Modal.getOrCreateInstance(document.getElementById("BudgetConversionModal")).show();

  try {
    // Ya no lleva un budgetId en la URL: trae las líneas de TODOS los
    // presupuestos firmados del episodio de una sola vez (ver
    // BudgetConversionService::detailsFor()).
    const response = await fetch(
      route("budget-conversions.details", Reception_Id),
      { headers: { Accept: "application/json", "X-Requested-With": "XMLHttpRequest" } },
    );
    const data = await response.json();

    if (!data.details || data.details.length === 0) {
      $tbody.html('<tr><td colspan="5" class="text-center text-muted">No hay presupuestos firmados con servicios.</td></tr>');
      return;
    }

    // Las líneas ya vienen ordenadas por budget_id; se agrupan aquí con un
    // renglón de encabezado por presupuesto (folio + fecha) para que quede
    // claro de cuál presupuesto firmado viene cada servicio.
    let lastBudgetId = null;
    const rows = data.details
      .map(function (item) {
        let header = "";
        if (item.budget_id !== lastBudgetId) {
          lastBudgetId = item.budget_id;
          const folio = "#" + String(item.budget_id).padStart(4, "0");
          header = `<tr class="table-light">
            <td colspan="5">
              <span class="fw-bold">Presupuesto ${folio}</span>
              <span class="text-muted ms-2">${item.budget_date ?? ""}</span>
              <span class="badge bg-success ms-2">Firmado</span>
            </td>
          </tr>`;
        }

        const disabled = item.is_converted || !item.is_available;

        return `${header}<tr>
          <td>
            <input type="checkbox" class="form-check-input budget-conversion-checkbox"
              value="${item.id}" ${disabled ? "disabled" : ""}>
          </td>
          <td>${item.name}</td>
          <td>${item.type ?? "—"}</td>
          <td>$${Number(item.price || 0).toFixed(2)}</td>
          <td>${budgetConversionStatusBadge(item)}</td>
        </tr>`;
      })
      .join("");

    $tbody.html(rows);
  } catch (error) {
    console.error("Error al cargar los servicios del presupuesto:", error);
    $tbody.html('<tr><td colspan="5" class="text-center text-danger">No se pudo cargar el presupuesto.</td></tr>');
  }
}

$(document).on("change", "#budgetConversionSelectAll", function () {
  $(".budget-conversion-checkbox:not(:disabled)").prop("checked", $(this).is(":checked"));
});

$(document).on("click", "#btnConvertSelectedBudget", async function () {
  const ids = $(".budget-conversion-checkbox:checked")
    .map(function () {
      return Number($(this).val());
    })
    .get();

  if (ids.length === 0) {
    Swal.fire({ icon: "warning", title: "Selecciona al menos un servicio" });
    return;
  }

  // const confirmResult = await Swal.fire({
  //   title: "¿Convertir servicios?",
  //   text: `Se agregarán ${ids.length} servicio(s) a Hospitalización. El precio de cobro será el vigente al momento de cerrar la cuenta.`,
  //   icon: "question",
  //   showCancelButton: true,
  //   confirmButtonText: "Sí, convertir",
  //   cancelButtonText: "Cancelar",
  // });

  // if (!confirmResult.isConfirmed) return;

  const $btn = $(this).prop("disabled", true);

  try {
    const response = await fetch(
      route("budget-conversions.convert", Reception_Id),
      {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          Accept: "application/json",
          "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        body: JSON.stringify({ budget_detail_ids: ids }),
      },
    );

    const data = await response.json();

    if (!response.ok) {
      Swal.fire({
        icon: "error",
        title: "No se pudo convertir el presupuesto",
        text: data.message || "Ocurrió un error inesperado.",
      });
      return;
    }

    bootstrap.Modal.getInstance(document.getElementById("BudgetConversionModal"))?.hide();

    const convertedCount = data.converted?.length ?? 0;
    const skippedCount = data.skipped?.length ?? 0;

    let message = `${convertedCount} servicio(s) convertido(s) correctamente.`;
    if (skippedCount > 0) {
      const reasons = data.skipped.map((s) => `• ${s.reason}`).join("<br>");
      message += `<br><br>${skippedCount} no se pudieron convertir:<br>${reasons}`;
    }

    Swal.fire({
      icon: skippedCount > 0 ? "warning" : "success",
      title: "Conversión completada",
      html: message,
    });

    // Refleja los nuevos servicios de inmediato, sin location.reload() (ver
    // fetchAndRenderData(), ya definida en createredsheets.js).
    if (typeof fetchAndRenderData === "function") {
      fetchAndRenderData(false);
    }
  } catch (error) {
    console.error("Error al convertir el presupuesto:", error);
    Swal.fire({ icon: "error", title: "Error", text: "Ocurrió un error inesperado." });
  } finally {
    $btn.prop("disabled", false);
  }
});

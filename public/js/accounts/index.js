var accountsTable = undefined;

$(document).ready(function () {
  accountsTable = initAccountsTable("#table", function (d) {
    d.status = $("#filterAccountEstatus").val();
    d.date_from = $("#filterAccountFechaDesde").val();
    d.date_to = $("#filterAccountFechaHasta").val();
    d.search = $("#filterAccountSearch").val();
  });

  $(
    "#filterAccountEstatus, #filterAccountFechaDesde, #filterAccountFechaHasta, #filterAccountSearch",
  ).on("change", function () {
    accountsTable.ajax.reload(null, false);
  });

  // Búsqueda de texto: recarga también mientras se escribe (con reload
  // inmediato, sin debounce — la tabla no se espera que crezca a un volumen
  // que lo justifique).
  $("#filterAccountSearch").on("keyup", function () {
    accountsTable.ajax.reload(null, false);
  });

  $("#btnClearAccountFilters").on("click", function () {
    $("#filterAccountEstatus").val("");
    $("#filterAccountFechaDesde").val("");
    $("#filterAccountFechaHasta").val("");
    $("#filterAccountSearch").val("");
    accountsTable.ajax.reload(null, false);
  });
});

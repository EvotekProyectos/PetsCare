let tabla = undefined;
$(document).ready(function () {
    tabla = $('#table').DataTable({
        ordering: false,
        pageLength: -1,
        lengthMenu: [[-1], ["Todos"]],
        rowGroup: {
            dataSrc: 1,
            className: 'text-uppercase table-primary'
        },
    });

    new $.fn.dataTable.FixedHeader( tabla);
});
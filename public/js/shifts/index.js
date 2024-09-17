var table = undefined;
$(document).ready(function () {
    table = $('#table').DataTable({
        ajax: route('shifts.list'),
        columns: [
            {
                data: 'name',
            },
            {
                data: 'begin',
            },
            {
                data: 'end',
            },
            {
                data: null,
                render: function (data) {
                    return `
                        <a type="button" href="${route('shifts.edit', data.id)}" class="btn btn-sm text-primary">
                            <i class="fas fa-edit"></i>
                        </a>
                       
                        <button type="button" class="btn btn-sm text-primary" onclick="showAlertWithCallback(() => deleteShifts(${data.id}, table));">
                            <i class="fas fa-trash"></i>
                        </button>`;
                }

            },
        ],
    });
});
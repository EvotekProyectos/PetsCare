var table = undefined;
$(document).ready(function () {
    table = $('#table').DataTable({
        ajax: route('reasons.list'),
        columns: [
            {
                data: 'name',
            },
            {
                data: null,
                render: function (data) {
                    return `
                        <a type="button" href="${route('reasons.edit', data.id)}" class="btn btn-sm text-primary">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button type="button" class="btn btn-sm text-primary" onclick="showAlertWithCallback(() => deleteReason(${data.id}, table));">
                            <i class="fas fa-trash"></i>
                        </button>`;
                }

            },
        ],
    });
});
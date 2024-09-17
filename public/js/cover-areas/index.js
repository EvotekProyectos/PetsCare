var table = undefined;
$(document).ready(function () {
    table = $('#table').DataTable({
        ajax: route('cover-areas.list'),
        columns: [
            {
                data: 'name',
            },
            {
                data: null,
                render: function(data) {
                    return `<input type="color" class="form-control" value="${data.color}" disabled>`
                }
            },
            {
                data: null,
                render: function (data) {
                    return `
                        <a type="button" href="${route('cover-areas.edit', data.id)}" class="btn btn-sm text-primary">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button type="button" class="btn btn-sm text-primary" onclick="showAlertWithCallback(() => deleteCoverArea(${data.id}, table));">
                            <i class="fas fa-trash"></i>
                        </button>`;
                }
            },
        ],
    });
});
var table = undefined;
$(document).ready(function () {
    table = $('#table').DataTable({
        ajax: route('attention-statuses.list'),
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
                        <a type="button" href="${route('attention-statuses.edit', data.id)}" class="btn btn-sm text-primary">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button type="button" class="btn btn-sm text-primary" onclick="showAlertWithCallback(() => deleteAttentionStatus(${data.id}, table));">
                            <i class="fas fa-trash"></i>
                        </button>`;
                }
            },
        ],
    });
});

const deleteAttentionStatus = (id, table) => {
    const url = route("attention-statuses.destroy", id);
    deleteResource(url, table);
};
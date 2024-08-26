var table = undefined;
$(document).ready(function () {
    table = $('#table').DataTable({
        ajax: route('reception-types.list'),
        columns: [
            {
                data: 'name',
            },
            {
                data: null,
                render: function (data) {
                    return `
                        <a type="button" href="${route('reception-types.edit', data.id)}" class="btn btn-sm text-primary">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button type="button" class="btn btn-sm text-primary" onclick="showAlertWithCallback(() => deleteReceptionType(${data.id}, table));">
                            <i class="fas fa-trash"></i>
                        </button>`;
                }

            },
        ],
    });
});

const deleteReceptionType = (id, table) => {
    const url = route("reception-types.destroy", id);
    deleteResource(url, table);
};
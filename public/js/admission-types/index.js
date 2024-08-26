var table = undefined;
$(document).ready(function () {
    table = $('#table').DataTable({
        ajax: route('admission-types.list'),
        columns: [
            {
                data: 'name',
            },
            {
                data: null,
                render: function (data) {
                    return `
                        <a type="button" href="${route('admission-types.edit', data.id)}" class="btn btn-sm text-primary">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button type="button" class="btn btn-sm text-primary" onclick="showAlertWithCallback(() => deleteAdmissionTypes(${data.id}, table));">
                            <i class="fas fa-trash"></i>
                        </button>`;
                }
            },
        ],
    });
});

const deleteAdmissionTypes = (id, table) => {
    const url = route("admission-types.destroy", id);
    deleteResource(url, table);
};
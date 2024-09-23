var table = undefined;
$(document).ready(function () {
    table = $('#table').DataTable({
        ajax: route('reception.list'),
        responsive: true,
        order: [0, 'desc'],
        columns: [
            {
                data: 'entry_date',
            },

            {
                data: null,
                render: function (data) {
                    return data.reception_type ? data.reception_type.name : '';
                }
            },

            {
                data: null,
                render: function (data) {
                    return data.family ? data.family.name : '';
                }
            },
            {
                data: null,
                render: function (data) {
                    return data.pet ? data.pet.name : '';
                }
            },
            {
                data: null,
                render: function (data) {
                    return data.reason ? data.reason.name : '';
                }
            },
            {
                data: null,
                render: function (data) {
                    return `
                        <a type="button" href="${route('receptions.edit', data.id)}" class="btn btn-sm text-primary">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button type="button" class="btn btn-sm text-primary" onclick="showAlertWithCallback(() => deleteReception(${data.id}, table));">
                            <i class="fas fa-trash"></i>
                        </button>`;
                }
            },
        ],
    });
}); 

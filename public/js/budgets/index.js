var table = undefined;
$(document).ready(function () {
    table = $('#table').DataTable({
        ajax: route('budgets.list'),
        columns: [
            {
                data: 'id',
            },
            {
                data: 'date',
            },
            {
                data: null,
                render: function (data) {
                    return data.pet ? data.pet.name: '';
                }
            },
            {
                data: null,
                render: function (data) {
                    return data.surgery_pack ? data.surgery_pack.name + " $" +data.surgery_pack.total: '';
                }
            },
            {
                data: 'procedure',
            },
            {
                data: 'biometric',
            },
            {
                data: 'chemistry',
            },
            {
                data: 'nodulectomy',
            },
            {
                data: 'histopathology',
            },
            {
                data: 'xrays',
            },
            {
                data: 'collar',
            },
            {
                data: 'body',
            },
            {
                data: 'others',
            },
            {
                data: 'total',
            },
            {
                data: null,
                render: function (data) {
                    return data.vet ? data.vet.name : '';
                }
            },
            {
                data: null,
                render: function (data) {
                    return `
                        <a type="button" href="${route('budgets.edit', data.id)}" class="btn btn-sm text-primary">
                            <i class="fas fa-edit"></i>
                        </a>
                       
                        <button type="button" class="btn btn-sm text-primary" onclick="showAlertWithCallback(() => deleteBudget(${data.id}, table));">
                            <i class="fas fa-trash"></i>
                        </button>`;
                }

            },
        ],
    });
});
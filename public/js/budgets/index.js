var table = undefined;
$(document).ready(function () {
    table = $('#table').DataTable({
        ajax: route('budgets.list'),
        columns: [
            {
                data: 'id',
                render: function (data) {
                    return data.toString().padStart(4, '0');
                }
            },
            {
                data: 'date',
            },
            {
                data: null,
                render: function (data) {
                    return data.pet ? data.pet.name : '';
                }
            },
            {
                data: 'others',
            },
            {
                data: null,
                render: function (data) {
                    const precio = parseFloat(data.total) || 0;
                    return `$${precio.toFixed(2)}`;
                }
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

                        <a type="button" href="${route('budgets.show', data.id)}" class="btn btn-sm text-primary">
                            <i class="fas fa-eye"></i>
                        </a>
                       
                        <button type="button" class="btn btn-sm text-primary" onclick="showAlertWithCallback(() => deleteBudget(${data.id}, table));">
                            <i class="fas fa-trash"></i>
                        </button>`;
                }

            },
        ],
    });
});
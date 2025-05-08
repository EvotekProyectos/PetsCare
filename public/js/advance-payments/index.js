var table = undefined;
$(document).ready(function () {
    table = $('#table').DataTable({
        ajax: route('advance-payments.list'),
        columns: [
            {
                data: 'reference',
                // render: function (data) {
                //     return data.toString().padStart(4, '0');
                // }
            },
            {
                data: null,
                render: function (data) {
                    return data.reception ? data.reception.reception_type.name : '';
                }
            },
            {
                data: null,
                render: function (data) {
                    return data.reception ? data.reception.family.name : '';
                }
            },
            {
                data: null,
                render: function (data) {
                    return data.reception ? data.reception.pet.name : '';
                }
            },
            {
                data: 'date',
            },
            {
                data: 'concept',
            },
            {
                data: null,
                render: function (data) {
                    const precio = parseFloat(data.amount) || 0;
                    return `$${precio.toFixed(2)}`;
                }
            },
            // {
            //     data: 'status',
            //     render: function(data, type, row) {
            //         return data == 1 ? 'Pagado' : 'Pendiente';
            //     }
            // },
            // {
            //     data: null,
            //     render: function (data) {
            //         return data.user ? data.user.name : '';
            //     }
            // },
            {
                data: null,
                render: function (data) {
                    return `
                        <a type="button" href="${route('advance-payments.edit', data.id)}" class="btn btn-sm text-primary" title="Editar Anticipo">
                            <i class="fas fa-edit"></i>
                        </a>
                       <a type="button" href="${route('advance-payments.pdf', data.id)}" target="_blank" class="btn btn-sm text-primary" title="Imprimir Anticipo">
                            <i class="lets-icons--paper-fill"></i>
                        </a>
                        <button type="button" class="btn btn-sm text-primary" title="Eliminar Anticipo" onclick="showAlertWithCallback(() => deleteAdvancePayment(${data.id}, table));">
                            <i class="fas fa-trash"></i>
                        </button>`;
                }

            },
        ],
    });
});
var table = undefined;
$(document).ready(function () {
    table = $('#table').DataTable({
        ajax: route('surgery-packs.list'),
        columns: [
            {
                data: 'name',
            },
            {
                data: 'catheterization_price',
            },
            {
                data: 'preanesthetic_price',
            },
            {
                data: 'monitoring_price',
            },
            {
                data: 'surgical_clothing_price',
            },
            {
                data: 'preparations_price',
            },
            {
                data: 'observation_price',
            },
            {
                data: 'total',
            },
            {
                data: null,
                render: function (data) {
                    return `
                        <a type="button" href="${route('surgery-packs.edit', data.id)}" class="btn btn-sm text-primary">
                            <i class="fas fa-edit"></i>
                        </a>
                       
                        <button type="button" class="btn btn-sm text-primary" onclick="showAlertWithCallback(() => deleteSurgeryPacks(${data.id}, table));">
                            <i class="fas fa-trash"></i>
                        </button>`;
                }

            },
        ],
    });
});
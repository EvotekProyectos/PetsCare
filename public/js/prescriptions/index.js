var table = undefined;
$(document).ready(function () {
    table = $('#table').DataTable({
        ajax: route('prescription.list'),
        responsive: true,
        order: [0, 'desc'],
        columns: [
            {
                data: 'date',
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
                    return data.medicine ? data.medicine: '';
                }
            },
            {
                data: null,
                render: function (data) {
                    return data.diagnosis ? data.diagnosis: '';
                }
            },
            {
                data: null,
                render: function (data) {
                    return `
                        <a type="button" href="${route('prescriptions.edit', data.id)}" class="btn btn-sm text-primary">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button type="button" class="btn btn-sm text-primary" onclick="showAlertWithCallback(() => deletePrescription(${data.id}, table));">
                            <i class="fas fa-trash"></i>
                        </button>
                       <a type="button" href="${route('prescription.imprimir', data.id)}" class="btn btn-sm text-primary">
                            <span class="material-symbols--prescriptions-outline" weigth:10px title="Formula medica"></span>
                        </a>`
                }
            },
        ],
    });
}); 

                                       
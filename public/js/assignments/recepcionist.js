$(document).ready(function () {
    table = $('#table').DataTable({
        ajax: route('assignment.altas'),
        responsive: true,
        order: [0, 'desc'],
        columns: [
            {
                data: null,
                render: function (data) {
                    return data.reception ? data.reception.entry_date : '';
                }
            },
            {
                data: null,
                render: function (data) {
                    return data.reception ? data.reception.vet.name : '';
                }
            },

            {
                data: null,
                render: function (data) {
                    return data.reception ? data.reception.admission_type.name : '';
                }
            },
            {
                data: null,
                render: function (data) {
                    return data.reception.family ? data.reception.family.name : '';
                }
            },
            {
                data: null,
                render: function (data) {
                    return data.reception.family ? data.reception.family.phone : '';
                }
            },
            {
                data: null,
                render: function (data) {
                    return data.reception ? data.reception.pet.name : '';
                }
            },
            {
                data: null,
                render: function (data) {
                    return data.reception ? data.reception.pet.raza : '';
                }
            },
           
            {
                data: 'exit_date',
            },
            {
                data: null,
                render: function (data) {
               return data.hospital_discharges ? data.hospital_discharges.name : '';
                }
            },
    ],
    createdRow: function (row, data, dataIndex) {
        const colors = {
            1: '#2BEA91',
            2: '#2DAAF8'
        };

        const dischargeId = data.hospital_discharges ? data.hospital_discharges.id : null;
        if (dischargeId && colors[dischargeId]) {
            $(row).css('background-color', colors[dischargeId]);
        }
    }
});
});

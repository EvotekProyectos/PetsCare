$(document).ready(function () {
    table = $('#table').DataTable({
        ajax: route('assignment.altas'),
        responsive: true,
        order: [0, 'desc'],
        columns: [
            {
                data: 'entry_date',
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
                    return data.admission_type ? data.admission_type.name : '';
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
                    return data.family ? data.family.phone : '';
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
                    return data.pet ? data.pet.raza : '';
                }
            },
           
            {
                data: 'exit_date',
            },
        ],
            rowCallback: function (row, data) {
                const colors = {
                    1: '#2BEA91', // Color para id=1
                    2: '#2DAAF8', // Color para id=2
                };

                if (data.hospitalizations && data.hospitalizations.length > 0) {
                    const dischargeId = data.hospitalizations[0].hospital_discharges_id;
                    if (colors[dischargeId]) {
                        $(row).css('background-color', colors[dischargeId]);
                    }
                }
            },
        });
    });


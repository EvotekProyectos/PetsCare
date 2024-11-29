// Definimos los colores correspondientes para cada estado

const reasons = {
    "1": "#079dd1",
    "2": "#f52528",
    "3": "#85c98b",
    "4": "#f8a693",
    "5": "#71459e",
};

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
    });
});




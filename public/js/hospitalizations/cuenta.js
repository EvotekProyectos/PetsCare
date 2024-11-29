$(document).ready(function () {
    $('#table').DataTable({
        ajax: {
            url: route('cuenta', receptionId),
            dataSrc: 'data'
        },
        responsive: true,
        order: [[0, 'desc']],
        columns: [
            {
                data: null,
                render: function (data) {
                    return data.service ? data.service.name : 
                           data.lab ? data.lab.name : '';
                }
            },
            {
                data: null,
                render: function (data) {
                    return data.service 
                        ? `$${parseFloat(data.service.price).toFixed(2)}`
                        : data.lab 
                        ? `$${parseFloat(data.lab.price).toFixed(2)}`
                        : '';
                }
            }
        ]
    });
});
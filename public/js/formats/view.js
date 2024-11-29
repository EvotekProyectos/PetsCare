$(document).ready(function () {
    $('#table').DataTable({
        ajax: {
            url: route('formats.list', petId),
            dataSrc: 'data'
        },
        responsive: true,
        order: [0, 'desc'],
        columns: [
            {
                data: 'created_at',
            },
            {
                data: null,
                render: function (data) {
                    return data.reception ? data.reception.reception_type_id : '';
                }
            },
            {
                data: null,
                render: function (data) {
                    return data.format_type ? data.format_type.name : '';
                }
            },
          
            { 
                data: 'format_pdf',
                render: function (data) {
                    return `
                        <a href="${data}" class="btn btn-primary" target="_blank">
                        Ver PDF
                        </a>
                    `;
                }
            }
        ]
    });
});


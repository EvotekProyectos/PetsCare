$(document).ready(function () {
    $('#table').DataTable({
        ajax: {
            url: route('list.index'),
            dataSrc: 'data'
        },
        responsive: true,
        order: [0, 'desc'],
        columns: [
            {
                data: 'created_at',
                render: function (data, type, row) {
                    if (data) {
                        const date = new Date(data);
                        const year = date.getFullYear();
                        const month = String(date.getMonth() + 1).padStart(2, '0'); 
                        const day = String(date.getDate()).padStart(2, '0');
                        const hours = String(date.getHours()).padStart(2, '0');
                        const minutes = String(date.getMinutes()).padStart(2, '0');
                        const seconds = String(date.getSeconds()).padStart(2, '0');
                        return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
                    }
                    return ''; 
                }
            },    
            {
                data: null,
                render: function (data) {
                    return data.format_type ? data.format_type.name : '';
                }
            },
             {
                 data: null,
                 render: function (data) {
                     return data.reception ? data.reception.reception_type.name : 'Sin recepción';
                 }
            },
            {
                data: null,
                render: function (data) {
                    if (data.pet) {
                        return data.pet.name; 
                    } else if (data.reception) {
                        return data.reception.pet.name; 
                    }
                    return ''; 
                }
            },            
            { 
                data: 'format_pdf',
                render: function (data) {
                    const newPath = data.replace('public/', 'storage/');
                    return `
                         <a href="${newPath}" class="btn btn-primary" target="_blank">
                        Ver PDF
                        </a>
                    `;
                }
            }
        ]
    });
});


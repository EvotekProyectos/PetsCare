var table = undefined;
$(document).ready(function () {
    table = $('#table').DataTable({
        ajax: route('cubicles.list'),
        columns: [
            {
                data: 'name',
            },
            {
                data: null, 
                render: function(data) {
                    return data.cubicle_type ? data.cubicle_type.name : '';
                }
            }
            ,
            {
                data: 'state',
                render: function(data) {
                    return data === 0 ? 'Disponible' : 'Ocupado';
                }
            },
            {
                data: null,
                render: function (data) {
                    return `
                        <a type="button" href="${route('cubicles.edit', data.id)}" class="btn btn-sm text-primary">
                            <i class="fas fa-edit"></i>
                        </a>
                       
                        <button type="button" class="btn btn-sm text-primary" onclick="showAlertWithCallback(() => deleteCubicle(${data.id}, table));">
                            <i class="fas fa-trash"></i>
                        </button>`;
                }

            },
        ],
        
    });
});
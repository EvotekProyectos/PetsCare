var fileDataTable = undefined;

function showAlertWithCallback(callbackFunction, id) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "¡Esta acción es irreversible!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, eliminar.',
        cancelButtonText: 'No, cancelar.'
    }).then((result) => {
        if (result.isConfirmed) {
            // Llama a la función de callback si se confirma la alerta
            if (typeof callbackFunction === 'function') {
                callbackFunction(id);
            }
        }
    });
}

function iniciarTablaArchivos(record_id = 0) {
    fileDataTable = $('#fileTable').DataTable({
        ajax: route('documents.list', record_id),
        responsive: true,
        select: {
            style: 'multi'
        },
        ordering: [[2, 'asc']],
        columns: [
            {
                data: 'id',
            },
            {
                data: 'name',
            },
            {
                data: null,
                render: function (data) {
                    return "<input type='text' name='' id='paginasde"+data.id+"' placeholder='Ej. 1,2,3 (Separado por comas y sin espacios)' class='form-control'>";
                }
            },
            {
                data: 'description',
            }, 
            {
                data: 'created_at',
                render: function (data) {
                    return data ? moment(data).format('DD-MM-YYYY') : '';
                },
            },
            {
                data: null,
                render: function (data) {
                    return '<a type="button" href="'+data.asset_route+'" target="_blank" class="btn btn-glass btn-primary text-info btn-sm"><i class="fa-solid fa-eye"></i></a><button type="button" class="btn btn-glass btn-warning text-danger btn-sm" onclick="showAlertWithCallback(deleteDocument, '+ data.id+')"><i class="fa-solid fa-trash-can"></i></button>';
                }
            }
        ],
        columnDefs: [
            {
                targets: [0], // Índice de la columna que deseas ocultar
                className: "d-none"
                // hidden: true // Configura esta columna como no visible.
            }
        ]
    });

    
     
    // document.querySelector('#button').addEventListener('click', function () {
    //     alert(table.rows('.selected').data().length + ' row(s) selected');
    // });
}



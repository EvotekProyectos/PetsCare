var table = undefined;
$(document).ready(function () {
    table = $('#table').DataTable({
        ajax: route('assignment.list'),
        responsive: true,
        order: [0, 'desc'],
        columns: [
            {
                data: 'entry_date',
            },

            {
                data: null,
                render: function (data) {
                    return data.reception_type ? data.reception_type.name : '';
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
                    return data.pet ? data.pet.name : '';
                }
            },
            {
                data: null,
                render: function (data) {
                    return data.reason ? data.reason.name : '';
                }
            },
             {
                 data: null,
                 render: function (data) {
                    return data.status ? data.status : ''; 
                 }
             },
            {
                data: null,
                render: function (data) {
                    return `
                        
                        <button type="button" class="btn btn-sm text-primary" onclick="Attend(${data.reception_type_id}, ${data.id});">
                            <span class="mage--hospital-shield-fill"></span>
                        </button>`;
                }
            },
        ],
    });
}); 

async function Attend(Type, ID) {
    event.preventDefault();
    Swal.fire({
        title: '¿Estás listo para atender este paciente?',
        text: "Confirma su atención",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, atender.',
        cancelButtonText: 'No, seguira en espera.'
    }).then((result) => {
        if (result.isConfirmed) {
            if (Type === 1) {
                    window.location.href = route('appointment.consultation', ID);
            }
        }
    });
    
}
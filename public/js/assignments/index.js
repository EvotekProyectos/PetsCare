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
                        
                        <button type="button" class="btn btn-sm text-primary" onclick="Attend(${data.reception_type_id}, ${data.id}, ${data.status_id} );">
                            <span class="mage--hospital-shield-fill"></span>
                        </button>`;
                }
            },
        ],
    });
});

async function Attend(Type, ID, Status) {
    event.preventDefault();
    if (Status != 2) {
        Swal.fire({
            icon: "warning",
            title: "Esta mascota ya fue atendida",
            timer: 7000,
            showConfirmButton: true
        })
    } else {
        const result = await Swal.fire({
            title: '¿Estás listo para atender este paciente?',
            text: "Confirma su atención",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, atender.',
            cancelButtonText: 'No, seguira en espera.'
        });
        if (result.isConfirmed && Type === 1) {
            if (Type === 1) {
                let url = route('reception-status-histories.store');
                let form = new FormData();
                form.append('reception_id', ID);
                form.append('attention_status_id', 3);
                let csrfToken = document.querySelector('input[name="_token"]').value;
                form.append('_token', csrfToken);
                let pet = await fetch(url, { method: "POST", body: form });
                if (pet.ok) {
                    window.location.href = route('appointment.consultation', ID);
                }

            }
        }
    };
}

// Definimos los colores correspondientes para cada estado
const attentionStatuses = {
    "Atendido": "#56BF2F",
    "En espera": "#FF2C2C",
    "En consulta": "#FFBE33"
};

const reasons = {
    "Consulta General": "#6CC3E3",
    "Consulta de Seguimiento": "#917AAC",
    "Medicina Preventiva": "#F8A693",
    "Consulta especialidad": "#FFF7952",
    "Curación/Cambio de vendaje":"#95FFEA",
    "Retiro de sutura": "#FF69B42",
    "Servicios externos": "#A52A2A",
    "Estudios de laboratorio":"#FF69B4"
};

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
                data: 'reason',
                render: function (data) {
                    if (reasons[data]) {
                        return `<span style="background-color: ${reasons[data]}; padding: 5px; color: white; border-radius: 5px;">${data}</span>`;
                    }
                    return data || '';
                }
            
            },
            {
                data: null,
                render: function (data) {
                    return data.room ? data.room.name : '';
                }
            },
            {
                data: 'status',
                render: function (data) {
            
                    if (attentionStatuses[data]) {
                        return `<span style="background-color: ${attentionStatuses[data]}; padding: 5px; color: white; border-radius: 5px;">${data}</span>`;
                    }
                    return data || '';
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

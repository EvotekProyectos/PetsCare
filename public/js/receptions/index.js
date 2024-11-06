const reasons = {
    "Consulta General": "#6CC3E3",
    "Consulta de Seguimiento": "#917AAC",
    "Medicina Preventiva": "#F8A693",
    "Consulta especialidad": "#FFDE59",
    "Curación/Cambio de vendaje":"#95FFEA",
    "Retiro de sutura": "#FC9BCC",
    "Servicios externos": "#CD4D4D",
    "Estudios de laboratorio":"#FE9900"
};

var table = undefined;
$(document).ready(function () {
    table = $('#table').DataTable({
        ajax: route('reception.list'),
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
                    const reasonName = data && data.name ? data.name : '';

                    if (reasonName && reasons[reasonName]) {
                        return `<span style="background-color: ${reasons[reasonName]}; padding: 5px; color: black; border-radius: 5px;">${reasonName}</span>`;
                    }
                    
                    return reasonName || '';
                }
            },  
             {
                data: null,
                render: function (data) {
                    return data.room ? data.room.name : '';
                }
            },
            {
                data: null,
                render: function (data) {
                    return `
                        <a type="button" href="${route('receptions.edit', data.id)}" class="btn btn-sm text-primary">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button type="button" class="btn btn-sm text-primary" onclick="showAlertWithCallback(() => deleteReception(${data.id}, table));">
                            <i class="fas fa-trash"></i>
                        </button>`;
                }
            },
        ],
    });
}); 

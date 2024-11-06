const reasons = {
    "1": "#6CC3E3",
    "2": "#917AAC",
    "3": "#F8A693",
    "4": "#FFF7952",
    "5":"#95FFEA",
    "6": "#FF69B42",
    "7": "#A52A2A",
    "8":"#FF69B4"
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
                data: null,
                render: function (data) {
                    if (data && data.reason_id && reasons[data.reason_id]) {
                        return `<span style="background-color: ${reasons[data.reason_id]}; padding: 5px; color: black; border-radius: 5px;">
                                    ${data.reason.name}
                                </span>`;
                    }
                    return '';
                }
            }, {
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

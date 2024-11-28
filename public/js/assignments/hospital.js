// Definimos los colores correspondientes para cada estado

const reasons = {
    "1": "#079dd1",
    "2": "#f52528",
    "3": "#85c98b",
    "4": "#f8a693",
    "5": "#71459e",
};

$(document).ready(function () {
    table = $('#table').DataTable({
        ajax: route('assignment.hospitalizations'),
        responsive: true,
        order: [0, 'desc'],
        columns: [
            {
                data: 'entry_date',
            },
            {
                data: null,
                render: function (data) {
                    return data.admission_type ? data.admission_type.name : '';
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
                    if (reasons[data.area_id]) {
                        return `<span style="background-color: ${reasons[data.area_id]}; padding: 5px; color: white; border-radius: 5px;">${data.area.name}</span>`;
                    }
                    return data || '';
                }
            
            },
            {
                data: null,
                render: function (data) {
                    return data.vet ? data.vet.name : '';
                }
            },
            {
                data: null,
                render: function (data) {
                    return `
                        <a type="button" href="${route('redsheet.entry', data.id)}" class="btn btn-sm text-primary">
                            <span class="mage--hospital-shield-fill"></span>
                        </a>
                        <a type="button" href="${route('hospitalization.historic', data.id)}" class="btn btn-sm text-primary">
                            <span class="material-symbols--folder-eye-sharp"></span>
                        </a>`;
                }
            },
        ],
    });
});




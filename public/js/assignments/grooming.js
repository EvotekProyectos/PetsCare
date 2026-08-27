var table = undefined;

$(document).ready(function () {
    table = $('#table').DataTable({
        ajax: {
            url: route('assignment.groomings'),
            data: function (d) {
                d.date = $('#filterGroomingFecha').val();
                d.status_id = $('#filterGroomingEstado').val();
            },
        },
        responsive: true,
        order: [0, 'desc'],
        columns: [
            {
                data: 'entry_date',
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
                    return data.vet ? data.vet.name : '';
                }
            },
            {
                data: null,
                render: function (data) {
                    if (data.status) {
                        return `<span style="background-color: ${data.status.color}; color: black; padding: 5px; border-radius: 5px;">
                                    ${data.status.name}
                                </span>`;
                    }
                    return 'No status';
                }

            },
            {
                data: 'exit_date',
            },
            {
                data: null,
                render: function (data) {
                    return data.grooming ? data.grooming.delivery_service == 1 ? 'Domicilio' : 'En tienda' : '';
                }
            },
            {
                data: null,
                render: function (data) {
                    let buttons = '';
                    let CriticPDF = rutaBase + `/responsive_${data.grooming?.id}.pdf`;

                    buttons += `<a type="button" href="${route('groomings.show', data.id)}" class="btn btn-sm icon-btn-outline text-primary" title="Ver">
                        <span class="gravity-ui--scissors"></span>
                    </a>`;

                    // if (data.grooming?.delivery_service == 1) {
                    //     buttons += `
                    //     <a type="button" href="${route('pdf.delivery', data.id)}" target="_blank" class="btn btn-sm icon-btn-outline text-danger">
                    //         <span class="mdi--house-export-outline"></span>
                    //     </a>`;
                    // }

                    if (data.grooming?.critic_status == 1) {
                        buttons += `
                            <a type="button" href="${CriticPDF}" target="_blank" class="btn btn-sm icon-btn-outline text-warning" title="Estado crítico">
                                <span class="material-symbols--pulse-alert-outline"></span>
                            </a>`;
                    }

                    return buttons;
                }
            },
        ],
    });

    $('#filterGroomingFecha, #filterGroomingEstado').on('change', function () {
        table.ajax.reload(null, false);
    });

    $('#btnClearGroomingFilters').on('click', function () {
        $('#filterGroomingFecha').val('');
        $('#filterGroomingEstado').val('');
        table.ajax.reload(null, false);
    });
});
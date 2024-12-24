$(document).ready(function () {
    table = $('#table').DataTable({
        ajax: route('assignment.groomings'),
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
                    return `
                        <a type="button" href="${route('groomings.show', data.id)}" class="btn btn-sm text-primary">
                            <span class="mage--hospital-shield-fill"></span>
                        </a>`;
                }
            },
        ],
    });
});
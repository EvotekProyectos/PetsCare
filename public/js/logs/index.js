var table = undefined;
$(document).ready(function () {
    table = $('#table').DataTable({
        ajax: route('logs.list'),
        responsive: true,
        order: [0, 'desc'],
        columns: [
            {
                data: 'created_at',
                render: function (data) {
                    return data ? moment(data).format('DD-MM-YYYY hh:mm:ss a') : '';
                },
            },
            {
                data: 'action',
            },
            {
                data: 'description',
            },
            {
                data: null,
                render: function (data) {
                    return (data.user) ? data.user.name : '';
                }
            }
        ],
    });
}); 

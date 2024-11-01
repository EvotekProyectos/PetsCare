window.onload = function () {
    let fileRoute = Pic_route.startsWith('/') ? Pic_route.substring(1) : Pic_route;

    if (Pic_id !== null) {
        $("#preview").attr("src", ruta + fileRoute);
    }
    else {
        $("#preview").attr("src", imgDefault);
    }

}

var table = undefined;
$(document).ready(function () {
    table = $('#table').DataTable({
        ajax: route('reception.historial', Pet_Id),
        responsive: true,
        order: [0, 'desc'],
        columns: [
            {
                data: 'entry_date',
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
                    return data.reception_type ? data.reception_type.name : '';
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
                    return `
                        <a class="btn btn-sm btn-primary"  title="Ver Detalles" href="#" onclick="Details(${data.reception_type_id}, ${data.id});">
                            <span class="mage--hospital-shield-fill"></span>
                        </a>`;
                }
            },
        ],
    });
});


async function Details(Type, ID) {
    event.preventDefault();
    if (Type === 1) {
        window.location.href = route('appointment.historic', ID);

    }
};
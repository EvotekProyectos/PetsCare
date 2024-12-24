window.onload = function () {
    if (Pic_id !== null) {
        let fileRoute = Pic_route.startsWith('/') ? Pic_route.substring(1) : Pic_route;
        $("#preview").attr("src", ruta + fileRoute);
    }
    else {
        $("#preview").attr("src", imgDefault);
    }

}

var table = undefined;
$(document).ready(function () {
    table = $('#table').DataTable({
        ajax: route('groomings.list', Reception_Id),
        responsive: true,
        order: [0, 'desc'],
        columns: [
            {
                data: 'serv.NOMBRE',
            },
            {
                data: 'notes',
            },
            {
                data: null,
                render: function (data) {
                    const precio = parseFloat(data.service.PRECIO) || 0;
                    return `$${precio.toFixed(2)}`;
                }
            },

        ],
    });
    table.on('draw', function () {
        calculateTotal(table);
    });
});

function calculateTotal(table) {
    let total = 0;

    table.rows({ page: 'all' }).every(function () {
        const data = this.data();
        if (data.service && data.service.PRECIO) {
            total += parseFloat(data.service.PRECIO);
        }
    });

    $('#total-price').text(`Total Final: $${total.toFixed(2)}`);
}

async function EndService() {
    event.preventDefault();
    let url = route('grooming.status');
    let form = new FormData(document.getElementById("updatestatus"));
    let pet = await fetch(url, { method: "POST", body: form });

    if (pet.ok) {
        await Swal.fire({
            icon: "success",
            title: "Se finalizó el servicio",
            timer: 7000,
            showConfirmButton: true,
            willClose: () => {
                window.location.href = route('assignment.groomings');
            }
        });
    } else {
        let resp = await pet.json();
        Swal.fire({
            icon: "error",
            body: resp
        })
    }
}
window.onload = function () {
    if (Pic_id !== null) {
        let fileRoute = Pic_route.startsWith('/') ? Pic_route.substring(1) : Pic_route;
        $("#preview").attr("src", ruta + fileRoute);
    }
    else {
        $("#preview").attr("src", imgDefault);
    }
    // const now = new Date();
    // const hours = String(now.getHours()).padStart(2, '0');
    // const minutes = String(now.getMinutes()).padStart(2, '0');
    // document.getElementById("time").value = `${hours}:${minutes}`;

}

async function NewEntry() {
    event.preventDefault();
    let url = route('red-sheets.store');
    let form = new FormData(document.getElementById("NewRedSheet"));
    let pet = await fetch(url, { method: "POST", body: form });
    let resp = await pet.json();

    if (pet.ok) {
        Swal.fire({
            icon: "success",
            title: "Se guardaron los procedimientos con exito",
            timer: 7000,
            showConfirmButton: true
        })
        table.ajax.reload();
    } else {
        let resp = await pet.json();
        Swal.fire({
            icon: "error",
            body: resp
        })
    }
}

var table = undefined;
$(document).ready(function () {
    table = $('#table').DataTable({
        ajax: route('red-sheets.recap', Reception_Id),
        responsive: true,
        order: [0, 'desc'],
        columns: [
            {
                data: 'created_at',
                render: function(data) {
                    if (data) {
                        let date = new Date(data);
                        let formattedDate = date.toLocaleDateString('en-US', {
                            year: 'numeric',
                            month: 'short',
                            day: 'numeric'
                        });
                        let formattedTime = date.toLocaleTimeString('en-US', {
                            hour: '2-digit',
                            minute: '2-digit'
                        });
                        return `${formattedDate} ${formattedTime}`;
                    }
                    return '';
                }
            },

            {
                data: 'day_count',
            },

            {
                data: null,
                render: function (data) {
                    return data.service ? data.service.name : '';
                }
            },

            {
                data: null,
                render: function (data) {
                    return data.lab ? data.lab.name : '';
                }
            },
            {
                data: null,
                render: function (data) {
                    return data.imaging ? data.imaging.name : '';
                }
            },
            {
                data: 'observations',
            },
            {
                data: null,
                render: function (data) {
                    return data.vet ? data.vet.name : '';
                }
            },
        ],
    });
});

async function OpenFollowUps() {
    document.getElementById("reception_id_followup").value = Reception_Id;
    $('#ModalFollowUps').modal('show');
}

function CloseFollowUp() {
    document.getElementById("time").value = "";
    document.getElementById("details").value = "";
    document.getElementById("temperature").value = "";
    document.getElementById("systolic").value = "";
    document.getElementById("diastolic").value = "";
    document.getElementById("average").value = "";
    document.getElementById("glycemia_level").value = "";
    $('#ModalFollowUps').modal('hide');
}

async function AddFollowUp() {
    event.preventDefault();
    let url = route('follow-ups.store');
    let form = new FormData(document.getElementById("NewFollowUp"));
    let pet = await fetch(url, { method: "POST", body: form });
    let resp = await pet.json();

    if (pet.ok) {
        Swal.fire({
            icon: "success",
            title: "Se guardo el seguimiento con exito",
            timer: 7000,
            showConfirmButton: true
        })
        CloseFollowUp()
    } else {
        let resp = await pet.json();
        Swal.fire({
            icon: "error",
            body: resp
        })
    }
}


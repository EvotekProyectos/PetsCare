window.onload = function () {
    let fileRoute = Pic_route.startsWith('/') ? Pic_route.substring(1) : Pic_route;
   
    if (Pic_id !== null) {
        $("#preview").attr("src", ruta + fileRoute);
    }
    else {
        $("#preview").attr("src", imgDefault);
    }


}

async function AddPrescription() {
    event.preventDefault();
    let url = route('prescriptions.store');
    let form = new FormData(document.getElementById("NewPrescription"));
    let pet = await fetch(url, { method: "POST", body: form });

    if (pet.ok) {
        Swal.fire({
            icon: "success",
            title: "Se guardo la receta médica",
            timer: 7000,
            showConfirmButton: true
        })
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
                        
                        <button type="button" class="btn btn-sm text-primary" onclick="Attend(${data.reception_type_id}, ${data.id});">
                            <span class="mage--hospital-shield-fill"></span>
                        </button>`;
                }
            },
        ],
    });
});
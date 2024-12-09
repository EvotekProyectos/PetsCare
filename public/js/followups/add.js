window.onload = function () {
    if (Pic_id !== null) {
        let fileRoute = Pic_route.startsWith('/') ? Pic_route.substring(1) : Pic_route;
        $("#preview").attr("src", ruta + fileRoute);
    }
    else {
        $("#preview").attr("src", imgDefault);
    }
  

}

async function OpenCritics() {
    document.getElementById("reception_id_critics").value = Reception_Id;
    console.log(Reception_Id);
    
    $('#ModalCritics').modal('show');
}

async function AddCritics() {
    event.preventDefault();
    let url = route('followups-critics.store');
    let form = new FormData(document.getElementById("NewCritic"));
    let pet = await fetch(url, { method: "POST", body: form });
    let resp = await pet.json();

    if (pet.ok) {
        Swal.fire({
            icon: "success",
            title: "Se guardo el seguimiento con exito",
            timer: 7000,
            showConfirmButton: true
        })
        // followtable.ajax.reload();
        CloseCritics()
    } else {
        let resp = await pet.json();
        Swal.fire({
            icon: "error",
            body: resp
        })
    }
}

function CloseCritics() {
    document.getElementById("pet_status_critics").value = "";
    document.getElementById("preasure_critics").value = "";
    document.getElementById("temperature_critics").value = "";
    document.getElementById("glycemia_critics").value = "";
    document.getElementById("throwup_detail_critics").value = "";
    document.getElementById("defecate_detail_critics").value = "";
    document.getElementById("orino_detail_critics").value = "";
    document.getElementById("eat_detail_critics").value = "";
    document.getElementById("infusions_detail_critics").value = "";
    document.getElementById("terapeutic_detail_critics").value = "";
    document.getElementById("pends_critics").value = "";
    document.getElementById("imaging_detail_critics").value = "";
    $('#ModalCritics').modal('hide');
}

var tableCritics = undefined;
$(document).ready(function () {
    table = $('#critics_table').DataTable({
        ajax: route('followups-critics.list', Reception_Id),
        responsive: true,
        order: [0, 'desc'],
        columns: [
            {
                data: 'created_at',
            },

            {
                data: 'pet_status',
            },

            {
                data: 'preasure',
            },
            {
                data: 'temperature',
            },
            {
                data: 'glycemia',
            },
            {
                data: 'throwup_detail',
            },
            {
                data: 'defecate_detail',
            },
            {
                data: 'orino_detail',
            },
            {
                data: 'eat_detail',
            },
            {
                data: 'infusions_detail',
            },
            {
                data: 'terapeutic_detail',
            },
            {
                data: 'imaging_detail',
            },
            {
                data: 'pends',
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
                        <a type="button" href="${route('followups-critics.edit', data.id)}" class="btn btn-sm text-primary">
                            <i class="fas fa-edit"></i>
                        </a>
                       
                        <button type="button" class="btn btn-sm text-primary" onclick="showAlertWithCallback(() => deleteFollowUpCritic(${data.id}, table));">
                            <i class="fas fa-trash"></i>
                        </button>`;
                }
            },
            
        ],
    });
});
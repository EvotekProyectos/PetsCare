window.onload=function(){
    let familia= document.getElementById("family_id").value
    getpets(familia)
    first(type)
}

let isUpdating = false;

async function getpets(family_id) {
    if (isUpdating) return;
    isUpdating = true;

    let url = route("pets.data", family_id)
    let peticion = await fetch(url)
    if (peticion.ok) {
        document.getElementById("pet_id").value
        let respuesta = await peticion.json()
        let html = ""
        respuesta.forEach(pet => {
            html += `<option value="${pet.id}">${pet.name} #${pet.number_chip}</option>`;
        });
        document.getElementById("pet_id").innerHTML = html

    }
    isUpdating = false;
}

async function getFamily(pet_id) {
    if (isUpdating) return;
    isUpdating = true;
    let url = route("families.getFamilyByPet", pet_id);
    let peticion = await fetch(url);
    if (peticion.ok) {
        let family = await peticion.json();
        if (family) {
            $('#family_id').val(family.id).trigger('change');
            //  getPets(family.id); 
            isUpdating = false;
        }
    }
    isUpdating = false;
}


function togglee(radio) {
    document.getElementById("adm").style.display = "none";
    document.getElementById("area").style.display = "none";
    document.getElementById("motivo").style.display = "none";
    document.getElementById("mvz").style.display = "none";
    document.getElementById("consultorio").style.display = "none";
    document.getElementById("salida").style.display = "none";
    document.getElementById("num").style.display = "none";


    var type = parseInt(radio.value);

    switch (type) {
        case 1:
            document.getElementById("motivo").style.display = "block";
            document.getElementById("mvz").style.display = "block";
            document.getElementById("consultorio").style.display = "block";
            break;
        case 2:
            document.getElementById("adm").style.display = "block";
            document.getElementById("area").style.display = "block";
            document.getElementById("mvz").style.display = "block";
            break;
        case 3:
            document.getElementById("mvz").style.display = "block";
            document.getElementById("salida").style.display = "block";
            document.getElementById("num").style.display = "block";
            break;
        case 4:
            document.getElementById("mvz").style.display = "block";
            document.getElementById("salida").style.display = "block";
            document.getElementById("num").style.display = "block";
            break;
        case 5:
            document.getElementById("mvz").style.display = "block";
            // document.getElementById("salida").style.display = "block";
            break;
        default:
            break;
    }

}

function first(value) {
    document.getElementById("adm").style.display = "none";
    document.getElementById("area").style.display = "none";
    document.getElementById("motivo").style.display = "none";
    document.getElementById("mvz").style.display = "none";
    document.getElementById("consultorio").style.display = "none";
    document.getElementById("salida").style.display = "none";
    document.getElementById("num").style.display = "none";

    switch (value) {
        case 1:
            document.getElementById("motivo").style.display = "block";
            document.getElementById("mvz").style.display = "block";
            document.getElementById('person').innerText = 'M.V.Z.';
            document.getElementById("consultorio").style.display = "block";
            break;
        case 2:
            document.getElementById("adm").style.display = "block";
            document.getElementById("area").style.display = "block";
            // document.getElementById("motivo").style.display = "block";
            document.getElementById("mvz").style.display = "block";
            document.getElementById('person').innerText = 'M.V.Z.';
            // document.getElementById("salida").style.display = "block";
            break;
        case 3:
            document.getElementById("mvz").style.display = "block";
            document.getElementById('person').innerText = 'Colaborador';
            document.getElementById("salida").style.display = "block";
            document.getElementById("num").style.display = "block";
            break;
        case 4:
            document.getElementById("mvz").style.display = "block";
            document.getElementById('person').innerText = 'M.V.Z.';
            document.getElementById("salida").style.display = "block";
            document.getElementById("num").style.display = "block";
            break;
        case 5:
            document.getElementById("mvz").style.display = "block";
            document.getElementById('person').innerText = 'M.V.Z.';
            // document.getElementById("salida").style.display = "block";
            break;
        default:
            break;
    }

}

$(document).ready(function () {
    $('#family_id').select2({
        placeholder: 'Buscar Familia',
        width: 'resolve'
    });
    $('#pet_id').select2({
         placeholder: 'Buscar Mascota',
          width: 'resolve'
     });
});
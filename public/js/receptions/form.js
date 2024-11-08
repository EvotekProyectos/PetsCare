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

async function getpets(family_id) {
    let url = route("pets.preview", family_id)
    let peticion = await fetch(url)
    if (peticion.ok) {
        let respuesta = await peticion.json()
        let html = ""
        respuesta.forEach(pet => {
            html += `<option value="${pet.id}">${pet.name}</option>`;
        });
        document.getElementById("pet_id").innerHTML = html

    }
}

 async function getFamily(pet_id) {
     let url = route("families.getFamilyByPet", pet_id); 
     let peticion = await fetch(url);
     if (peticion.ok) {
         let family = await peticion.json();
         if (family) {
             $('#family_id').val(family.id).trigger('change');
             getPets(family.id); 
         }
     }
 }


function togglee(radio) {
    document.getElementById("adm").style.display = "none";
    document.getElementById("area").style.display = "none";
    document.getElementById("motivo").style.display = "none";
    document.getElementById("mvz").style.display = "none";
    document.getElementById("consultorio").style.display = "none";
    document.getElementById("salida").style.display = "none";


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
            break;
        case 4:
            document.getElementById("mvz").style.display = "block";
            break;
        case 5:
            document.getElementById("mvz").style.display = "block";
            document.getElementById("salida").style.display = "block";
            break;
        default:
            break;
    }
}


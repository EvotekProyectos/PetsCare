$(document).ready(function () {
    $('#family_id').select2({
        placeholder: 'Buscar Familia',
        width: 'resolve'
    });
    $('#pet_id').select2({
        placeholder: 'Buscar Mascota',
        width: 'resolve'
    });

    if (errors['reception_type_id']) {
        $('input[name="reception_type_id"]').addClass('is-invalid');
        $('#cremacion').parent().append('<div class="invalid-feedback"><strong>' + errors['reception_type_id'][0] + '</strong></div>');
    } else {
        $('input[name="reception_type_id"]').removeClass('is-invalid');
        $('.invalid-feedback').remove();
    }
    if (errors['pet_id']) {
        $('#pet_id').addClass('is-invalid');
        $('.select2-selection').addClass('is-invalid');
        $('#pet_id').parent().append('<div class="invalid-feedback"><strong>' + errors['pet_id'][0] + '</strong></div>');
    }
    if (errors['veterinarian_id']) {
        $('#veterinarian_id').addClass('is-invalid');
        $('.select2-selection').addClass('is-invalid');
        $('#veterinarian_id').parent().append('<div class="invalid-feedback"><strong>' + errors['veterinarian_id'][0] + '</strong></div>');
    }
    if (errors) {
        const selectedRadio = $('input[name="reception_type_id"]:checked');
        if (selectedRadio.length > 0) {
            togglee(selectedRadio[0]); 
        }
    }


});

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


//Activar case 4
let isCase4Active = false; 

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
            document.getElementById('person').innerText = 'M.V.Z.';
            document.getElementById("consultorio").style.display = "block";
            break;
        case 2:
            document.getElementById("adm").style.display = "block";
            document.getElementById("area").style.display = "block";
            document.getElementById("mvz").style.display = "block";
            document.getElementById('person').innerText = 'M.V.Z.';
            break;
        case 3:
            document.getElementById("mvz").style.display = "block";
            document.getElementById("salida").style.display = "block";
            document.getElementById("num").style.display = "block";
            document.getElementById('person').innerText = 'COLABORADOR';
            isCase4Active = false;

            break;
        case 4:
            document.getElementById("mvz").style.display = "block";
            document.getElementById('person').innerText = 'M.V.Z.';
            document.getElementById("salida").style.display = "block";
            document.getElementById("num").style.display = "block";
            isCase4Active = true; // Activamos el estado para el case 4
            break;
        case 5:
            document.getElementById("mvz").style.display = "block";
            document.getElementById('person').innerText = 'M.V.Z.';
            //document.getElementById("salida").style.display = "block";
            break;
        default:
            break;
    }
}

//Bloquear los domingos para la fecha de salida de pension
document.getElementById("exit_date").addEventListener("input", function () {
    if (isCase4Active) { 
        let input = this;
        let date = new Date(input.value);
        
        if (date.getDay() === 0) { // Si el día es domingo
            Swal.fire({
                icon: 'error',
                title: '¡Error!',
                text: 'Los domingos no están permitidos.',
                confirmButtonText: 'Aceptar'
            });
            input.value = ""; 
        }
    }
});
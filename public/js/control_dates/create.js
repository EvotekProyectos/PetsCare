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

let isUpdating = false;

async function getpets(family_id) {
    if (isUpdating) return;
    isUpdating = true;

    let url = route("pets.preview", family_id)
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


var table = undefined;
$(document).ready(function () {
    table = $('#table').DataTable({
        ajax: route('dates.list'),
        responsive: true,
        order: [0, 'desc'],
        columns: [
            {
                data: null,
                render: function (data) {
                    return data.family ? data.family.name : '';
                }
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
                    return data.data_type ? data.data_type.name : '';
                }
            },
            {
                data: 'date',
            },
            {
                data: null,
                render: function (data) {
                    return data.status_date ? data.status_date.name : '';
                }
            },
             {
                 data: null,
                 render: function (data) {
                     return `
                         <a type="button" href="${route('surgery-schedules.edit', data.id)}" class="btn btn-sm text-primary">
                             <i class="lucide--calendar-clock"></i>
                         </a>
                         <button type="button" class="btn btn-sm text-primary" onclick="showAlertWithCallback(() => deletesurgerySchedule(${data.id}, table));">
                             <i class="ri--whatsapp-fill"></i>
                         </button>`;
                 }
             },
        ],
    });
}); 
window.onload = function () {
    let familia = document.getElementById("family_id").value
    getpets(familia)
    first(type)
}

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

function first(value) {
    document.getElementById("adm").style.display = "none";
    document.getElementById("area").style.display = "none";
    document.getElementById("motivo").style.display = "none";
    document.getElementById("mvz").style.display = "none";
    document.getElementById("consultorio").style.display = "none";
    document.getElementById("salida").style.display = "none";

    switch (value) {
        case 1:
            document.getElementById("motivo").style.display = "block";
            document.getElementById("mvz").style.display = "block";
            document.getElementById("consultorio").style.display = "block";
            break;
        case 2:
            document.getElementById("adm").style.display = "block";
            document.getElementById("area").style.display = "block";
            document.getElementById("motivo").style.display = "block";
            document.getElementById("mvz").style.display = "block";
            document.getElementById("salida").style.display = "block";
            break;
        case 3:
            document.getElementById("mvz").style.display = "block";
            document.getElementById("salida").style.display = "block";
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

$(document).ready(function () {
    $('#family_id').select2({
        placeholder: 'Buscar Familia',
        width: 'resolve'
    });
    $('#pet_id').select2({
        placeholder: 'Buscar Mascota',
        width: 'resolve'
    });
    $('#service_id').select2({
        placeholder: 'Buscar Servicio',
        width: 'resolve'
    });
});

async function NewEntry() {
    event.preventDefault();
    let url = route('groomings.store');
    let form = new FormData(document.getElementById("NewService"));
    let pet = await fetch(url, { method: "POST", body: form });
    let resp = await pet.json();

    if (pet.ok) {
        Swal.fire({
            icon: "success",
            title: "Se registraron los servicios con exito",
            timer: 7000,
            showConfirmButton: true
        })
        table.ajax.reload();
        $('#service_id').val('').trigger('change')
        $('#notes').val('')
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
            {
                data: null,
                render: function (data) {
                    return `
                        <button type="button" class="btn btn-sm text-primary" onclick="showAlertWithCallback(() => deleteGrooming(${data.id}, table));">
                            <i class="fas fa-trash"></i>
                        </button>`;
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

async function generate(event) {
    event.preventDefault();
    Swal.fire({
        title: 'Procesando...',
        text: 'Por favor espera mientras procesamos la solicitud.',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    try {
        let url3 = route('grooming.pay', Reception_Id);
        let pet3 = await fetch(url3, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
            },
        });
        let resp3 = await pet3.json();

        Swal.close();

        Swal.fire({
            icon: "success",
            title: "El folio para pagar el servicio es " + resp3,
            timer: 27000,
            showConfirmButton: true
        }).then(() => {
            window.location.href = route('grooming.sign', Reception_Id);
        });

    } catch (error) {
        console.error("Error:", error);
        Swal.fire({
            icon: "error",
            title: "Error inesperado",
            text: "Por favor, intenta nuevamente."
        });
    } finally {
        Swal.close();
    }
}
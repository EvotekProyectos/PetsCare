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
    $('#service_id_vaccine').select2({
        placeholder: 'Buscar Servicio',
        width: 'resolve'
    });
});

async function Add() {
    event.preventDefault();


    let url = route('groomings.store');
    let form1 = new FormData(document.getElementById("NewGroomingServ"));
    let form2 = new FormData(document.getElementById("NewVaccineServ"));

    let successCount = 0;
    let errorMessages = [];
    let serviceField = document.getElementById("service_id").value;
    let vaccineField = document.getElementById("service_id_vaccine").value;

    if (serviceField) {
        await sendForm(form1);
    }
    if (vaccineField) {
        await sendForm(form2);
    }

    async function sendForm(formData) {

        let response = await fetch(url, { method: "POST", body: formData });
        let respData = await response.json();

        if (response.ok) {
            successCount++;
        } else {
            errorMessages.push(respData.message || "Ocurrió un error en el registro.");
        }


        if (successCount > 0) {
            Swal.fire({
                icon: "success",
                title: "Se registraron los servicios con éxito",
                timer: 5000,
                showConfirmButton: true
            });

            table.ajax.reload();
            $('#service_id').val('').trigger('change');
            $('#service_id_vaccine').val('').trigger('change');
        }

        if (errorMessages.length > 0) {
            Swal.fire({
                icon: "error",
                title: "Error al guardar",
                text: errorMessages.join("\n"),
            });
        }

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
            // {
            //     data: 'notes',
            // },
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
        let url = route('general-groomings.store');
        let form = new FormData(document.getElementById("NewGrooming"));

        let response = await fetch(url, { method: "POST", body: form });
        if (!response.ok) throw new Error("Error al crear el servicio");


        let url3 = route('grooming.pay', Reception_Id);
        let pet3 = await fetch(url3, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
            },
        });
        if (!pet3.ok) throw new Error("Error al obtener el folio de pago");
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
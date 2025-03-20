$(document).ready(function () {
    table = $('#table').DataTable({
        ajax: route('assignment.delivery'),
        responsive: true,
        order: [0, 'desc'],
        columns: [
            {
                data: 'entry_date',
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
                    return data.vet ? data.vet.name : '';
                }
            },
            {
                data: null,
                render: function (data) {
                    if (data.status) {
                        return `<span style="background-color: ${data.status.color}; color: black; padding: 5px; border-radius: 5px;">
                                    ${data.status.name}
                                </span>`;
                    }
                    return 'No status';
                }

            },
            {
                data: 'exit_date',
            },
            {
                data: null,
                render: function (data) {
                    let buttons = '';

                    buttons += `  <a type="button" onclick="OpenDetails(${data.id})" class="btn btn-sm text-primary">
                 <span class="mdi--eye"></span> </a>`;

                    if (data.grooming?.delivery_service == 1) {
                        buttons += `
                        <a type="button" href="${route('pdf.delivery', data.id)}" target="_blank" class="btn btn-sm text-danger">
                            <span class="mdi--house-export-outline"></span> 
                        </a>`;
                    }

                    return buttons;
                }
            },
        ],
    });
});

async function OpenDetails(id) {
    try {
        let response = await fetch(route("delivery.data", id));
        
        if (!response.ok) {
            throw new Error(`Error al obtener los datos: ${response.statusText}`);
        }

        let data = await response.json();

        if (data.entry_date) {
            let formattedDate = formatDateTime(data.entry_date);
            $("#collect").text(formattedDate);
        } else {
            $("#collect").text("Fecha no disponible");
        }
        if (data.exit_date) {
            let formattedDate = formatDateTime(data.exit_date);
            $("#deliver").text(formattedDate);
        } else {
            $("#deliver").text("Fecha no disponible");
        }

        $("#family").text(data.family?.name);
        $("#phone").text(data.family?.phone);
        $("#address").text(data.family?.address);
        $("#references").text(data.grooming?.delivery_references || "");
        $("#pet").text(data.pet?.name);
        $("#specie").text(data.pet?.specie || "");
        $("#raza").text(data.pet?.raza || "");
        $("#genre").text(data.pet?.genre?.name || "");
        $("#classification").text(data.pet?.pet_classification?.name || "");
        $("#description").text(data.pet?.physic_descrip || "");

        $('#ModalDetails').modal('show');

    } catch (error) {
        console.error("Error en OpenDetails:", error);
        Swal.fire({
            icon: "error",
            title: "Error al obtener los detalles",
            text: "No se pudo cargar la información. Intenta de nuevo."
        });
    }
}

function closeModal(){
    $('#ModalDetails').modal('hide');
}


function formatDateTime(dateString) {
    let date = new Date(dateString);

    let day = String(date.getDate()).padStart(2, "0");
    let month = String(date.getMonth() + 1).padStart(2, "0"); 
    let year = date.getFullYear();

    let hours = date.getHours();
    let minutes = String(date.getMinutes()).padStart(2, "0");

    let ampm = hours >= 12 ? "PM" : "AM";
    hours = hours % 12 || 12; // Convierte 0 en 12 para formato AM/PM

    return `${day}-${month}-${year} ${hours}:${minutes} ${ampm}`;
}


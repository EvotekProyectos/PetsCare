function fetchAndRenderData() {
    $.ajax({
        url: route('red-sheets.recap', Reception_Id),
        method: 'GET',
        success: function (response) {
            // Pass response data to the function for rendering
            renderData(response.data);
        },
        error: function (error) {
            console.error("Error fetching data:", error);
        }
    });
}
window.onload = function () {
    fetchAndRenderData()
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
        // table.ajax.reload();
        fetchAndRenderData()
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
                render: function (data) {
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
        followtable.ajax.reload();
        CloseFollowUp()
    } else {
        let resp = await pet.json();
        Swal.fire({
            icon: "error",
            body: resp
        })
    }
}

async function OpenSurgeries() {
    // document.getElementById("reception_id_followup").value = Reception_Id;
    $('#ModalSurgeries').modal('show');
}

async function AddSurgery() {
    event.preventDefault();
    let url = route('surgeries.store');
    let form = new FormData(document.getElementById("NewSurgery"));
    let pet = await fetch(url, { method: "POST", body: form });
    let resp = await pet.json();

    if (pet.ok) {
        Swal.fire({
            icon: "success",
            title: "Se guardo la cirugia con exito",
            timer: 7000,
            showConfirmButton: true
        })
        surgerytable.ajax.reload();
        CloseSurgeries()
    } else {
        let resp = await pet.json();
        Swal.fire({
            icon: "error",
            body: resp
        })
    }
}

function CloseSurgeries() {
    document.getElementById("surgery_date").value = "";
    document.getElementById("surgery_type_id").value = "";
    document.getElementById("surgery_description").value = "";
    document.getElementById("preanesthetic").value = "";
    document.getElementById("anesthetic").value = "";
    document.getElementById("other_medicines").value = "";
    document.getElementById("treatment").value = "";
    document.getElementById("observations_surgery").value = "";
    document.getElementById("complications").value = "";
    $('#ModalSurgeries').modal('hide');
}



// function renderData(data) {
//     // Group data by day_count
//     const groupedData = data.reduce((acc, item) => {
//         acc[item.day_count] = acc[item.day_count] || [];
//         acc[item.day_count].push(item);
//         return acc;
//     }, {});

//     // Clear existing data in your display table
//     $('#table-container').empty(); // Assuming an element with id `table-container`

//     // Iterate over grouped data and create HTML
//     for (const [dayCount, entries] of Object.entries(groupedData)) {
//         // Create a section header for each day_count
//         const dayHeader = `<h5>Día ${dayCount}</h5>`;
//         $('#table-container').append(dayHeader);

//         // Create a table for each day_count
//         const table = `
//             <table class="table table-striped table-hover table-red" style="background-color: red">
//                 <thead>
//                     <tr>
//                         <th>Tipo</th>
//                         <th>Nombre</th>
//                         <th>Observaciones</th>
//                         <th>M.V.Z.</th>
//                         <th>Precio</th>
//                     </tr>
//                 </thead>
//                 <tbody>
//                     ${entries.map(entry => renderEntryRow(entry)).join('')}
//                 </tbody>
//             </table>
//         `;
//         $('#table-container').append(table);
//     }
// }

function renderData(data) {
    // Group data by day_count
    const groupedData = data.reduce((acc, item) => {
        acc[item.day_count] = acc[item.day_count] || [];
        acc[item.day_count].push(item);
        return acc;
    }, {});

    // Clear existing data in your display table
    $('#table-container').empty(); // Assuming an element with id `table-container`

    // Iterate over grouped data and create HTML
    for (const [dayCount, entries] of Object.entries(groupedData)) {
        // Create a section header for each day_count
        const dayHeader = `<h5>Día ${dayCount}</h5>`;
        $('#table-container').append(dayHeader);

        // Calculate the total for the current day_count group
        let total = 0;
        entries.forEach(entry => {
            if (entry.lab && entry.lab.price) {
                total += parseFloat(entry.lab.price);
            }
            if (entry.service && entry.service.price) {
                total += parseFloat(entry.service.price);
            }
            if (entry.imaging && entry.imaging.price) {
                total += parseFloat(entry.imaging.price);
            }
        });

        // Create a table for each day_count
        const table = `
            <table class="table table-striped table-hover" style="background-color: #ffdddd;">
                <thead>
                    <tr>
                        <th>Tipo</th>
                        <th>Nombre</th>
                        <th>Observaciones</th>
                        <th>M.V.Z.</th>
                        <th>Precio</th>
                    </tr>
                </thead>
                <tbody>
                    ${entries.map(entry => renderEntryRow(entry)).join('')}
                    <!-- Row for total price -->
                    <tr>
                        <td colspan="4"><strong>SubTotal de día</strong></td>
                        <td><strong>$${total.toFixed(2)}</strong></td>
                    </tr>
                </tbody>
            </table>
        `;
        $('#table-container').append(table);
    }
}
// Function to create rows for each entry based on available data
function renderEntryRow(entry) {
    let rows = '';

    // Check and add lab entry row if exists
    if (entry.lab) {
        rows += `
            <tr>
                <td>Laboratorio</td>
                <td>${entry.lab.name}</td>
                <td>${entry.observations || ''}</td>
                <td>${entry.vet ? entry.vet.name : ''}</td>
                <td>$${entry.lab.price}</td>
            </tr>
        `;
    }

    // Check and add imaging entry row if exists
    if (entry.imaging) {
        rows += `
            <tr>
                <td>Imagenologia</td>
                <td>${entry.imaging.name}</td>
                <td>${entry.observations || ''}</td>
                <td>${entry.vet ? entry.vet.name : ''}</td>
                <td>$${entry.imaging.price}</td>
            </tr>
        `;
    }

    // Check and add service entry row if exists
    if (entry.service) {
        rows += `
            <tr>
                <td>Servicio</td>
                <td>${entry.service.name}</td>
                <td>${entry.observations || ''}</td>
                <td>${entry.vet ? entry.vet.name : ''}</td>
                <td>$${entry.service.price}</td>
            </tr>
        `;
    }

    return rows;
}





var followtable = undefined;
$(document).ready(function () {
    followtable = $('#follow-ups').DataTable({
        ajax: route('followup.entry', Reception_Id),
        responsive: true,
        order: [0, 'desc'],
        columns: [
            {
                data: 'created_at',
                render: function (data) {
                    if (data) {
                        let date = new Date(data);
                        let formattedDate = date.toLocaleDateString('en-US', {
                            year: 'numeric',
                            month: 'short',
                            day: 'numeric'
                        });
                        return `${formattedDate} `;
                    }
                    return '';
                }
            },

            {
                data: 'time',
            },

            {
                data: 'details',
            },

            {
                data: 'temperature'
            },
            {
                data: 'systolic',
            },
            {
                data: 'diastolic',
            },
            {
                data: 'average',
            },
            {
                data: 'glycemia_level',
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

var surgerytable = undefined;
$(document).ready(function () {
    surgerytable = $('#surgeries').DataTable({
        ajax: route('surgeries.entry', Reception_Id),
        responsive: true,
        order: [0, 'desc'],
        columns: [
            {
                data: 'surgery_date',
                render: function (data) {
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
                data: null,
                render: function (data) {
                    return data.service ? data.service.name : '';
                }
            },

            {
                data: 'surgery_description'
            },
            {
                data: 'preanesthetic'
            },
            {
                data: 'anesthetic',
            },
            {
                data: 'other_medicines',
            },
            {
                data: 'treatment',
            },
            {
                data: 'observations',
            },
            {
                data: 'complications',
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

async function Transfer() {
    event.preventDefault();
    Swal.fire({
        title: 'Vas a trasladar a este paciente',
        icon: "question",
        html: `Decide cúal es el nuevo tipo de admisión`,
        input: 'select',
        inputOptions: getAdm(admisiones),
        inputPlaceholder: 'Selecciona la admisión',
        showCancelButton: true,
        confirmButtonText: 'Asignar',
        cancelButtonText: 'Cancelar',
        inputValidator: (value) => {
            return new Promise((resolve) => {
                if (value === '') {
                    resolve('Debes seleccionar una admisión');
                } else {
                    resolve();
                }
            });
        }
    }).then(async (result) => {
        if (result.isConfirmed) {
            const form = new FormData();
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            form.append("_token", token);
            form.append("_method", "PUT"); 
            form.append("admission_type_id", result.value); 

            let url = route('reception.transfer', Reception_Id); 
            let pet = await fetch(url, {
                method: "POST", 
                body: form
            });
            if (pet.ok) {
                window.location.reload();

            }
        }

    });
}

function getAdm(AdminssionData) {
    return AdminssionData.reduce((options, Adminssion) => {
        options[Adminssion.id] = Adminssion.name;

        return options;
    }, {});
}
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

function fetchAndRenderData() {
    $.ajax({
        url: route('red-sheets.recap', Reception_Id),
        method: 'GET',
        success: function (response) {
            const Data = response.data.flat(); 
            const normalizedData = normalizeData(Data); 
            renderData(normalizedData); 
        },
        error: function (error) {
            console.error("Error fetching data:", error);
        }
    });
}
function normalizeData(data) {
    return data.map(entry => ({
        ...entry,
        lab: entry.lab || null,
        imaging: entry.imaging || null,
        service: entry.service || null,
        surgery: entry.surgery || null,
        observations: entry.observations || 'Sin observaciones',
        vet: entry.vet || { name: 'Desconocido' },
    }));
}

function renderData(data) {
    
    const groupedData = data.reduce((acc, item) => {
        acc[item.day_count] = acc[item.day_count] || [];
        acc[item.day_count].push(item);
        return acc;
    }, {});

    let grandTotal = 0;
    
    $('#table-container').empty(); 

    for (const [dayCount, entries] of Object.entries(groupedData)) {
        const dayHeader = `<h5>Día ${dayCount}</h5>`;
        $('#table-container').append(dayHeader);

        let total = 0;
        entries.forEach(entry => {
            if (entry.lab && entry.lab.PRECIO) {
                total += parseFloat(entry.lab.PRECIO);
            }
            if (entry.service && entry.service.PRECIO) {
                total += parseFloat(entry.service.PRECIO);
            }
            if (entry.imaging && entry.imaging.PRECIO) {
                total += parseFloat(entry.imaging.PRECIO);
            }
            if (entry.surgery && entry.surgery.PRECIO) {
                total += parseFloat(entry.surgery.PRECIO);
            }
        });
        grandTotal += total;

        
        const table = `
            <table class="table table-striped table-hover responsive w-100" style="background-color: #2596be;">
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
    const totalFinalSection = `
        <div class="total-final" style="margin-top: 20px; text-align: right; font-weight: bold;">
            <h4>Total Final: $${grandTotal.toFixed(2)}</h4>
        </div>
    `;
    $('#table-container').append(totalFinalSection);
}


function renderEntryRow(entry) {
    let rows = '';

    if (entry.lab) {
        rows += `
            <tr>
                <td>Laboratorio</td>
                <td>${entry.laboratory.NOMBRE}</td>
                <td>${entry.observations || ''}</td>
                <td>${entry.vet ? entry.vet.name : ''}</td>
                <td>$${entry.lab.PRECIO}</td>
            </tr>
        `;
    }

    if (entry.imaging) {
        rows += `
            <tr>
                <td>Imagenologia</td>
                <td>${entry.img.NOMBRE}</td>
                <td>${entry.observations || ''}</td>
                <td>${entry.vet ? entry.vet.name : ''}</td>
                <td>$${entry.imaging.PRECIO}</td>
            </tr>
        `;
    }

    if (entry.service) {
        rows += `
            <tr>
                <td>Servicio</td>
                <td>${entry.serv.NOMBRE}</td>
                <td>${entry.observations || ''}</td>
                <td>${entry.vet ? entry.vet.name : ''}</td>
                <td>$${entry.service.PRECIO}</td>
            </tr>
        `;
    }
    if (entry.surgery) {
        rows += `
            <tr>
                <td>Cirugia</td>
                <td>${entry.surg.NOMBRE}</td>
                <td>${entry.observations || ''}</td>
                <td>${entry.vet ? entry.vet.name : ''}</td>
                <td>$${entry.surgery.PRECIO}</td>
            </tr>
        `;
    }

    return rows;
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



async function OpenFollowUps() {
    document.getElementById("reception_id_followup").value = Reception_Id;
    console.log(Reception_Id);
    
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

// async function OpenPrescription(petId, receptionId) {
//     const result = await Swal.fire({
//         title: '¿Dar de alta a este paciente?',
//         text: "Confirma su atención",
//         icon: 'question',
//         showCancelButton: true,
//         confirmButtonColor: '#3085d6',
//         cancelButtonColor: '#d33',
//         confirmButtonText: 'Sí, dar alta.',
//         cancelButtonText: 'No, regresar.'
//     });

//     if (result.isConfirmed) {
//             const response = await fetch(`/redSheet/discharge`, {
//                 method: 'POST',
//                 headers: {
//                     'Content-Type': 'application/json',
//                     'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
//                 },
//                 body: JSON.stringify({ petId, receptionId })
//             });
//             const data = await response.json();
//             if (response.ok) {
//                 Swal.fire(
//                     'Paciente dado de alta',
//                     'Se ha registrado la salida.'
//                 );
//                 window.location.href = `/prescriptions/create/${petId}`;
//             } else {
//                 Swal.fire('Error', data.message || 'No se pudo dar de alta.', 'error');
//             }
//     }
// }

async function OpenPrescription(petId, receptionId) {
    const result = await Swal.fire({
        title: '¿Dar de alta a este paciente?',
        text: "Confirma su atención",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, dar alta.',
        cancelButtonText: 'No, regresar.'
    });

    if (result.isConfirmed) {
        const { value: selectedType } = await Swal.fire({
            title: 'Selecciona el tipo de alta',
            html: `
                <select id="dischargeType" class="swal2-input">
                    <option value="" disabled selected>Selecciona una opción</option>
                    <option value="Alta normal" style="color: #2BEA91;">Alta normal</option>
                    <option value="Alta voluntaria" style="color: #2DAAF8;">Alta voluntaria</option>
                    <option value="Alta por fallecimiento" style="color: #F862AA;">Alta por fallecimiento</option>
                </select>
            `,
            focusConfirm: false,
            preConfirm: () => {
                return document.getElementById('dischargeType').value;
            }
        });

        if (!selectedType) {
            Swal.fire('Error', 'Debes seleccionar un tipo de alta.', 'error');
            return;
        }

        if (selectedType === "Alta normal") {
            const response = await fetch(`/redSheet/discharge`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ petId, receptionId })
            });
            const data = await response.json();
            if (response.ok) {
                Swal.fire(
                    'Paciente dado de alta',
                    'Se ha registrado la salida.'
                );
                window.location.href = `/prescriptions/create/${petId}`;
            } else {
                Swal.fire('Error', data.message || 'No se pudo dar de alta.', 'error');
            }
        } else if (selectedType === "Alta voluntaria") {
            const nameFamily = $("#name_family").val();
            const reason = $("#reason").val();
             window.location.href = `/alta-voluntaria/${receptionId}`;
            } else if (selectedType === "Alta por fallecimiento") {
                Swal.fire('Alta por fallecimiento', 'El proceso de alta por fallecimiento se ha registrado.');
            }
        }
        
}
 async function OpenSurgeries() {
     const receptionId = document.getElementById("reception_id_followup").value;

         const url = route('surgery.checkRequirements', receptionId) ; 
         const response = await fetch(url);
         const data = await response.json();

         if (data.status === 'ok') {
             $('#ModalSurgeries').modal('show');
         } else {
             Swal.fire({
                 icon: 'error',
                 title: 'Error',
                 text: data.message || 'Ocurrió un error al verificar los requisitos.',
             });
 }
 }

// async function OpenSurgeries() {
//     const receptionId = document.getElementById("reception_id_followup").value;

//     const url = route('surgery.checkRequirements', receptionId); 
//     const response = await fetch(url);
//     const data = await response.json();

//     if (data.status === 'ok') {
       
//         const authorizationUrl = route('surgery.auth', receptionId);
//         window.location.href = authorizationUrl;

//         setTimeout(() => {
//             $('#ModalSurgeries').modal('show');
//         }, 1000); 
//     } else {
//         Swal.fire({
//             icon: 'error',
//             title: 'Error',
//             text: data.message || 'Ocurrió un error al verificar los requisitos.',
//         });
//     }
// }


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
        fetchAndRenderData()
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
    document.getElementById("date").value = "";
    document.getElementById("product_type_id").value = "";
    document.getElementById("observations").value = "";
    $('#ModalSurgeries').modal('hide');
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

// var surgerytable = undefined;
// $(document).ready(function () {
//     surgerytable = $('#surgeries').DataTable({
//         ajax: route('surgeries.entry', Reception_Id),
//         responsive: true,
//         order: [0, 'desc'],
//         columns: [
//             {
//                 data: 'date',
//                 render: function (data) {
//                     if (data) {
//                         let date = new Date(data);
//                         let formattedDate = date.toLocaleDateString('en-US', {
//                             year: 'numeric',
//                             month: 'short',
//                             day: 'numeric'
//                         });
//                         let formattedTime = date.toLocaleTimeString('en-US', {
//                             hour: '2-digit',
//                             minute: '2-digit'
//                         });
//                         return `${formattedDate} ${formattedTime}`;
//                     }
//                     return '';
//                 }
//             },

//             {
//                 data: null,
//                 render: function (data) {
//                     return data.service ? data.service.name : '';
//                 }
//             },
//             {
//                 data: 'observations',
//             },
//             {
//                 data: null,
//                 render: function (data) {
//                     return data.vet ? data.vet.name : '';
//                 }
//             },
//         ],
//     });
// });

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
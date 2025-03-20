window.onload = function () {
    if (Pic_id !== null) {
        let fileRoute = Pic_route.startsWith('/') ? Pic_route.substring(1) : Pic_route;
        $("#preview").attr("src", ruta + fileRoute);
    }
    else {
        $("#preview").attr("src", imgDefault);
    }


}

function uncheckAllRadioButtons() {
    document.querySelectorAll('input[type="radio"]').forEach(radio => {
        radio.checked = false;
    });
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


    try {
        let resp = await pet.json();

        if (pet.ok) {
            Swal.fire({
                icon: "success",
                title: "Se guardó el seguimiento con éxito",
                timer: 7000,
                showConfirmButton: true
            });
            tableCritics.ajax.reload();
            CloseCritics()
        } else {
            if (resp.errors) {
                let errorMessages = '';
                for (let field in resp.errors) {
                    errorMessages += `<p><strong>${field}:</strong> ${resp.errors[field].join(', ')}</p>`;
                }

                Swal.fire({
                    icon: "error",
                    title: "Errores en el formulario",
                    html: errorMessages
                });
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Error desconocido",
                    text: "Hubo un problema al guardar.",
                });
            }
        }
    } catch (error) {
        Swal.fire({
            icon: "warning",
            title: "Faltan datos",
            text: "Debes llenar todo el pase de guardia antes de continuar.",
        });
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
    uncheckAllRadioButtons()
    $('#ModalCritics').modal('hide');
}

var tableCritics = undefined;
$(document).ready(function () {
    tableCritics = $('#critics_table').DataTable({
        ajax: route('followups-critics.list', Reception_Id),
        responsive: true,
        order: [0, 'desc'],
        columns: [
            {
                data: 'created_at',
                render: function (data, type, row) {
                    return moment(data).format('DD/MM/YYYY hh:mm A');
                }
            },

            // {
            //     data: 'pet_status',
            // },

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
                data: 'throwup',
                render: function (data, type, row) {
                    const boolean = data === 0 ? 'Sí' : 'No';
                    const detail = row.throwup_detail ? `<small>, ${row.throwup_detail}</small>` : '';
                    return `${boolean}${detail}`;
                }
            },
            {
                data: 'defecate',
                render: function (data, type, row) {
                    const boolean = data === 0 ? 'Sí' : 'No';
                    const detail = row.defecate_detail ? `<small>, ${row.defecate_detail}</small>` : '';
                    return `${boolean}${detail}`;
                }
            },
            {
                data: 'orino',
                render: function (data, type, row) {
                    const boolean = data === 0 ? 'Sí' : 'No';
                    const detail = row.orino_detail ? `<small>, ${row.orino_detail}</small>` : '';
                    return `${boolean}${detail}`;
                }
            },
            {
                data: 'eat',
                render: function (data, type, row) {
                    const boolean = data === 0 ? 'Sí' : 'No';
                    const detail = row.eat_detail ? `<small>, ${row.eat_detail}</small>` : '';
                    return `${boolean}${detail}`;
                }
            },
            {
                data: 'infusions',
                render: function (data, type, row) {
                    const boolean = data === 0 ? 'Sí' : 'No';
                    const detail = row.infusions_detail ? `<small>, ${row.infusions_detail}</small>` : '';
                    return `${boolean}${detail}`;
                }
            },
            {
                data: 'terapeutic',
                render: function (data, type, row) {
                    const boolean = data === 0 ? 'Sí' : 'No';
                    const detail = row.terapeutic_detail ? `<small>, ${row.terapeutic_detail}</small>` : '';
                    return `${boolean}${detail}`;
                }
            },
            {
                data: 'imaging',
                render: function (data, type, row) {
                    const boolean = data === 0 ? 'Sí' : 'No';
                    const detail = row.imaging_detail ? `<small>, ${row.imaging_detail}</small>` : '';
                    return `${boolean}${detail}`;
                }
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
                       
                        <button type="button" class="btn btn-sm text-primary" onclick="showAlertWithCallback(() => deleteFollowUpCritic(${data.id}, tableCritics));">
                            <i class="fas fa-trash"></i>
                        </button>`;
                }
            },

        ],
    });
});

async function OpenInterns() {
    document.getElementById("reception_id_interns").value = Reception_Id;

    $('#ModalInterns').modal('show');
}

async function AddInterns() {
    event.preventDefault();

    let url = route('followup-interns.store');
    let form = new FormData(document.getElementById("NewIntern"));
    let pet = await fetch(url, { method: "POST", body: form });


    try {
        let resp = await pet.json();

        if (pet.ok) {
            Swal.fire({
                icon: "success",
                title: "Se guardó el seguimiento con éxito",
                timer: 7000,
                showConfirmButton: true
            });
            tableInterns.ajax.reload();
            CloseInterns()
        } else {
            if (resp.errors) {
                let errorMessages = '';
                for (let field in resp.errors) {
                    errorMessages += `<p><strong>${field}:</strong> ${resp.errors[field].join(', ')}</p>`;
                }

                Swal.fire({
                    icon: "error",
                    title: "Errores en el formulario",
                    html: errorMessages
                });
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Error desconocido",
                    text: "Hubo un problema al guardar.",
                });
            }
        }
    } catch (error) {
        Swal.fire({
            icon: "warning",
            title: "Faltan datos",
            text: "Debes llenar todo el pase de guardia antes de continuar.",
        });
    }
}

function CloseInterns() {
    document.getElementById("which_alterations_interns").value = "";
    document.getElementById("which_therapeutic_interns").value = "";
    document.getElementById("quantity_vomiting_interns").value = "";
    document.getElementById("quantity_defecation_interns").value = "";
    document.getElementById("quantity_urine_interns").value = "";
    document.getElementById("defecate_detail_critics").value = "";
    document.getElementById("orino_detail_critics").value = "";
    document.getElementById("type_feeding_interns").value = "";
    document.getElementById("observations_ultrasounds_interns").value = "";
    document.getElementById("pendings_interns").value = "";
    uncheckAllRadioButtons()
    $('#ModalInterns').modal('hide');
}




var tableInterns = undefined;
$(document).ready(function () {
    tableInterns = $('#interns_table').DataTable({
        ajax: route('followup-interns.list', Reception_Id),
        responsive: true,
        order: [0, 'desc'],
        columns: [
            {
                data: 'created_at',
                render: function (data, type, row) {
                    return moment(data).format('DD/MM/YYYY hh:mm A');
                }
            },

            {
                data: 'alterations',
                render: function (data, type, row) {
                    const boolean = data === 0 ? 'Sí' : 'No';
                    const detail = row.which_alterations ? `<small>, ${row.which_alterations}</small>` : '';
                    return `${boolean}${detail}`;
                }
            },
            {
                data: 'therapeutic',
                render: function (data, type, row) {
                    const boolean = data === 0 ? 'Sí' : 'No';
                    const detail = row.which_therapeutic ? `<small>, ${row.which_therapeutic}</small>` : '';
                    return `${boolean}${detail}`;
                }
            },
            {
                data: 'vomiting',
                render: function (data, type, row) {
                    const boolean = data === 0 ? 'Sí' : 'No';
                    const detail = row.quantity_vomiting ? `<small>, ${row.quantity_vomiting}</small>` : '';
                    return `${boolean}${detail}`;
                }
            },
            {
                data: 'defecation',
                render: function (data, type, row) {
                    const boolean = data === 0 ? 'Sí' : 'No';
                    const detail = row.quantity_defecation ? `<small>, ${row.quantity_defecation}</small>` : '';
                    return `${boolean}${detail}`;
                }
            },
            {
                data: 'urine',
                render: function (data, type, row) {
                    const boolean = data === 0 ? 'Sí' : 'No';
                    const detail = row.quantity_urine ? `<small>, ${row.quantity_urine}</small>` : '';
                    return `${boolean}${detail}`;
                }
            },
            {
                data: 'feeding',
                render: function (data, type, row) {
                    const boolean = data === 0 ? 'Sí' : 'No';
                    const detail = row.type_feeding ? `<small>, ${row.type_feeding}</small>` : '';
                    return `${boolean}${detail}`;
                }
            },
            {
                data: 'ultrasounds',
                render: function (data, type, row) {
                    const boolean = data === 0 ? 'Sí' : 'No';
                    const detail = row.observations_ultrasounds ? `<small>, ${row.observations_ultrasounds}</small>` : '';
                    return `${boolean}${detail}`;
                }
            },
            {
                data: 'pendings',
            },
            // {
            //     data: 'observations',
            // },
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
                        <a type="button" href="${route('followup-interns.edit', data.id)}" class="btn btn-sm text-primary">
                            <i class="fas fa-edit"></i>
                        </a>
                       
                        <button type="button" class="btn btn-sm text-primary" onclick="showAlertWithCallback(() => deleteFollowUpIntern(${data.id}, tableInterns));">
                            <i class="fas fa-trash"></i>
                        </button>`;
                }
            },

        ],
    });
});

async function OpenSurgicals() {
    document.getElementById("reception_id_surgicals").value = Reception_Id;

    $('#ModalSurgicals').modal('show');
}


async function AddSurgicals() {
    event.preventDefault();

    let url = route('followup-surgicals.store');
    let form = new FormData(document.getElementById("NewSurgical"));
    let pet = await fetch(url, { method: "POST", body: form });


    try {
        let resp = await pet.json();

        if (pet.ok) {
            Swal.fire({
                icon: "success",
                title: "Se guardó el seguimiento con éxito",
                timer: 7000,
                showConfirmButton: true
            });
            tableSurgicals.ajax.reload();
            CloseSurgicals();
        } else {
            if (resp.errors) {
                let errorMessages = '';
                for (let field in resp.errors) {
                    errorMessages += `<p><strong>${field}:</strong> ${resp.errors[field].join(', ')}</p>`;
                }

                Swal.fire({
                    icon: "error",
                    title: "Errores en el formulario",
                    html: errorMessages
                });
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Error desconocido",
                    text: "Hubo un problema al guardar.",
                });
            }
        }
    } catch (error) {
        Swal.fire({
            icon: "warning",
            title: "Faltan datos",
            text: "Debes llenar todo el pase de guardia antes de continuar.",
        });
    }
}




function CloseSurgicals() {
    document.getElementById("which_alterations_surgicals").value = "";
    document.getElementById("which_therapeutic_surgicals").value = "";
    document.getElementById("quantity_vomiting_surgicals").value = "";
    document.getElementById("quantity_defecation_surgicals").value = "";
    document.getElementById("quantity_urine_surgicals").value = "";
    document.getElementById("type_feeding_surgicals").value = "";
    document.getElementById("pendings_surgicals").value = "";
    document.getElementById("clean_observations_surgicals").value = "";
    document.getElementById("secretion_observations_surgicals").value = "";
    document.getElementById("quantity_drainage_surgicals").value = "";
    document.getElementById("type_blocked_surgicals").value = "";
    document.getElementById("type_time_infusions_surgicals").value = "";
    document.getElementById("which_alterations_surgery_surgicals").value = "";
    uncheckAllRadioButtons()
    $('#ModalSurgicals').modal('hide');
}

var tableSurgicals = undefined;
$(document).ready(function () {
    tableSurgicals = $('#surgicals_table').DataTable({
        ajax: route('followup-surgicals.list', Reception_Id),
        responsive: true,
        order: [0, 'desc'],
        columns: [
            {
                data: 'created_at',
                render: function (data, type, row) {
                    return moment(data).format('DD/MM/YYYY hh:mm A');
                }
            },

            {
                data: 'alterations',
                render: function (data, type, row) {
                    const boolean = data === 0 ? 'Sí' : 'No';
                    const detail = row.which_alterations ? `<small>, ${row.which_alterations}</small>` : '';
                    return `${boolean}${detail}`;
                }
            },
            {
                data: 'therapeutic',
                render: function (data, type, row) {
                    const boolean = data === 0 ? 'Sí' : 'No';
                    const detail = row.which_therapeutic ? `<small>, ${row.which_therapeutic}</small>` : '';
                    return `${boolean}${detail}`;
                }
            },
            {
                data: 'vomiting',
                render: function (data, type, row) {
                    const boolean = data === 0 ? 'Sí' : 'No';
                    const detail = row.quantity_vomiting ? `<small>, ${row.quantity_vomiting}</small>` : '';
                    return `${boolean}${detail}`;
                }
            },
            {
                data: 'defecation',
                render: function (data, type, row) {
                    const boolean = data === 0 ? 'Sí' : 'No';
                    const detail = row.quantity_defecation ? `<small>, ${row.quantity_defecation}</small>` : '';
                    return `${boolean}${detail}`;
                }
            },
            {
                data: 'urine',
                render: function (data, type, row) {
                    const boolean = data === 0 ? 'Sí' : 'No';
                    const detail = row.quantity_urine ? `<small>, ${row.quantity_urine}</small>` : '';
                    return `${boolean}${detail}`;
                }
            },
            {
                data: 'feeding',
                render: function (data, type, row) {
                    const boolean = data === 0 ? 'Sí' : 'No';
                    const detail = row.type_feeding ? `<small>, ${row.type_feeding}</small>` : '';
                    return `${boolean}${detail}`;
                }
            },
            {
                data: 'pendings',
            },
            {
                data: 'cleaning',
                render: function (data, type, row) {
                    const boolean = data === 0 ? 'Sí' : 'No';
                    const detail = row.clean_observations ? `<small>, ${row.clean_observations}</small>` : '';
                    return `${boolean}${detail}`;
                }
            },
            {
                data: 'secretion',
                render: function (data, type, row) {
                    const boolean = data === 0 ? 'Sí' : 'No';
                    const detail = row.secretion_observations ? `<small>, ${row.secretion_observations}</small>` : '';
                    return `${boolean}${detail}`;
                }
            },
            {
                data: 'drainage',
                render: function (data, type, row) {
                    const boolean = data === 0 ? 'Sí' : 'No';
                    const detail = row.quantity_drainage ? `<small>, ${row.quantity_drainage}</small>` : '';
                    return `${boolean}${detail}`;
                }
            },
            {
                data: 'blockedages',
                render: function (data, type, row) {
                    const boolean = data === 0 ? 'Sí' : 'No';
                    const detail = row.type_blocked ? `<small>, ${row.type_blocked}</small>` : '';
                    return `${boolean}${detail}`;
                }
            },
            {
                data: 'infusions',
                render: function (data, type, row) {
                    const boolean = data === 0 ? 'Sí' : 'No';
                    const detail = row.type_time_infusions ? `<small>, ${row.type_time_infusions}</small>` : '';
                    return `${boolean}${detail}`;
                }
            },
            {
                data: 'alterations_surgery',
                render: function (data, type, row) {
                    const boolean = data === 0 ? 'Sí' : 'No';
                    const detail = row.which_alterations_surgery ? `<small>, ${row.which_alterations_surgery}</small>` : '';
                    return `${boolean}${detail}`;
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
                    return `
                        <a type="button" href="${route('followup-surgicals.edit', data.id)}" class="btn btn-sm text-primary">
                            <i class="fas fa-edit"></i>
                        </a>
                       
                        <button type="button" class="btn btn-sm text-primary" onclick="showAlertWithCallback(() => deleteFollowUpSurgical(${data.id}, tableSurgicals));">
                            <i class="fas fa-trash"></i>
                        </button>`;
                }
            },

        ],
    });
});
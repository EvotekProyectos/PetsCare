window.onload = function () {
    // let fileRoute = Pic_route.startsWith('/') ? Pic_route.substring(1) : Pic_route;

    if (Pic_id !== null) {
        let fileRoute = Pic_route.startsWith('/') ? Pic_route.substring(1) : Pic_route;
        $("#preview").attr("src", ruta + fileRoute);
    }
    else {
        $("#preview").attr("src", imgDefault);
    }

}

$(document).ready(function () {
    $("#lab_type_id").select2({
        theme: "bootstrap-5",
        dropdownParent: $('#ModalLab')
    });
    $("#imaging_type_id").select2({
        theme: "bootstrap-5",
        dropdownParent: $('#ModalImg')
    });
    // $("#product1").select2({
    //     theme: "bootstrap-5",
    //     dropdownParent: $('#ModalCertificate')
    // });
    // $("#product2").select2({
    //     theme: "bootstrap-5",
    //     dropdownParent: $('#ModalCertificate')
    // });
    // $("#product3").select2({
    //     theme: "bootstrap-5",
    //     dropdownParent: $('#ModalCertificate')
    // });
});

async function AddPrescription() {
    event.preventDefault();
    let url = route('prescriptions.store');
    let form = new FormData(document.getElementById("NewPrescription"));
    let pet = await fetch(url, { method: "POST", body: form });
    let resp = await pet.json();

    if (pet.ok) {
        Swal.fire({
            icon: "success",
            title: "Se guardo la receta médica",
            timer: 7000,
            showConfirmButton: true
        })
        let prescription = resp.id;
        window.open(route('prescription.imprimir', prescription), '_blank');
    } else {
        let resp = await pet.json();
        Swal.fire({
            icon: "error",
            body: resp
        })
    }
}

async function EndAppointment() {
    event.preventDefault();
    const dayNextCheck = document.getElementById("day_next_check").value;


    if (!dayNextCheck) {
        Swal.fire({
            icon: 'warning',
            title: 'Campo obligatorio',
            text: 'Por favor, selecciona el próximo control antes de finalizar la consulta.'
        });
        return;
    }


    const result = await Swal.fire({
        title: '¿Ya terminó la consulta?',
        text: "Los datos de la consulta y fórmula médica serán guardados, recuerda incluir el próximo control.",
        icon: 'question',
        input: 'select',
        inputOptions: getServices(services),
        inputPlaceholder: 'Selecciona el tipo de consulta a cobrar',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, finalizar.',
        cancelButtonText: 'No, continuar consulta.',
        inputValidator: (value) => {
            return new Promise((resolve) => {
                if (value === '') {
                    resolve('Debes seleccionar un concepto');
                } else {
                    resolve();
                }
            });
        }
    });


    if (result.isConfirmed) {
        Swal.fire({
            title: 'Procesando...',
            text: 'Por favor espera mientras generamos la receta médica y la orden de pago.',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        try {

            let url = route('appointments.store');
            let form = new FormData(document.getElementById("NewAppointment"));

            let pet = await fetch(url, { method: "POST", body: form });

            if (!pet.ok) {
                throw new Error('Error al guardar la cita');
            }


            let url2 = route('prescriptions.store');
            let form2 = new FormData(document.getElementById("NewPrescription"));
            const dateInput = document.getElementById('day_next_check');
            form2.append('day_next_check', dateInput.value);

            let pet2 = await fetch(url2, { method: "POST", body: form2 });
            let resp2 = await pet2.json();

            if (!pet2.ok) throw new Error('Error al guardar la prescripción');

            let url3 = route('appointment.pay', { id: Reception_Id, concepto: result.value });
            let pet3 = await fetch(url3, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                },
            });
            let resp3 = await pet3.json();

            if (!pet3.ok) throw new Error('Error en la orden de venta');

            // let prescription = resp2.id;
            // window.open(route('prescription.imprimir', prescription), '_blank');

            Swal.close();

            Swal.fire({
                icon: 'success',
                title: 'Consulta finalizada con éxito',
                text: 'El Folio para pagar en caja es ' + resp3,
                timer: 27000
            }).then(() => {
                let prescription = resp2.id;
                window.open(route('prescription.imprimir', prescription), '_blank');
                window.location.href = route('assignment.index');
            });

        } catch (error) {
            console.error(error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Ocurrió un problema al procesar la solicitud.'
            });
        }
    }
}

function getServices(ServiceData) {
    return ServiceData.reduce((options, services) => {
        options[services.ARTICULO_ID] = services.NOMBRE;

        return options;
    }, {});
}

var table = undefined;
$(document).ready(function () {
    table = $('#table').DataTable({
        ajax: route('reception.historial', Pet_Id),
        responsive: true,
        order: [0, 'desc'],
        columns: [
            {
                data: 'entry_date',
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
                    return data.reception_type ? data.reception_type.name : '';
                }
            },
            {
                data: null,
                render: function (data) {
                    return data.reason ? data.reason.name : '';
                }
            },
            {
                data: null,
                render: function (data) {
                    return `
                        <a class="btn btn-sm btn-primary"  title="Ver Detalles" href="#" onclick="Details(${data.reception_type_id}, ${data.id});">
                            <span class="mage--hospital-shield-fill"></span>
                        </a>`;
                }
            },
        ],
    });
});

async function Details(Type, ID) {
    event.preventDefault();
    let actual = Number(document.getElementById("reception_id").value);
    if (ID === actual) {
        Swal.fire({
            icon: "warning",
            title: "Este resgitro es la consulta actual",
            timer: 7000,
            showConfirmButton: true
        })
    } else {
        if (Type === 1) {
            window.open(route('appointment.historic', ID), '_blank');

        }
    }
};

async function Register() {
    event.preventDefault();
    let url = route('vaccine-certificates.store');
    let form = new FormData(document.getElementById("NewInterDeworming"));

    let pet = await fetch(url, { method: "POST", body: form });


}

async function OpenCarnet() {
    document.getElementById("pet_id1").value = Pet_Id;
    document.getElementById("pet_id2").value = Pet_Id;
    document.getElementById("pet_id3").value = Pet_Id;
    $('#ModalCertificate').modal('show');
}

async function Register() {
    event.preventDefault();
    let product1 = document.getElementById("product1").value;
    let product2 = document.getElementById("product2").value;
    let product3 = document.getElementById("product3").value;

    let vaccine_date = document.getElementById("next_application_date1").value;
    let intern_date = document.getElementById("next_application_date2").value;
    let extern_date = document.getElementById("next_application_date3").value;

    let formSent = false;

    if (product1 !== null && product1 !== "") {
        if (!vaccine_date) {
            Swal.fire({
                icon: 'warning',
                title: 'Campo obligatorio',
                text: 'Por favor, selecciona la proxima aplicación de vacuna para guardar los registros.'
            });
            return;
        }
        let url = route('vaccine-certificates.store');
        let form = new FormData(document.getElementById("NewVaccine"));
        form.append('reception_id', Reception_Id);
        let pet = await fetch(url, {
            method: "POST",
            body: form
        });
        if (pet.ok) {
            formSent = true;
        }
    }
    if (product2 !== null && product2 !== "") {
        if (!intern_date) {
            Swal.fire({
                icon: 'warning',
                title: 'Campo obligatorio',
                text: 'Por favor, selecciona la proxima desparacitación interna para guardar los registros.'
            });
            return;
        }
        let url = route('vaccine-certificates.store');
        let form = new FormData(document.getElementById("NewInterDeworming"));
        form.append('reception_id', Reception_Id);
        let pet = await fetch(url, {
            method: "POST",
            body: form
        });
        if (pet.ok) {
            formSent = true;
        }
    }
    if (product3 !== null && product3 !== "") {
        if (!extern_date) {
            Swal.fire({
                icon: 'warning',
                title: 'Campo obligatorio',
                text: 'Por favor, selecciona la proxima desparacitación externa para guardar los registros.'
            });
            return;
        }
        let url = route('vaccine-certificates.store');
        let form = new FormData(document.getElementById("NewExternDeworming"));
        form.append('reception_id', Reception_Id);
        let pet = await fetch(url, {
            method: "POST",
            body: form
        });
        if (pet.ok) {
            formSent = true;
        }
    }
    if (formSent) {
        Swal.fire({
            icon: "success",
            title: "¡Éxito!",
            text: "Se han guardado los registros correctamente.",
            timer: 3000,
            showConfirmButton: false
        }).then(() => {
            closeModal();
        });
    } else {
        Swal.fire({
            icon: "warning",
            title: "No se enviaron registros",
            text: "Por favor, completa al menos una vacuna y/o desparacitación para guardar los registros."
        });
    }

}

function closeModal() {
    document.getElementById("application_date1").value = "";
    document.getElementById("product1").value = "";
    document.getElementById("lab1").value = "";
    document.getElementById("lote1").value = "";
    document.getElementById("next_application_date1").value = "";
    document.getElementById("observations1").value = "";

    document.getElementById("application_date2").value = "";
    document.getElementById("product2").value = "";
    document.getElementById("dose2").value = "";
    document.getElementById("last_deworming_date2").value = "";
    document.getElementById("next_application_date2").value = "";
    document.getElementById("observations2").value = "";

    document.getElementById("application_date3").value = "";
    document.getElementById("product3").value = "";
    document.getElementById("dose3").value = "";
    document.getElementById("last_deworming_date3").value = "";
    document.getElementById("next_application_date3").value = "";
    document.getElementById("observations3").value = "";
    $('#ModalCertificate').modal('hide');
}

async function OpenLabs() {
    document.getElementById("reception_id_labs").value = Reception_Id;
    $('#ModalLab').modal('show');
}

async function OpenImgs() {
    document.getElementById("reception_id_img").value = Reception_Id;
    $('#ModalImg').modal('show');
}

async function AddLabs() {
    event.preventDefault();
    let url = route('appointment-services.store');
    let form = new FormData(document.getElementById("NewLab"));
    let pet = await fetch(url, { method: "POST", body: form });
    let resp = await pet.json();

    if (pet.ok) {
        Swal.fire({
            icon: "success",
            title: "Se guardo el laboratorio con exito",
            timer: 7000,
            showConfirmButton: true
        })
        LabTable.ajax.reload();
        closeModalLabs()
    } else {
        let resp = await pet.json();
        Swal.fire({
            icon: "error",
            body: resp
        })
    }
}

function closeModalLabs() {
    $('#lab_type_id').val('').trigger('change')
    $('#observations_labs').val('')
    $('#ModalLab').modal('hide');
}

async function AddImgs() {
    event.preventDefault();
    let url = route('appointment-services.store');
    let form = new FormData(document.getElementById("NewImg"));
    let pet = await fetch(url, { method: "POST", body: form });
    let resp = await pet.json();

    if (pet.ok) {
        Swal.fire({
            icon: "success",
            title: "Se guardo la imagenologia con exito",
            timer: 7000,
            showConfirmButton: true
        })
        ImgTable.ajax.reload();
        closeModalImgs()
    } else {
        let resp = await pet.json();
        Swal.fire({
            icon: "error",
            body: resp
        })
    }
}

function closeModalImgs() {
    $('#imaging_type_id').val('').trigger('change')
    $('#observations_img').val('')
    $('#ModalImg').modal('hide');
}

var LabTable = undefined;
$(document).ready(function () {
    LabTable = $('#DataLabs').DataTable({
        ajax: route('appointment-services.labs', Reception_Id),
        responsive: true,
        order: [0, 'desc'],
        columns: [
            {
                data: null,
                render: function (data) {
                    return data.lab ? data.lab.NOMBRE : '';
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

var ImgTable = undefined;
$(document).ready(function () {
    ImgTable = $('#DataImgs').DataTable({
        ajax: route('appointment-services.imgs', Reception_Id),
        responsive: true,
        order: [0, 'desc'],
        columns: [
            {
                data: null,
                render: function (data) {
                    return data.imaging ? data.imaging.NOMBRE : '';
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
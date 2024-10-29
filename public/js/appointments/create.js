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
    event.preventDefault(); // Prevenir el comportamiento predeterminado del formulario o botón.
    const dayNextCheck = document.getElementById("day_next_check").value;

    // Validar si 'day_next_check' está vacío o es nulo
    if (!dayNextCheck) {
        Swal.fire({
            icon: 'warning',
            title: 'Campo obligatorio',
            text: 'Por favor, selecciona el próximo control antes de finalizar la consulta.'
        });
        return; // Salir de la función si 'day_next_check' está vacío
    }

    // Confirmación con SweetAlert2
    const result = await Swal.fire({
        title: '¿Ya terminó la consulta?',
        text: "Los datos de la consulta y fórmula médica serán guardados, recuerda incluir el próximo control.",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, finalizar.',
        cancelButtonText: 'No, continuar consulta.'
    });

    
    if (result.isConfirmed) {
        try {
            // Enviar el formulario de la cita
            let url = route('appointments.store');
            let form = new FormData(document.getElementById("NewAppointment"));

            let pet = await fetch(url, { method: "POST", body: form });

            if (!pet.ok) {
                throw new Error('Error al guardar la cita');  // Si hay error, lanzamos una excepción
            }

            // Enviar el formulario de la prescripción
            let url2 = route('prescriptions.store');
            let form2 = new FormData(document.getElementById("NewPrescription"));

            let pet2 = await fetch(url2, { method: "POST", body: form2 });
            let resp2 = await pet2.json();

            if (!pet2.ok) throw new Error('Error al guardar la prescripción');

            // Abrir la nueva ventana para imprimir la prescripción
            let prescription = resp2.id;
            window.open(route('prescription.imprimir', prescription), '_blank');

            // Redirigir a la lista de asignaciones después de que todo se haya completado
            Swal.fire({
                icon: 'success',
                title: 'Consulta finalizada con éxito',
                text: 'Puedes seguir atendiendo al siguiente paciente.',
                timer: 3000
            }).then(() => {
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

async function Register(){
    event.preventDefault();
    let url = route('vaccine-certificates.store');
            let form = new FormData(document.getElementById("NewInterDeworming"));

            let pet = await fetch(url, { method: "POST", body: form });

            
}

async function OpenCarnet() {
    document.getElementById("pet_id1").value = Pet_Id;
    document.getElementById("pet_id2").value = Pet_Id;
    document.getElementById("pet_id3").value = Pet_Id;
    $('#myModal').modal('show');
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
    $('#myModal').modal('hide');
}
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
                        <a class="btn btn-sm btn-primary"  title="Ver datos de consulta" href="${route('appointment.list', data.id)}">
                            <span class="mage--hospital-shield-fill"></span>
                        </a>

                        <a type="button" href="${route('imprimir', data.id)}" class="btn btn-sm text-primary">
                            <span class="material-symbols--prescriptions-outline" weigth:10px title="Formula medica"></span>
                        </a>`;
                }
            },
        ],
    });
});
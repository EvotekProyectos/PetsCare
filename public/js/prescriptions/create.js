async function AddPrescription() {
    event.preventDefault();
    let url = route('prescriptions.store');
    let form = new FormData(document.getElementById("NewPrescription"));
    let pet = await fetch(url, { method: "POST", body: form });
    let resp = await pet.json();

    if (pet.ok) {
        Swal.fire({
            icon: "success",
            title: "Se guardó la receta médica",
            timer: 7000,
            showConfirmButton: true
        })
        let prescription = resp.id;
        window.open(route('prescription.imprimir', prescription), '_blank');
        window.location.href = route('prescriptions.index');
    } else {
        let resp = await pet.json();
        Swal.fire({
            icon: "error",
            body: resp
        })
    }
}

async function EndAppointment(event) {
    event.preventDefault();
    const form = document.getElementById("NewPrescription");
    if (!form) {
        console.error("Formulario no encontrado.");
        return;
    }

    const url = route('prescriptions.store');
    const formData = new FormData(form);

    try {
        const response = await fetch(url, { method: "POST", body: formData });
        const data = await response.json();

        if (!response.ok) throw new Error("Error al guardar la prescripción");

        const prescriptionId = data.id;
        window.open(route('prescription.imprimir', prescriptionId), '_blank');

        Swal.fire({
            icon: 'success',
            title: 'Fórmula generada con éxito',
            timer: 3000
        }).then(() => {
            window.location.href = route('prescriptions.index');
        });

    } catch (error) {
        Swal.fire({
            icon: "error",
            title: "Error",
            text: error.message || "Ocurrió un error durante el proceso."
        });
    }
}

//Función Para Guardar Receta y Generar Folio de Orden de Venta para los casos de alta de Hospital
async function DischargePrescription() {
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
        let url = route('prescriptions.store');
        let form = new FormData(document.getElementById("NewPrescription"));
        let pet = await fetch(url, { method: "POST", body: form });
        let resp = await pet.json();

        if (pet.ok) {
            let url3 = route('redsheet.pay', Reception_Id);
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
                let prescription = resp.id;
                window.open(route('prescription.imprimir', prescription), '_blank');
                window.location.href = route('assignment.hospital');
            });
        } else {
            let resp = await pet.json();
            Swal.fire({
                icon: "error",
                body: resp
            })
        }
    } catch (error) {
        Swal.fire({
            icon: "error",
            title: "Error inesperado",
            text: "Por favor, intenta nuevamente."
        });
    }
}
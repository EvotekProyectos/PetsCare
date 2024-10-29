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

$(document).ready(function () {
    $('#servicie').select2({
        placeholder: 'Buscar servicio',
        width: 'resolve'
    });
});




async function Cremation(event) {
    event.preventDefault();
    let url = route('cremations.store'); 
    let form = new FormData(document.getElementById("newCremation")); 

    try {
        let response = await fetch(url, { method: "POST", body: form });
        let data = await response.json();

        if (response.ok) {
            Swal.fire({
                icon: "success",
                title: "Se guardó el servicio correctamente",
                timer: 7000,
                showConfirmButton: true
            });

            let cremationId = data.id; 
            window.open(route('cremation.comprobante', cremationId), '_blank'); 
            window.location.href = route('receptions.index');
        } else {
            Swal.fire({
                icon: "error",
                title: "Error al guardar el servicio",
                text: data.message || "Hubo un problema con el registro."
            });
        }
    } catch (error) {
        Swal.fire({
            icon: "error",
            title: "Error inesperado",
            text: "Por favor, intenta nuevamente."
        });
        console.error("Error:", error);
    }
}

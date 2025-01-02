$(document).ready(function () {
    $('#servicie').select2({
        placeholder: 'Buscar servicio',
        width: 'resolve'
    });
});

window.onload = function () {
    if (Pic_id !== null) {
        let fileRoute = Pic_route.startsWith('/') ? Pic_route.substring(1) : Pic_route;
        $("#preview").attr("src", ruta + fileRoute);
    }
    else {
        $("#preview").attr("src", imgDefault);
    }

}


async function Cremation(event) {
    event.preventDefault();
    let url = route('cremations.store');
    let form = new FormData(document.getElementById("newCremation"));

    Swal.fire({
        title: 'Procesando...',
        text: 'Por favor espera mientras procesamos la solicitud.',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    try {
        let response = await fetch(url, { method: "POST", body: form });
        let data = await response.json();

        if (response.ok) {
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

                let cremationId = data.id;
                window.open(route('cremation.comprobante', cremationId), '_blank');
                window.location.href = route('receptions.index');
            });
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

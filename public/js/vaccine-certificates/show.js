window.onload = function() {
    let fileRoute = Pic_route.startsWith('/') ? Pic_route.substring(1) : Pic_route;

    if (Pic_id !== null) {
        $("#preview").attr("src", ruta + fileRoute);
    } else {
        $("#preview").attr("src", imgDefault);
    }

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
    $('#ModalCertificate').modal('hide');
}
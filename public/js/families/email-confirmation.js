// Validación de UX (no autoritativa) para "Confirmar correo electrónico" en el
// formulario de familias. La validación real siempre corre en FamilyRequest
// (regla "same:email"); esto solo evita que el usuario descubra el error
// hasta después de enviar el formulario.
document.addEventListener("DOMContentLoaded", function () {
    var email = document.getElementById("email");
    var confirmation = document.getElementById("email_confirmation");
    var feedback = document.getElementById("email_confirmation_live_feedback");

    if (!email || !confirmation || !feedback) {
        return;
    }

    function checkMatch() {
        if (!confirmation.value) {
            confirmation.classList.remove("is-invalid");
            feedback.classList.remove("d-block");
            feedback.textContent = "";
            return;
        }

        var matches = confirmation.value === email.value;
        confirmation.classList.toggle("is-invalid", !matches);
        feedback.classList.toggle("d-block", !matches);
        feedback.textContent = matches ? "" : "Los correos electrónicos no coinciden.";
    }

    email.addEventListener("input", checkMatch);
    confirmation.addEventListener("input", checkMatch);
});

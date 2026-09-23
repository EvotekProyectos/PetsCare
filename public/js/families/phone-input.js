// Inicializa intl-tel-input en los campos de teléfono del formulario de familias
// (teléfono principal y teléfono del contacto autorizado). En ambos casos el
// input visible (#..._input) es solo para que el usuario escriba/vea el número
// formateado; el valor que Laravel recibe ("phone" / "contact_number") viaja en
// el input hidden que ya existe en el formulario (family/form.blade.php), y que
// la librería sincroniza automáticamente al enviar el formulario (hiddenInputs).
document.addEventListener("DOMContentLoaded", function () {
    if (typeof window.intlTelInput === "undefined") {
        return;
    }

    function initPhoneField(inputId, hiddenName) {
        var input = document.getElementById(inputId);

        if (!input) {
            return;
        }

        window.intlTelInput(input, {
            initialCountry: "mx",
            countryNameLocale: "es",
            hiddenInputs: function () {
                return { phone: hiddenName };
            },
        });
    }

    initPhoneField("phone_input", "phone");
    initPhoneField("contact_number_input", "contact_number");
});

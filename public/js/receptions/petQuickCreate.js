// family_id del receptionModal en el momento en que se abrió el modal rápido.
// null => no había familia fijada (se muestra el selector existente/nueva).
let qcFamilyIdAtOpen = null;

// Instancias de intl-tel-input sobre qc_family_phone y qc_family_contact_number.
// Se crean una sola vez (en 'shown.bs.modal', igual que ensureQcSelect2Init: el
// widget necesita el modal ya visible para calcular bien sus medidas) y se
// reutilizan en cada apertura del modal.
let qcPhoneIti = null;
let qcContactNumberIti = null;

const QC_FAMILY_FIELD_MAP = {
    name: 'qc_family_name',
    phone: 'qc_family_phone',
    email: 'qc_family_email',
    email_confirmation: 'qc_family_email_confirmation',
    address: 'qc_family_address',
    contact_name: 'qc_family_contact_name',
    contact_number: 'qc_family_contact_number',
    fam_classification_id: 'qc_family_classification_id',
};

const QC_PET_FIELD_MAP = {
    family_id: 'qc_existing_family_id',
    name: 'qc_pet_name',
    species_id: 'qc_pet_species_id',
    breed_id: 'qc_pet_breed_id',
    number_chip: 'qc_pet_chip',
    gender_id: 'qc_pet_gender_id',
    reproductive_status_id: 'qc_pet_reproductive_status_id',
    birthday: 'qc_pet_birthday',
    weight: 'qc_pet_weight',
    physic_descrip: 'qc_pet_physic_descrip',
    pet_classification_id: 'qc_pet_classification_id',
    notes: 'qc_pet_notes',
    picture_id: 'qc_pet_photo_file',
    file: 'qc_pet_photo_file',
};

// Snapshot del src por defecto de la foto (mismo criterio que
// petSelectFullOptionsHtml más abajo): se captura una sola vez al cargar,
// para poder restaurarlo en resetPetQuickCreateForm() sin depender de una
// variable global tipo imgDefault (esta página no la define).
const qcPetPhotoDefaultSrc = document.getElementById('qc_pet_photo_preview').src;

function padFamilyId(id) {
    return String(id).padStart(4, '0');
}

// bootstrap.Modal.hide() es animado (no termina en el mismo tick). Llamar
// show() de otro modal justo después, sin esperar, deja los backdrops mal
// apilados (el nuevo modal queda tapado por el backdrop del anterior — se ve
// como una pantalla gris sin nada abierto). Encadenamos vía 'hidden.bs.modal'.
//
// Causa raíz del modal "atascado" (investigado en bootstrap.min.js, v5.3.3,
// tal cual se usa en este proyecto): Modal.hide() empieza con la guarda
// `this._isShown && !this._isTransitioning` — si se llama MIENTRAS el modal
// todavía está en su propia animación de apertura (show() en curso,
// _isTransitioning aún true), hide() no hace absolutamente nada: no dispara
// 'hidden.bs.modal', no oculta nada. Como este flujo depende por completo de
// que ese evento llegue para continuar (mostrar el otro modal), esa llamada
// swallowed dejaba la promesa colgada para siempre: el modal de mascota
// quedaba visualmente abierto pero sin reaccionar a Cancelar/X, scroll,
// clicks, nada -exactamente el síntoma reportado de "queda atascado"-, y
// solo se daba quien alcanzaba a interactuar (click en "Nueva mascota",
// Cancelar, o Guardar) durante la ventana de la transición de apertura
// (~150-300ms) — de ahí que fuera intermitente y no reproducible siempre.
//
// _isShown/_isTransitioning son propiedades privadas de Bootstrap (no hay
// forma pública de consultarlas), así que en vez de leerlas se reintenta
// hide() pasado el tiempo que dura cualquier transición de Bootstrap, y si
// aun así no llegó 'hidden.bs.modal', se continúa de todas formas: es
// preferible completar la secuencia en un estado consistente a dejarla
// colgada indefinidamente.
function hideThenShow(modalToHideEl, modalToShowEl) {
    return new Promise(function (resolve) {
        // Quita el foco de cualquier elemento dentro de modalToHideEl
        // ANTES de ocultarlo, porque bootstrap.Modal.hide() marca
        // aria-hidden="true" de forma síncrona, y el navegador bloquea
        // eso si un descendiente conserva el foco.
        if (modalToHideEl.contains(document.activeElement)) {
            document.activeElement.blur();
        }

        let settled = false;
        let retryTimer = null;
        let fallbackTimer = null;

        function proceed() {
            if (settled) return;
            settled = true;
            modalToHideEl.removeEventListener('hidden.bs.modal', onHidden);
            clearTimeout(retryTimer);
            clearTimeout(fallbackTimer);
            bootstrap.Modal.getOrCreateInstance(modalToShowEl).show();
            resolve();
        }

        function onHidden() {
            proceed();
        }

        modalToHideEl.addEventListener('hidden.bs.modal', onHidden, { once: true });
        bootstrap.Modal.getOrCreateInstance(modalToHideEl).hide();

        // 350ms cubre de sobra la duración por defecto de una transición de
        // Bootstrap (.modal.fade, ~300ms): si 'hidden.bs.modal' no llegó para
        // entonces, lo más probable es que el hide() de arriba haya caído
        // durante la transición de apertura y se haya ignorado. Se reintenta.
        retryTimer = setTimeout(function () {
            bootstrap.Modal.getOrCreateInstance(modalToHideEl).hide();
            // Si el reintento tampoco alcanzó a completarse (mismo caso límite
            // otra vez, o cualquier otro motivo), no se vuelve a esperar
            // indefinidamente: se continúa igual para no dejar el modal
            // atascado.
            fallbackTimer = setTimeout(proceed, 350);
        }, 350);
    });
}

function clearPetQuickCreateErrors() {
    $('#petQuickCreateForm .is-invalid').removeClass('is-invalid');
    $('#petQuickCreateForm .invalid-feedback').remove();
    $('#petQuickCreateModal .select2-selection').removeClass('is-invalid');
}

function renderQcErrors(errors, fieldMap) {
    Object.keys(errors).forEach(function (field) {
        const targetId = fieldMap[field];
        if (!targetId) {
            return;
        }
        const message = errors[field][0];
        const $field = $('#' + targetId);
        $field.addClass('is-invalid');
        if ($field.hasClass('select2')) {
            $field.next('.select2-container').find('.select2-selection').addClass('is-invalid');
        }
        $field.parent().append(`<div class="invalid-feedback d-block"><strong>${message}</strong></div>`);
    });
}

function resetPetQuickCreateForm() {
    const form = document.getElementById('petQuickCreateForm');
    form.reset();
    clearPetQuickCreateErrors();

    document.getElementById('qc_family_mode_existing').checked = true;
    document.getElementById('qc_family_existing_fields').style.display = '';
    document.getElementById('qc_family_new_fields').style.display = 'none';

    if ($('#qc_existing_family_id').hasClass('select2-hidden-accessible')) {
        $('#qc_existing_family_id').val('').trigger('change');
    }

    // form.reset() vacía los inputs de teléfono, pero intl-tel-input no escucha
    // el evento 'reset': hay que devolverlos a mano al país por defecto para
    // que no arrastren el país seleccionado en un uso previo del modal.
    if (qcPhoneIti) {
        qcPhoneIti.setNumber('');
        qcPhoneIti.setSelectedCountry('mx');
    }
    if (qcContactNumberIti) {
        qcContactNumberIti.setNumber('');
        qcContactNumberIti.setSelectedCountry('mx');
    }

    // form.reset() no alcanza a restaurar esto: loadBreedsForSpecies()
    // reemplaza las <option> de qc_pet_breed_id por las de la última especie
    // elegida, así que hay que devolverlo a mano al placeholder/disabled
    // original (ver pet-quick-create-modal.blade.php).
    document.getElementById('qc_pet_breed_id').innerHTML =
        '<option value="">Selecciona primero una especie</option>';
    document.getElementById('qc_pet_breed_id').disabled = true;

    // Mismo motivo que en el cascade de especie->raza: Select2 no relee las
    // <option> solo porque se reescribió el <select> nativo por DOM.
    if ($('#qc_pet_breed_id').hasClass('select2-hidden-accessible')) {
        $('#qc_pet_breed_id').val('').trigger('change');
    }

    // form.reset() vacía el <input type="file">, pero no toca la <img> de
    // vista previa (igual que en pets/edit.js) — hay que regresarla a mano.
    document.getElementById('qc_pet_photo_preview').src = qcPetPhotoDefaultSrc;
}

// dropdownParent apunta a .modal-content (la caja blanca visible, position:
// relative), NO al .modal exterior (position: fixed; overflow-y: auto — es
// ese overflow el que hace que este modal largo scrollee completo, título y
// botones incluidos, ya que no usa modal-dialog-scrollable). Con el .modal
// como dropdownParent, Select2 mide el espacio disponible contra ESE
// contenedor con scroll propio y calcula mal cuánto espacio real queda
// visible en pantalla: en campos que quedan más abajo en el formulario (raza
// necesita haber scrolleado para verse) terminaba decidiendo abrir el
// desplegable hacia arriba y bien lejos del campo. .modal-content no tiene
// scroll propio -es simplemente la caja que se desplaza junto con el resto
// dentro de .modal-, así que Select2 mide contra el límite real visible.
const QC_SELECT2_DROPDOWN_PARENT = () => $('#petQuickCreateModal .modal-content');

function ensureQcSelect2Init() {
    if (!$('#qc_existing_family_id').hasClass('select2-hidden-accessible')) {
        $('#qc_existing_family_id').select2({
            placeholder: 'Buscar familia',
            width: 'resolve',
            dropdownParent: QC_SELECT2_DROPDOWN_PARENT(),
        });
    }

    if (!$('#qc_pet_breed_id').hasClass('select2-hidden-accessible')) {
        $('#qc_pet_breed_id').select2({
            placeholder: 'Selecciona la raza',
            width: 'resolve',
            dropdownParent: QC_SELECT2_DROPDOWN_PARENT(),
        });
    }
}

// Validación de UX (no autoritativa) para "Confirmar correo electrónico".
// La validación real siempre corre en FamilyRequest (regla "same:email") del
// lado del servidor, vía renderQcErrors si la respuesta es 422. Se maneja
// aparte de clearPetQuickCreateErrors/renderQcErrors (que borran o agregan
// .invalid-feedback en cada intento de envío) para no pisarse con ellas: este
// hint se crea/borra a demanda en cada tecleo, no queda un nodo fijo que
// clearPetQuickCreateErrors pudiera eliminar de forma permanente.
function setQcEmailConfirmationHint(matches) {
    const input = document.getElementById('qc_family_email_confirmation');
    let hint = document.getElementById('qc_family_email_confirmation_live_hint');

    if (matches) {
        input.classList.remove('is-invalid');
        if (hint) hint.remove();
        return;
    }

    input.classList.add('is-invalid');
    if (!hint) {
        hint = document.createElement('div');
        hint.id = 'qc_family_email_confirmation_live_hint';
        hint.className = 'invalid-feedback d-block';
        input.parentElement.appendChild(hint);
    }
    hint.textContent = 'Los correos electrónicos no coinciden.';
}

function checkQcEmailConfirmationMatch() {
    const confirmation = document.getElementById('qc_family_email_confirmation');

    if (!confirmation.value) {
        setQcEmailConfirmationHint(true);
        return;
    }

    setQcEmailConfirmationHint(confirmation.value === document.getElementById('qc_family_email').value);
}

// Vista previa de la foto: mismo patrón que public/js/pets/edit.js
// (FileReader -> src de la <img>), reutilizado tal cual, sin librería nueva.
function qcPreviewPetPhoto() {
    const input = document.getElementById('qc_pet_photo_file');
    const preview = document.getElementById('qc_pet_photo_preview');

    if (!input.files || !input.files[0]) {
        preview.src = qcPetPhotoDefaultSrc;
        return;
    }

    const reader = new FileReader();
    reader.onload = function (e) {
        preview.src = e.target.result;
    };
    reader.readAsDataURL(input.files[0]);
}

function ensureQcPhoneIntlTelInput() {
    if (typeof window.intlTelInput === 'undefined') {
        return;
    }

    if (!qcPhoneIti) {
        qcPhoneIti = window.intlTelInput(document.getElementById('qc_family_phone'), {
            initialCountry: 'mx',
            countryNameLocale: 'es',
        });
    }

    if (!qcContactNumberIti) {
        qcContactNumberIti = window.intlTelInput(document.getElementById('qc_family_contact_number'), {
            initialCountry: 'mx',
            countryNameLocale: 'es',
        });
    }
}

function openPetQuickCreateModal() {
    const familyId = document.getElementById('family_id').value;
    qcFamilyIdAtOpen = familyId || null;

    resetPetQuickCreateForm();

    if (qcFamilyIdAtOpen) {
        document.getElementById('qc_family_readonly_section').style.display = '';
        document.getElementById('qc_family_picker_section').style.display = 'none';
        const familyText = $('#family_id option:selected').text().trim();
        document.getElementById('qc_family_readonly_text').textContent = familyText || ('Familia #' + qcFamilyIdAtOpen);
        document.getElementById('qc_family_id_fixed').value = qcFamilyIdAtOpen;
    } else {
        document.getElementById('qc_family_readonly_section').style.display = 'none';
        document.getElementById('qc_family_picker_section').style.display = '';
    }

    hideThenShow(document.getElementById('receptionModal'), document.getElementById('petQuickCreateModal'));
}

function cancelPetQuickCreate() {
    hideThenShow(document.getElementById('petQuickCreateModal'), document.getElementById('receptionModal'));
}

// Sincroniza family_id/pet_id del receptionModal con la familia/mascota recién
// creadas, sin perder la selección por el cascadeo de los onchange existentes
// (family_id -> getpets(); pet_id -> getFamily()), que si se dejan activos
// durante esta actualización programática se pisan entre sí.
async function syncReceptionSelectsAfterQuickCreate(familyId, familyLabel, petId) {
    const petSelect = document.getElementById('pet_id');
    const familySelect = document.getElementById('family_id');
    const petOnchange = petSelect.getAttribute('onchange');
    const familyOnchange = familySelect.getAttribute('onchange');
    petSelect.removeAttribute('onchange');
    familySelect.removeAttribute('onchange');

    try {
        // Si la familia era nueva, todavía no existe como <option> en
        // family_id (con familia ya fija -qcFamilyIdAtOpen- el <option> ya
        // estaba desde antes de abrir petQuickCreateModal).
        if (!qcFamilyIdAtOpen && !$('#family_id option[value="' + familyId + '"]').length) {
            const option = document.createElement('option');
            option.value = familyId;
            option.textContent = familyLabel;
            familySelect.appendChild(option);
        }

        // Se fuerza explícitamente el estado de family_id ANTES de cargar
        // mascotas, en vez de asumir que sobrevivió intacto al viaje por
        // petQuickCreateModal: con familia ya fija, en algunos casos Select2
        // no reflejaba visualmente la familia al volver aunque el <select>
        // nativo siguiera con el valor correcto por debajo — no hay que
        // depender únicamente de que getpets() haya filtrado bien para
        // asumir que la familia está bien seleccionada. onchange sigue
        // desconectado acá arriba, así que esto no dispara getpets() por
        // su cuenta (se llama explícito, una sola vez, abajo).
        $('#family_id').val(String(familyId)).trigger('change');

        await getpets(familyId);

        $('#pet_id').val(String(petId)).trigger('change');
    } finally {
        petSelect.setAttribute('onchange', petOnchange);
        familySelect.setAttribute('onchange', familyOnchange);
    }
}

async function submitPetQuickCreate() {
    clearPetQuickCreateErrors();

    const isNewFamilyMode = !qcFamilyIdAtOpen && document.getElementById('qc_family_mode_new').checked;
    const submitBtn = document.querySelector('#petQuickCreateForm button[type="submit"]');
    if (submitBtn) submitBtn.disabled = true;

    try {
        // Familia y mascota se envían en UNA sola petición a pets.quickCreate,
        // que las resuelve/crea dentro de una misma transacción de BD: si la
        // mascota falla, una familia nueva creada en este intento se revierte
        // junto con ella; si la familia ya existía, nunca se toca ni se borra
        // sin importar el resultado de la mascota (ver PetController::quickCreate).
        //
        // FormData (no JSON) desde que se agregó la foto: es el único formato
        // que permite mandar el archivo real, igual que ya hace pet/form.blade.php
        // con su input name="file" — PetController::quickCreate() ya sabe leer
        // esta misma forma (File::uploadFile() espera exactamente ese campo).
        // Los campos anidados usan notación family[..]/pet[..], que Laravel
        // decodifica igual que el objeto anidado que antes viajaba en JSON.
        const formData = new FormData();

        if (isNewFamilyMode) {
            // qc_family_phone/qc_family_contact_number solo muestran el número
            // nacional (el código de país se ve aparte, en la bandera); el número
            // completo hay que pedírselo a la instancia de intl-tel-input, no
            // leerlo de .value.
            const familyPhone = qcPhoneIti
                ? qcPhoneIti.getNumber()
                : document.getElementById('qc_family_phone').value;
            const familyContactNumber = qcContactNumberIti
                ? qcContactNumberIti.getNumber()
                : document.getElementById('qc_family_contact_number').value;

            formData.append('family_mode', 'new');
            formData.append('family[name]', document.getElementById('qc_family_name').value);
            formData.append('family[phone]', familyPhone);
            formData.append('family[email]', document.getElementById('qc_family_email').value);
            formData.append('family[email_confirmation]', document.getElementById('qc_family_email_confirmation').value);
            formData.append('family[address]', document.getElementById('qc_family_address').value);
            formData.append('family[contact_name]', document.getElementById('qc_family_contact_name').value);
            formData.append('family[contact_number]', familyContactNumber);
            formData.append('family[fam_classification_id]', document.getElementById('qc_family_classification_id').value);
        } else {
            formData.append('family_mode', 'existing');
            formData.append('family_id', qcFamilyIdAtOpen || document.getElementById('qc_existing_family_id').value);
        }

        formData.append('pet[name]', document.getElementById('qc_pet_name').value);
        formData.append('pet[species_id]', document.getElementById('qc_pet_species_id').value);
        formData.append('pet[breed_id]', document.getElementById('qc_pet_breed_id').value);
        formData.append('pet[number_chip]', document.getElementById('qc_pet_chip').value);
        formData.append('pet[gender_id]', document.getElementById('qc_pet_gender_id').value);
        formData.append('pet[reproductive_status_id]', document.getElementById('qc_pet_reproductive_status_id').value);
        formData.append('pet[birthday]', document.getElementById('qc_pet_birthday').value);
        formData.append('pet[weight]', document.getElementById('qc_pet_weight').value);
        formData.append('pet[physic_descrip]', document.getElementById('qc_pet_physic_descrip').value);
        formData.append('pet[pet_classification_id]', document.getElementById('qc_pet_classification_id').value);
        formData.append('pet[notes]', document.getElementById('qc_pet_notes').value);
        // Sin checkbox en este modal (creación rápida): siempre nace viva,
        // igual que antes de agregar Foto/Notas. PetRequest exige el campo
        // (required|boolean), por eso se manda fijo en vez de omitirlo.
        formData.append('pet[deceased]', '0');

        // Igual que pet/form.blade.php: name="file" (no "pet[picture]"), es el
        // campo que File::uploadFile() ya sabe leer. Opcional: si no se elige
        // ninguna, la mascota se crea sin foto, como hoy.
        const photoFile = document.getElementById('qc_pet_photo_file').files[0];
        if (photoFile) {
            formData.append('file', photoFile);
        }

        const response = await fetch(route('pets.quickCreate'), {
            method: 'POST',
            headers: {
                // Sin 'Content-Type': el navegador arma el boundary de
                // multipart/form-data solo si no se fija a mano.
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': $('#csrf').attr('content'),
            },
            body: formData,
        });

        if (response.status === 422) {
            const data = await response.json();
            const errors = data.errors || {};
            if (errors.family) renderQcErrors(errors.family, QC_FAMILY_FIELD_MAP);
            if (errors.pet) renderQcErrors(errors.pet, QC_PET_FIELD_MAP);
            return;
        }
        if (!response.ok) {
            showAlert('Ocurrió un error al guardar los datos.', 'Error', 'error');
            return;
        }

        const data = await response.json();
        const familyId = data.family_id;
        const familyLabel = data.family
            ? padFamilyId(data.family.id) + '-' + data.family.name + ' Tel.' + (data.family.phone || '')
            : null;

        await hideThenShow(document.getElementById('petQuickCreateModal'), document.getElementById('receptionModal'));

        await syncReceptionSelectsAfterQuickCreate(familyId, familyLabel, data.pet.id);

        showAlert('Mascota guardada correctamente');
    } catch (error) {
        showAlert('Ocurrió un error inesperado. Vuelve a intentar más tarde.', 'Error', 'error');
        console.error('Error al guardar mascota (creación rápida):', error);
    } finally {
        if (submitBtn) submitBtn.disabled = false;
    }
}

$(document).ready(function () {
    $('#petQuickCreateModal').on('shown.bs.modal', function () {
        ensureQcSelect2Init();
        ensureQcPhoneIntlTelInput();
    });

    // loadBreedsForSpecies (pets/breed-cascade.js) es la MISMA función que
    // usa Form Pet (species_id/breed_id) — no se duplica ese fetch/populate.
    // No se usa bindSpeciesBreedCascade() tal cual porque esa solo llama a
    // loadBreedsForSpecies() y no sabe nada de Select2: ese helper reescribe
    // las <option> del <select> nativo directo por DOM, y Select2 no relee
    // esas <option> solo, hay que avisarle con un 'change' después de que
    // termine de cargar -si no, el buscador se queda mostrando la raza de la
    // especie anterior aunque el <select> de abajo ya tenga las nuevas-.
    document.getElementById('qc_pet_species_id').addEventListener('change', async function () {
        await loadBreedsForSpecies(this.value, 'qc_pet_breed_id');

        const $breed = $('#qc_pet_breed_id');
        if ($breed.hasClass('select2-hidden-accessible')) {
            $breed.trigger('change');
        }
    });

    $('input[name="qc_family_mode"]').on('change', function () {
        const isNew = document.getElementById('qc_family_mode_new').checked;
        document.getElementById('qc_family_existing_fields').style.display = isNew ? 'none' : '';
        document.getElementById('qc_family_new_fields').style.display = isNew ? '' : 'none';
    });

    document.getElementById('qc_family_email').addEventListener('input', checkQcEmailConfirmationMatch);
    document.getElementById('qc_family_email_confirmation').addEventListener('input', checkQcEmailConfirmationMatch);

    document.getElementById('qc_pet_photo_file').addEventListener('change', qcPreviewPetPhoto);

    $('#petQuickCreateForm').on('submit', function (e) {
        e.preventDefault();
        submitPetQuickCreate();
    });
});

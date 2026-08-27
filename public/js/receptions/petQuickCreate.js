// family_id del receptionModal en el momento en que se abrió el modal rápido.
// null => no había familia fijada (se muestra el selector existente/nueva).
let qcFamilyIdAtOpen = null;

const QC_FAMILY_FIELD_MAP = {
    name: 'qc_family_name',
    phone: 'qc_family_phone',
    email: 'qc_family_email',
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
};

function padFamilyId(id) {
    return String(id).padStart(4, '0');
}

// bootstrap.Modal.hide() es animado (no termina en el mismo tick). Llamar
// show() de otro modal justo después, sin esperar, deja los backdrops mal
// apilados (el nuevo modal queda tapado por el backdrop del anterior — se ve
// como una pantalla gris sin nada abierto). Encadenamos vía 'hidden.bs.modal'.
function hideThenShow(modalToHideEl, modalToShowEl) {
    return new Promise(function (resolve) {
        // Quita el foco de cualquier elemento dentro de modalToHideEl 
        // ANTES de ocultarlo, porque bootstrap.Modal.hide() marca 
        // aria-hidden="true" de forma síncrona, y el navegador bloquea 
        // eso si un descendiente conserva el foco.
        if (modalToHideEl.contains(document.activeElement)) {
            document.activeElement.blur();
        }

        function onHidden() {
            modalToHideEl.removeEventListener('hidden.bs.modal', onHidden);
            bootstrap.Modal.getOrCreateInstance(modalToShowEl).show();
            resolve();
        }
        modalToHideEl.addEventListener('hidden.bs.modal', onHidden, { once: true });
        bootstrap.Modal.getOrCreateInstance(modalToHideEl).hide();
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

    // form.reset() no alcanza a restaurar esto: loadBreedsForSpecies()
    // reemplaza las <option> de qc_pet_breed_id por las de la última especie
    // elegida, así que hay que devolverlo a mano al placeholder/disabled
    // original (ver pet-quick-create-modal.blade.php).
    document.getElementById('qc_pet_breed_id').innerHTML =
        '<option value="">Selecciona primero una especie</option>';
    document.getElementById('qc_pet_breed_id').disabled = true;
}

function ensureQcSelect2Init() {
    if (!$('#qc_existing_family_id').hasClass('select2-hidden-accessible')) {
        $('#qc_existing_family_id').select2({
            placeholder: 'Buscar familia',
            width: 'resolve',
            dropdownParent: $('#petQuickCreateModal'),
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
        let familyId = qcFamilyIdAtOpen;
        let familyLabel = null;

        if (isNewFamilyMode) {
            const familyPayload = {
                name: document.getElementById('qc_family_name').value,
                phone: document.getElementById('qc_family_phone').value,
                email: document.getElementById('qc_family_email').value,
                address: document.getElementById('qc_family_address').value,
                contact_name: document.getElementById('qc_family_contact_name').value,
                contact_number: document.getElementById('qc_family_contact_number').value,
                fam_classification_id: document.getElementById('qc_family_classification_id').value || null,
            };

            const familyResponse = await fetch(route('families.store'), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': $('#csrf').attr('content'),
                },
                body: JSON.stringify(familyPayload),
            });

            if (familyResponse.status === 422) {
                const data = await familyResponse.json();
                renderQcErrors(data.errors || {}, QC_FAMILY_FIELD_MAP);
                return;
            }
            if (!familyResponse.ok) {
                showAlert('Ocurrió un error al guardar la familia.', 'Error', 'error');
                return;
            }

            const familyData = await familyResponse.json();
            familyId = familyData.id;
            familyLabel = padFamilyId(familyData.id) + '-' + familyData.name + ' Tel.' + (familyData.phone || '');
        } else if (!qcFamilyIdAtOpen) {
            familyId = document.getElementById('qc_existing_family_id').value;
        }

        const petPayload = {
            family_id: familyId,
            name: document.getElementById('qc_pet_name').value,
            species_id: document.getElementById('qc_pet_species_id').value || null,
            breed_id: document.getElementById('qc_pet_breed_id').value || null,
            number_chip: document.getElementById('qc_pet_chip').value,
            gender_id: document.getElementById('qc_pet_gender_id').value,
            reproductive_status_id: document.getElementById('qc_pet_reproductive_status_id').value,
            birthday: document.getElementById('qc_pet_birthday').value || null,
            weight: document.getElementById('qc_pet_weight').value,
            physic_descrip: document.getElementById('qc_pet_physic_descrip').value,
            pet_classification_id: document.getElementById('qc_pet_classification_id').value || null,
            deceased: 0,
        };

        const petResponse = await fetch(route('pets.store'), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': $('#csrf').attr('content'),
            },
            body: JSON.stringify(petPayload),
        });

        if (petResponse.status === 422) {
            const data = await petResponse.json();
            renderQcErrors(data.errors || {}, QC_PET_FIELD_MAP);
            return;
        }
        if (!petResponse.ok) {
            showAlert('Ocurrió un error al guardar la mascota.', 'Error', 'error');
            return;
        }

        const petData = await petResponse.json();

        await hideThenShow(document.getElementById('petQuickCreateModal'), document.getElementById('receptionModal'));

        await syncReceptionSelectsAfterQuickCreate(familyId, familyLabel, petData.id);

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
    });

    // Definido en pets/breed-cascade.js — mismo comportamiento y endpoint
    // que usa Form Pet (species_id/breed_id) para no duplicar la lógica.
    bindSpeciesBreedCascade('qc_pet_species_id', 'qc_pet_breed_id');

    $('input[name="qc_family_mode"]').on('change', function () {
        const isNew = document.getElementById('qc_family_mode_new').checked;
        document.getElementById('qc_family_existing_fields').style.display = isNew ? 'none' : '';
        document.getElementById('qc_family_new_fields').style.display = isNew ? '' : 'none';
    });

    $('#petQuickCreateForm').on('submit', function (e) {
        e.preventDefault();
        submitPetQuickCreate();
    });
});

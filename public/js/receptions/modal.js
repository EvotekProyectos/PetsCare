let isUpdating = false;
let isCase4Active = false;
let pendingReceptionData = null;
// Última family_id para la que se pidió getpets(): permite descartar la
// respuesta de un fetch en vuelo si, mientras tanto, family_id cambió o se
// vació (ej. el usuario selecciona una familia y la quita/cambia antes de
// que responda el fetch anterior) — sin esto, esa respuesta tardía
// repoblaba #pet_id con las mascotas de una familia que ya no está
// seleccionada.
let currentFamilyId = null;

// Snapshot del <option> completo (todas las mascotas, sin filtrar por
// familia) que Blade renderiza en #pet_id al cargar la página — se usa para
// restaurar la búsqueda libre por mascota cuando family_id queda vacío, en
// vez de dejar el select sin opciones. Capturado una sola vez, antes de que
// getpets() lo reemplace por primera vez con la lista filtrada de alguna
// familia.
const petSelectFullOptionsHtml = document.getElementById("pet_id").innerHTML;

function ensureSelect2Init() {
  if (!$("#family_id").hasClass("select2-hidden-accessible")) {
    $("#family_id").select2({
      placeholder: "Buscar Familia",
      width: "resolve",
      allowClear: true,
      dropdownParent: $("#receptionModal"),
    });
  }
  if (!$("#pet_id").hasClass("select2-hidden-accessible")) {
    $("#pet_id").select2({
      placeholder: "Buscar Mascota",
      width: "resolve",
      allowClear: true,
      dropdownParent: $("#receptionModal"),
    });
  }
}

// Sin family_id no hay ninguna familia por la cual filtrar #pet_id, así que
// se restaura la lista completa de mascotas (petSelectFullOptionsHtml) en
// vez de dejarlo vacío: permite seguir buscando por mascota directamente
// (sin depender de elegir primero una familia) — al elegir una, el onchange
// existente (getFamily()) ya trae su familia a #family_id. Se limpia la
// selección igual, para no arrastrar la mascota de la familia anterior.
function clearPetSelect() {
  document.getElementById("pet_id").innerHTML = petSelectFullOptionsHtml;
  $("#pet_id").val("").trigger("change");
}

async function getpets(family_id) {
  // currentFamilyId se actualiza siempre, incluso si el guard de abajo
  // corta la ejecución, para que cualquier fetch en vuelo de una llamada
  // anterior sepa (al resolver) que ya no es la familia vigente.
  currentFamilyId = family_id || null;

  if (!family_id) {
    clearPetSelect();
    return;
  }

  if (isUpdating) return;
  isUpdating = true;

  let url = route("pets.data", family_id);
  let peticion = await fetch(url);
  if (peticion.ok && currentFamilyId === family_id) {
    let respuesta = await peticion.json();
    let html = "";
    respuesta.forEach((pet) => {
      const chip = pet.number_chip ? ` #${pet.number_chip}` : "";

      html += `<option value="${pet.id}">${pet.name}${chip}</option>`;
    });
    document.getElementById("pet_id").innerHTML = html;
    $("#pet_id").trigger("change");
  }
  isUpdating = false;
}

// Desconecta el onchange de un select mientras se le hace un cambio
// programático, para no disparar su propio cascadeo (family_id -> getpets();
// pet_id -> getFamily()) durante una actualización que ya lo está manejando
// a mano. Mismo patrón que ya usa syncReceptionSelectsAfterQuickCreate() en
// petQuickCreate.js.
function setSelect2ValueSilently(selectId, value) {
  const el = document.getElementById(selectId);
  const onchange = el.getAttribute("onchange");
  el.removeAttribute("onchange");
  $(`#${selectId}`).val(value).trigger("change");
  el.setAttribute("onchange", onchange);
}

async function getFamily(pet_id) {
  if (isUpdating || !pet_id) return;
  isUpdating = true;

  try {
    let url = route("families.getFamilyByPet", pet_id);
    let peticion = await fetch(url);
    if (!peticion.ok) return;

    let family = await peticion.json();
    if (!family) return;

    setSelect2ValueSilently("family_id", family.id);

    // Filtra #pet_id a las mascotas de esta familia (mismo comportamiento
    // que si se hubiera elegido la familia primero). isUpdating se libera
    // antes porque getpets() lo usa como su propio guard de concurrencia.
    isUpdating = false;
    await getpets(family.id);

    // getpets() reemplaza las <option> de #pet_id con las de la familia;
    // se reselecciona la mascota que el usuario acababa de elegir (sigue
    // entre las opciones, porque pertenece a esta familia) sin volver a
    // disparar getFamily() por el onchange de #pet_id.
    setSelect2ValueSilently("pet_id", String(pet_id));
  } finally {
    isUpdating = false;
  }
}

function fillVeterinarianSelect(role) {
  const select = document.getElementById("veterinarian_id");
  const data =
    role === "colaborador"
      ? JSON.parse(select.dataset.collaborators)
      : JSON.parse(select.dataset.veterinarians);

  const currentValue = select.value; // preserva selección si sigue siendo válida

  let html =
    '<option value="">Selecciona ' +
    (role === "colaborador" ? "colaborador" : "M.V.Z") +
    "</option>";
  data.forEach((u) => {
    html += `<option value="${u.id}">${u.name}</option>`;
  });
  select.innerHTML = html;

  // si el valor anterior existe en la nueva lista, lo mantiene; si no, lo limpia
  if (data.some((u) => String(u.id) === currentValue)) {
    select.value = currentValue;
  }
}

// Campos cuyo "required" depende del tipo de recepción (ver ReceptionRequest::rules()
// para la validación real en backend — esto es solo feedback nativo del
// navegador antes de golpear el servidor).
const REQUIRED_FIELDS_BY_TYPE = {
  1: ["reason_id", "veterinarian_id"],
  2: ["admission_type_id", "area_id", "veterinarian_id"],
  3: ["veterinarian_id", "num_input"],
  4: ["exit_date", "num_input"],
  5: [],
};
const ALL_CONDITIONAL_REQUIRED_IDS = [
  "reason_id",
  "veterinarian_id",
  "admission_type_id",
  "area_id",
  "num_input",
  "exit_date",
];

function applyRequiredFieldsForType(type) {
  ALL_CONDITIONAL_REQUIRED_IDS.forEach((id) => {
    const el = document.getElementById(id);
    if (el) el.required = false;
  });

  (REQUIRED_FIELDS_BY_TYPE[type] || []).forEach((id) => {
    const el = document.getElementById(id);
    if (el) el.required = true;
  });
}

function togglee(radio) {
  document.getElementById("adm").style.display = "none";
  document.getElementById("area").style.display = "none";
  document.getElementById("motivo").style.display = "none";
  document.getElementById("mvz").style.display = "none";
  document.getElementById("consultorio").style.display = "none";
  document.getElementById("salida").style.display = "none";
  document.getElementById("num").style.display = "none";

  var type = parseInt(radio.value);
  applyRequiredFieldsForType(type);

  switch (type) {
    case 1:
      document.getElementById("motivo").style.display = "block";
      document.getElementById("mvz").style.display = "block";
      document.getElementById("person").innerText = "M.V.Z.";
      fillVeterinarianSelect("medico");
      document.getElementById("consultorio").style.display = "block";
      break;
    case 2:
      document.getElementById("adm").style.display = "block";
      document.getElementById("area").style.display = "block";
      document.getElementById("mvz").style.display = "block";
      document.getElementById("person").innerText = "M.V.Z.";
      fillVeterinarianSelect("medico");
      break;
    case 3:
      document.getElementById("mvz").style.display = "block";
      document.getElementById("person").innerText = "COLABORADOR";
      fillVeterinarianSelect("colaborador");
      document.getElementById("salida").style.display = "block";
      document.getElementById("num").style.display = "block";
      isCase4Active = false;
      break;
    case 4:
      document.getElementById("mvz").style.display = "block";
      document.getElementById("person").innerText = "M.V.Z.";
      fillVeterinarianSelect("medico");
      document.getElementById("salida").style.display = "block";
      document.getElementById("num").style.display = "block";
      isCase4Active = true;
      break;
    case 5:
      document.getElementById("mvz").style.display = "block";
      document.getElementById("person").innerText = "M.V.Z.";
      fillVeterinarianSelect("medico");
      break;
    default:
      break;
  }
}
// La fecha de salida nunca puede ser anterior a la de ingreso (crear y
// editar por igual — ver ReceptionRequest::rules(), after_or_equal:entry_date).
document.getElementById("entry_date").addEventListener("input", function () {
  if (this.value) {
    document.getElementById("exit_date").min = this.value;
  }
});

document.getElementById("exit_date").addEventListener("input", function () {
  if (isCase4Active) {
    let input = this;
    let date = new Date(input.value);

    if (date.getDay() === 0) {
      Swal.fire({
        icon: "error",
        title: "¡Error!",
        text: "Los domingos no están permitidos.",
        confirmButtonText: "Aceptar",
      });
      input.value = "";
    }
  }
});

function clearReceptionFormErrors() {
  $("#receptionForm .is-invalid").removeClass("is-invalid");
  $("#receptionForm .invalid-feedback").remove();
  $(".select2-selection").removeClass("is-invalid");
}

function addFieldError(fieldId, message) {
  const $field = $("#" + fieldId);
  $field.addClass("is-invalid");
  $field
    .parent()
    .append(`<div class="invalid-feedback"><strong>${message}</strong></div>`);
}

function renderReceptionFormErrors(errors) {
  Object.keys(errors).forEach(function (field) {
    const message = errors[field][0];

    if (field === "reception_type_id") {
      $('input[name="reception_type_id"]').addClass("is-invalid");
      $("#cremacion")
        .parent()
        .append(
          `<div class="invalid-feedback"><strong>${message}</strong></div>`,
        );
      return;
    }

    if (field === "pet_id" || field === "veterinarian_id") {
      $(".select2-selection").addClass("is-invalid");
    }

    addFieldError(field, message);
  });
}

function setSelectValue(id, value) {
  document.getElementById(id).value =
    value === null || value === undefined ? "" : value;
}

// Limpia inputs planos, radios y paneles condicionales. No toca los Select2
// (family_id/pet_id) porque este helper corre antes de que shown.bs.modal
// los haya inicializado.
function resetReceptionFormFields() {
  const form = document.getElementById("receptionForm");
  form.reset();
  document.getElementById("reception_id").value = "";
  clearReceptionFormErrors();

  // El "no fechas pasadas" (min de entry_date) solo aplica al CREAR —
  // openCreateReceptionModal() lo vuelve a fijar después de este reset;
  // al editar (applyReceptionData()) se queda sin min, para no bloquear
  // recepciones existentes con entry_date en el pasado.
  document.getElementById("entry_date").removeAttribute("min");
  document.getElementById("exit_date").removeAttribute("min");

  document.getElementById("adm").style.display = "none";
  document.getElementById("area").style.display = "none";
  document.getElementById("motivo").style.display = "none";
  document.getElementById("mvz").style.display = "none";
  document.getElementById("consultorio").style.display = "none";
  document.getElementById("salida").style.display = "none";
  document.getElementById("num").style.display = "none";
  applyRequiredFieldsForType(null);

  // el selector de tipo solo se oculta al editar (ver applyReceptionData);
  // al crear siempre debe estar visible.
  document.getElementById("receptionTypeGroup").style.display = "";
}

async function applyReceptionData(data) {
  resetReceptionFormFields();

  // No es válido cambiar el tipo de recepción una vez creada.
  document.getElementById("receptionTypeGroup").style.display = "none";

  document.getElementById("reception_id").value = data.id;
  document.getElementById("entry_date").value = data.entry_date ?? "";
  if (data.entry_date) {
    document.getElementById("exit_date").min = data.entry_date;
  }
  document.getElementById("exit_date").value = data.exit_date ?? "";
  document.getElementById("num_input").value = data.num ?? "";
  setSelectValue("admission_type_id", data.admission_type_id);
  setSelectValue("area_id", data.area_id);
  setSelectValue("reason_id", data.reason_id);
  setSelectValue("room_id", data.room_id);
  // veterinarian_id se aplica más abajo, después de togglee(),
  // porque togglee() repuebla las <option> del select según el tipo
  // (médicos vs colaboradores) y eso borraría cualquier valor puesto antes.

  $("#family_id")
    .val(data.family_id ?? "")
    .trigger("change");
  if (data.family_id) {
    await getpets(data.family_id);
  }
  $("#pet_id")
    .val(data.pet_id ?? "")
    .trigger("change");

  const radio = document.querySelector(
    `input[name="reception_type_id"][value="${data.reception_type_id}"]`,
  );
  if (radio) {
    radio.checked = true;
    togglee(radio);
  }

  // ahora que togglee() ya repobló #veterinarian_id con la lista correcta,
  // podemos seleccionar el valor guardado con seguridad
  setSelectValue("veterinarian_id", data.veterinarian_id);
}

function openCreateReceptionModal() {
  pendingReceptionData = "reset";
  document.getElementById("receptionModalTitle").textContent =
    "Nueva recepción";
  resetReceptionFormFields();

  // Hora real del momento en que se abre el modal, no la de carga de página
  const now = new Date();
  now.setMinutes(now.getMinutes() - now.getTimezoneOffset()); // corrige a hora local antes del slice
  const nowValue = now.toISOString().slice(0, 16);
  document.getElementById("entry_date").value = nowValue;
  // "No fechas pasadas" solo al crear (ver ReceptionRequest::rules()): al
  // editar no se fija min, para no bloquear recepciones ya existentes con
  // entry_date en el pasado.
  document.getElementById("entry_date").min = nowValue;
  document.getElementById("exit_date").min = nowValue;

  // Reset inmediato de los Select2 (no depende de que el evento
  // 'show.bs.modal' vuelva a dispararse: si este botón se clickea justo
  // después de cerrar el modal de edición, el modal puede seguir en
  // transición de cierre y Bootstrap ignora el show() -> family_id/pet_id
  // se quedarían mostrando la familia/mascota de la recepción editada).
  ensureSelect2Init();
  $("#family_id").val("").trigger("change");
  $("#pet_id").val("").trigger("change");
}

async function openEditReceptionModal(id) {
  const response = await fetch(route("receptions.edit", id), {
    headers: {
      Accept: "application/json",
      "X-Requested-With": "XMLHttpRequest",
    },
  });

  if (!response.ok) {
    showAlert("No se pudo cargar la recepción.", "Error", "error");
    return;
  }

  pendingReceptionData = await response.json();
  document.getElementById("receptionModalTitle").textContent =
    "Editar recepción";

  bootstrap.Modal.getOrCreateInstance(
    document.getElementById("receptionModal"),
  ).show();
}

async function submitReceptionForm() {
  const form = document.getElementById("receptionForm");
  const receptionId = document.getElementById("reception_id").value;
  const isEdit = !!receptionId;
  const url = isEdit
    ? route("receptions.update", receptionId)
    : route("receptions.store");

  clearReceptionFormErrors();

  const formData = new FormData(form);
  if (isEdit) {
    formData.append("_method", "PATCH");
  }

  const submitBtn = form.querySelector('button[type="submit"]');
  if (submitBtn) submitBtn.disabled = true;

  // Algunos tipos de recepción (ej. Grooming) redirigen a otra pantalla tras
  // guardar: el loader se queda visible durante esa espera y desaparece solo
  // al navegar; en el resto de los casos (éxito sin redirect, error, 422) se
  // cierra explícitamente antes de mostrar el resultado.
  Swal.fire({
    title: "Procesando...",
    text: "Por favor espera mientras procesamos la solicitud.",
    allowOutsideClick: false,
    didOpen: () => {
      Swal.showLoading();
    },
  });

  try {
    const response = await fetch(url, {
      method: "POST",
      headers: {
        Accept: "application/json",
        "X-Requested-With": "XMLHttpRequest",
        "X-CSRF-TOKEN": $("#csrf").attr("content"),
      },
      body: formData,
    });

    if (response.status === 422) {
      Swal.close();
      const data = await response.json();
      renderReceptionFormErrors(data.errors || {});
      return;
    }

    if (!response.ok) {
      Swal.close();
      showAlert("Ocurrió un error al guardar la recepción.", "Error", "error");
      return;
    }

    const data = await response.json();

    if (data.redirect) {
      window.location.href = data.redirect;
      return;
    }

    Swal.close();
    bootstrap.Modal.getOrCreateInstance(
      document.getElementById("receptionModal"),
    ).hide();
    showAlert(data.message || "Recepción guardada correctamente");

    const tablesByType = {
      1: table,
      2: table2,
      3: table3,
      4: table4,
      5: table5,
    };
    const tbl = tablesByType[data.reception_type_id];
    if (tbl) {
      tbl.ajax.reload();
    }
  } catch (error) {
    Swal.close();
    showAlert(
      "Ocurrió un error inesperado. Vuelve a intentar más tarde.",
      "Error",
      "error",
    );
    console.error("Error al guardar recepción:", error);
  } finally {
    if (submitBtn) submitBtn.disabled = false;
  }
}

$(document).ready(function () {
  $("#receptionModal").on("show.bs.modal", function () {
    // antes: 'shown.bs.modal'
    ensureSelect2Init();

    if (pendingReceptionData === "reset") {
      $("#family_id").val("").trigger("change");
      $("#pet_id").val("").trigger("change");
    } else if (pendingReceptionData) {
      applyReceptionData(pendingReceptionData);
    }

    pendingReceptionData = null;
  });

  $("#receptionForm").on("submit", function (e) {
    e.preventDefault();
    submitReceptionForm();
  });
});

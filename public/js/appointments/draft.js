// Borrador de la consulta en localStorage: recupera lo que el médico ya
// había escrito en #NewAppointment si recarga la página o cierra el
// navegador antes de finalizar o trasladar. Es solo persistencia temporal
// en el navegador — nunca sustituye a AppointmentController::store() ni a
// ReceptionTransferController::store(), que siguen siendo la única fuente
// real de guardado. El borrador se borra únicamente cuando uno de los dos
// confirma éxito (ver EndAppointment() en create.js y submitTransfer() en
// transfer.js), nunca antes.
(function () {
  const form = document.getElementById("NewAppointment");
  if (!form) return;

  // El reception_id sale del input oculto que ya trae appointment.form (no
  // se inventa variable ni selector nuevo). No se guarda como parte del
  // borrador: es la clave de scope, no un dato capturado por el médico, y
  // siempre debe reflejar lo que Laravel acaba de renderizar — nunca un
  // valor viejo de localStorage.
  const receptionIdField = form.querySelector('[name="reception_id"]');
  const receptionId = receptionIdField ? receptionIdField.value : null;
  if (!receptionId) return;

  const STORAGE_KEY = `appointment_draft_${receptionId}`;
  const EXCLUDED_NAMES = ["_token", "reception_id"];
  const EXCLUDED_TYPES = ["file", "submit", "button", "reset"];

  function isTrackable(field) {
    return (
      field &&
      field.name &&
      !EXCLUDED_NAMES.includes(field.name) &&
      !EXCLUDED_TYPES.includes(field.type)
    );
  }

  function trackableFields() {
    return Array.from(form.elements).filter(isTrackable);
  }

  function readDraft() {
    try {
      const raw = localStorage.getItem(STORAGE_KEY);
      return raw ? JSON.parse(raw) : null;
    } catch (e) {
      console.warn("No se pudo leer el borrador de la consulta:", e);
      return null;
    }
  }

  function saveDraft() {
    const data = {};
    trackableFields().forEach((field) => {
      if (field.type === "checkbox") {
        data[field.name] = field.checked;
      } else if (field.type === "radio") {
        if (field.checked) data[field.name] = field.value;
      } else {
        data[field.name] = field.value;
      }
    });
    try {
      localStorage.setItem(STORAGE_KEY, JSON.stringify(data));
    } catch (e) {
      console.warn("No se pudo guardar el borrador de la consulta:", e);
    }
  }

  function isFieldEmpty(field) {
    return (
      field.value === null ||
      field.value === undefined ||
      String(field.value).trim() === ""
    );
  }

  // Los valores que ya trajo Laravel (BD u old() tras un error de
  // validación) tienen prioridad: el borrador solo rellena lo que sigue
  // vacío porque la consulta todavía no se ha finalizado.
  function restoreDraft() {
    const draft = readDraft();
    if (!draft) return;

    const handledRadioGroups = new Set();

    trackableFields().forEach((field) => {
      if (!(field.name in draft)) return;

      if (field.type === "radio") {
        if (handledRadioGroups.has(field.name)) return;
        handledRadioGroups.add(field.name);
        if (form.querySelector(`[name="${field.name}"]:checked`)) return;
        const target = form.querySelector(
          `[name="${field.name}"][value="${draft[field.name]}"]`,
        );
        if (target) target.checked = true;
        return;
      }

      if (field.type === "checkbox") {
        if (field.checked) return;
        field.checked = Boolean(draft[field.name]);
        return;
      }

      if (!isFieldEmpty(field)) return;

      field.value = draft[field.name];

      // Select2 (u otro widget que envuelva el <select> nativo) no se entera
      // de un cambio hecho directo sobre .value: hay que disparar 'change',
      // que es la forma documentada de setear su valor programáticamente.
      if (field.tagName === "SELECT" && window.jQuery) {
        window.jQuery(field).trigger("change");
      } else {
        field.dispatchEvent(new Event("change", { bubbles: true }));
      }
    });
  }

  let saveTimeout = null;
  function scheduleSave() {
    clearTimeout(saveTimeout);
    saveTimeout = setTimeout(saveDraft, 300);
  }

  // Delegación sobre el form: cualquier campo nuevo dentro de
  // #NewAppointment queda cubierto automáticamente, sin listeners por id.
  form.addEventListener("input", function (e) {
    if (isTrackable(e.target)) scheduleSave();
  });

  // select/checkbox/radio no disparan 'input'; Select2 dispara 'change' en
  // el <select> nativo (burbujea hasta el form), así que queda cubierto sin
  // escuchar cada campo por separado.
  form.addEventListener("change", function (e) {
    if (isTrackable(e.target)) saveDraft();
  });

  restoreDraft();

  // Expuesta globalmente porque el traslado (transfer.js) también puede
  // dejar el Appointment guardado en BD y necesita poder borrar el
  // borrador; transfer.js no siempre corre en una página con este script
  // cargado, por eso ahí se llama detrás de un typeof-check.
  window.clearAppointmentDraft = function (id) {
    try {
      localStorage.removeItem(`appointment_draft_${id}`);
    } catch (e) {
      console.warn("No se pudo borrar el borrador de la consulta:", e);
    }
  };
})();

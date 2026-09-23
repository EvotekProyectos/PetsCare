function showAlertWithCallback(callbackFunction, id) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "¡Esta acción es irreversible!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, eliminar.',
        cancelButtonText: 'No, cancelar.'
    }).then((result) => {
        if (result.isConfirmed) {
            // Llama a la función de callback si se confirma la alerta
            if (typeof callbackFunction === 'function') {
                callbackFunction(id);
            }
        }
    });
}

function showAlert( text = "Registro guardado correctamente", message = 'Buen trabajo', icon = 'success', showConfirmButton = false, timer = 1500) {
    Swal.fire({
        icon: icon,
        title: message,
        text: text,
        showConfirmButton: showConfirmButton,
        timer: timer
    });
}

const deleteResource = async (url, table = null) => {
    const init = {
        method: "DELETE",
        headers: {
            Accept: "application/json",
            "X-CSRF-TOKEN": $("#csrf").attr("content"),
        }
    };
    try {
        const req = await fetch(url, init);
        if (req.ok) {
            showAlert("Registro eliminado correctamente");
            if (table) {
                table.ajax.reload(); 
            }
        } else {
            showAlert("Parece que no tienes permisos para realizar esta acción.", "Error", "error");
            console.error("Error deleting resource:", req.statusText);
        }
    } catch (error) {
        showAlert("Ocurrió un error inesperado. Vuelve a intentar más tarde.", "Error", "error");
        console.error("Error deleting resource:", error);
    }
};

function formatDate(fecha, incluirHora = false) {
    if (!fecha) return '';

    // Separa "2026-02-26 14:38:00" en fecha y hora
    const [fechaParte, horaParte] = fecha.split(' ');
    const [anio, mes, dia] = fechaParte.split('-');

    let resultado = `${dia}/${mes}/${anio}`;

    if (incluirHora && horaParte) {
        const [hh, mm] = horaParte.split(':');
        resultado += ` ${hh}:${mm}`;
    }

    return resultado;
}

// Columna de fecha para DataTables con orden cronológico correcto.
//
// `render: formatDate` (uso previo) tiene dos problemas: (1) DataTables
// invoca render(data, type, row) y ese `type` ("display"/"sort"/"filter"/
// "type") caía en el parámetro `incluirHora` de formatDate, siempre truthy,
// así que la hora aparecía sin haberla pedido; y (2) al no diferenciar
// "sort" de "display", DataTables ordenaba por el string ya formateado
// "DD/MM/YYYY", que se compara alfabéticamente por el día primero -> el
// orden queda incorrecto (ej. "05/01/2026" antes que "12/12/2025").
//
// Esta función usa datos ortogonales: para "sort"/"type" regresa el string
// ISO tal cual llega del backend ("YYYY-MM-DD HH:mm:ss"), que sí ordena
// cronológicamente como texto; para "display"/"filter" regresa la fecha ya
// formateada con formatDate().
function renderDateColumn(incluirHora = false) {
    return function (data, type) {
        if (type === 'sort' || type === 'type') {
            return data || '';
        }
        return formatDate(data, incluirHora);
    };
}

// Deriva una versión clara del mismo tono de `hex`, mezclándolo con blanco.
// Se usa para los badges "soft" (fondo claro + texto del color original),
// ej. estatus de recepción o motivo de consulta, donde el color base viene
// dinámico de un catálogo (reason.color, status.color) y no hay una versión
// clara guardada en ningún lado.
function lightenColor(hex, amount = 0.85) {
    if (!hex) return '#F1F3F6';

    let color = hex.replace('#', '');
    if (color.length === 3) {
        color = color.split('').map((c) => c + c).join('');
    }

    const num = parseInt(color, 16);
    if (isNaN(num)) return '#F1F3F6';

    const r = (num >> 16) & 255;
    const g = (num >> 8) & 255;
    const b = num & 255;

    const lighten = (channel) => Math.round(channel + (255 - channel) * amount);
    const toHex = (channel) => channel.toString(16).padStart(2, '0');

    return `#${toHex(lighten(r))}${toHex(lighten(g))}${toHex(lighten(b))}`;
}

const deleteUser = (id, table) => {
    const url = route("users.destroy", id);
    deleteResource(url, table);
};

const deleteReason = (id, table) => {
    const url = route("reasons.destroy", id);
    deleteResource(url, table);
};

const deleteArea = (id, table) => {
    const url = route("areas.destroy", id);
    deleteResource(url, table);
};

const deleteRoom = (id, table) => {
    const url = route("rooms.destroy", id);
    deleteResource(url, table);
};

const deleteGenre = (id, table) => {
    const url = route("genres.destroy", id);
    deleteResource(url, table);
}

const deleteReproductiveStatus = (id, table) => {
    const url = route("reproductive-statuses.destroy", id);
    deleteResource(url, table);
}

const deleteFamClassifications = (id, table) => {
    const url = route("fam-classifications.destroy", id);
    deleteResource(url, table);
}

const deletePetClassifications = (id, table) => {
    const url = route("pet-classifications.destroy", id);
    deleteResource(url, table);
}

const deleteShifts = (id, table) => {
    const url = route("shifts.destroy", id);
    deleteResource(url, table);
}

const deletePetsStatuses = (id, table) => {
    const url = route("pets-statuses.destroy", id);
    deleteResource(url, table);
}

const deleteFamily = (id, table) => {
    const url = route("families.destroy", id);
    deleteResource(url, table);
}

const deleteCoverArea = (id, table) => {
    const url = route("cover-areas.destroy", id);
    deleteResource(url, table);
}

const deleteSchedule = (id, table) => {
    const url = route("schedules.destroy", id);
    deleteResource(url, table);
}

const deleteReception = (id, table) => {
    const url = route("receptions.destroy", id);
    deleteResource(url, table);
}

const deletePrescription = (id, table) => {
    const url = route("prescriptions.destroy", id);
    deleteResource(url, table);
}

const deleteService = (id, table) => {
    const url = route("services.destroy", id);
    deleteResource(url, table);
}

const deleteVaccinationCertificate = (id, table) => {
    const url = route("vaccine-certificates.destroy", id);
    deleteResource(url, table);
}



const deleteBudget = (id, table) => {
    const url = route("budgets.destroy", id);
    deleteResource(url, table);
}

const deleteBudgetDetail = (id, table) => {
    const url = route("budget-details.destroy", id);
    deleteResource(url, table);
}

const deleteFollowUpCritic = (id, table) => {
    const url = route("followups-critics.destroy", id);
    deleteResource(url, table);
}
const deleteFollowUpIntern = (id, table) => {
    const url = route("followup-interns.destroy", id);
    deleteResource(url, table);
}
const deleteFollowUpSurgical = (id, table) => {
    const url = route("followup-surgicals.destroy", id);
    deleteResource(url, table);
}

const deleteGroomingStatus = (id, table) => {
    const url = route("grooming-statuses.destroy", id);
    deleteResource(url, table);
}

const deleteGrooming = (id, table) => {
    const url = route("groomings.destroy", id);
    deleteResource(url, table);
}

const deleteCremation = (id, table) => {
    const url = route("cremations.destroy", id);
    deleteResource(url, table);
}

const deleteCmType = (id, table) => {
    const url = route("cm-types.destroy", id);
    deleteResource(url, table);
}

const deleteTagType = (id, table) => {
    const url = route("tag-types.destroy", id);
    deleteResource(url, table);
}

const deletesurgerySchedule = (id, table) => {
    const url = route("surgery-schedules.destroy", id);
    deleteResource(url, table);
}

const deleteCubicle= (id, table) => {
    const url = route("cubicles.destroy", id);
    deleteResource(url, table);
}

const deleteCubicleTypes= (id, table) => {
    const url = route("cubicle-types.destroy", id);
    deleteResource(url, table);
}

const deleteServicieHotel = (id, table) => {
    const url = route("hotels.destroy", id);
    deleteResource(url, table);
}


const deleteControlDate = (id, table) => {
    const url = route("control-dates.destroy", id);
    deleteResource(url, table);
}

/**
 * Arranca un polling estándar de "¿cambió algo?" -> recargar: llama a
 * `checkFn` DE INMEDIATO y luego cada `intervalMs`; si el valor que
 * resuelve (ej. un timestamp `last_update`) cambió respecto al anterior,
 * llama a `onChanged`. Usado por las tablas de los distintos Index/vistas
 * con polling (Recepciones, Hospital, Consultas, Almacén, RedSheets...).
 *
 * Por qué existe (dos bugs reales ya diagnosticados en este proyecto, que
 * cada tabla repetía o no según qué copia del patrón se hubiera escrito):
 *
 * 1) Si la primera vez que se consulta el estado es recién en el primer
 *    tick del setInterval (a los intervalMs, no al momento de entrar a la
 *    vista), cualquier cambio ocurrido en esa primera ventana queda
 *    "absorbido" como línea base sin haberlo comparado nunca contra un
 *    estado anterior -ese primer tick solo GUARDA el valor, no dispara
 *    onChanged-, y ningún tick posterior lo detecta jamás (la línea base
 *    ya lo incluye desde el principio). Para quien usa la página eso es
 *    indistinguible de "la tabla se quedó sin actualizar": nada la refresca
 *    hasta la siguiente carga completa de la página. Por eso este helper
 *    llama a checkFn() de inmediato, antes de armar el setInterval.
 * 2) Si el usuario navega a otra vista y regresa con "Atrás", algunos
 *    navegadores restauran la página entera desde bfcache en vez de
 *    recargarla: el DOM, las variables JS y los timers quedan congelados
 *    tal cual estaban y se reanudan igual, sin que $(document).ready()
 *    vuelva a correr. El polling sigue vivo, pero hasta el próximo tick
 *    programado la tabla sigue mostrando lo que había al salir de la
 *    página -se percibe como "tarda mucho en cargar" al volver-. Por eso
 *    este helper también escucha 'pageshow' y, si event.persisted es
 *    true (restaurada desde bfcache), fuerza un chequeo inmediato.
 *
 * Un tick que falla (red, sesión expirada, 500, etc.) se registra en
 * consola y no detiene los siguientes: el próximo tick programado corre
 * igual, por su cuenta -nunca queda el polling parado en seco por un solo
 * error, ni se generan peticiones duplicadas como reintento-.
 *
 * @param {Object} options
 * @param {() => (any|Promise<any>)} options.checkFn - hace el fetch/ajax
 *   (puede devolver un jqXHR/Promise) y resuelve con el valor de "última
 *   actualización" a comparar contra el anterior.
 * @param {(value: any) => void} options.onChanged - se llama solo cuando
 *   ese valor cambió respecto al anterior (nunca en el primer chequeo).
 * @param {number} [options.intervalMs=30000]
 * @returns {() => void} función para detener el polling (clearInterval +
 *   quita el listener de 'pageshow'), por si la vista lo necesita.
 */
function pollForChanges({ checkFn, onChanged, intervalMs = 30000 }) {
    let lastValue = null;

    function tick() {
        Promise.resolve(checkFn())
            .then(function (value) {
                if (lastValue === null) {
                    lastValue = value;
                    return;
                }
                if (value !== lastValue) {
                    lastValue = value;
                    onChanged(value);
                }
            })
            .catch(function (error) {
                console.error('pollForChanges: chequeo fallido, se reintenta en el próximo ciclo.', error);
            });
    }

    function onPageShow(event) {
        if (event.persisted) {
            tick();
        }
    }

    tick();
    const intervalId = setInterval(tick, intervalMs);
    window.addEventListener('pageshow', onPageShow);

    return function stopPolling() {
        clearInterval(intervalId);
        window.removeEventListener('pageshow', onPageShow);
    };
}

const deleteAdvancePayment = (id, table) => {
    const url = route("advance-payments.destroy", id);
    deleteResource(url, table);
}
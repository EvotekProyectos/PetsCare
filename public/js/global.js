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

const deleteAdvancePayment = (id, table) => {
    const url = route("advance-payments.destroy", id);
    deleteResource(url, table);
}
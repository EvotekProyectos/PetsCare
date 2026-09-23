var table = undefined;
$(document).ready(function () {
    table = $('#table').DataTable({
        ajax: route('notifications.list'),
        responsive: true,
        order: [0, 'desc'],
        columns: [
            {
                data: 'created_at',
                render: function (data) {
                    let date = new Date(data);
                    return date.toLocaleString('es-MX', {
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    });
                }
            },

            {
                data: 'data',
                render: function (data) {

                    return 'La mascota ' + data['pet'] + ' está ' + data['status'] + ' de su servicio de ' + data['type'];
                }

            },

            {
                data: 'read_at',
                render: function (data) {
                    if (!data) return "<span class='text-muted'>No leído</span>";
                    let date = new Date(data);
                    return date.toLocaleString('es-MX', {
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    });
                }
            },
            {
                data: null,
                render: function (data) {
                    // list() (NotificationController) trae TODOS los tipos de
                    // notificación mezclados en esta misma tabla (no filtra
                    // por clase) — hoy AccountAdvancePaymentOverdue y
                    // GroomingStatus, ver app/Notifications/. data.type es el
                    // FQCN completo (ej. "App\Notifications\GroomingStatus");
                    // se manda solo el nombre corto de la clase a message()
                    // para decidir el texto (ver buildWhatsAppMessage) y,
                    // sobre todo, para NO interpolar backslashes crudos
                    // dentro de un onclick: JavaScript trata \N, \A, etc.
                    // como escapes desconocidos en un literal de string y los
                    // descarta en silencio, dejando el FQCN corrompido si se
                    // pasara tal cual.
                    const notificationType = (data.type || '').split('\\').pop();
                    return `

                        <a type="button" class="btn btn-sm text-primary" onclick="message('${data.data['phone']}', '${data.data['pet']}', '${data.data['status']}', '${data.data['type']}', '${notificationType}', '${data.id}');">
                          <span class="ri--whatsapp-fill"></span>
                         </a>`;
                }
            },

        ],
    });
});

 // <a class="btn btn-sm btn-primary"  title="Ver Detalles" href="#"
                        // onclick="Details(${data.data['reception_type_id']}, ${data.data['reception_id']});" >
                        //     <span class="ic--twotone-notifications-none"></span>
                        // </a>

async function Details(Type, ID) {
    event.preventDefault();

    if (Type === 1) {
        window.location.href = route('appointment.historic', ID);

    }
    if (Type === 2) {
        window.location.href = route('hospitalization.historic', ID);

    }
    if (Type === 3) {
        window.location.href = route('grooming.history', ID);

    }
    if (Type === 4) {
        window.location.href = route('hotel.history', ID);

    }
    if (Type === 5) {
        window.location.href = route('cremation.history', ID);

    }
};

// Un mensaje por tipo de notificación (ver app/Notifications/): esta tabla
// mezcla todos los tipos sin filtrar, así que un solo texto fijo queda mal
// para los que no sean de anticipo vencido (ej. avisaría "48 horas sin
// anticipo" sobre una notificación de que la mascota ya está lista de
// grooming). Las claves son el nombre corto de la clase de notificación
// (ver notificationType en el render de la columna de WhatsApp arriba).
const WHATSAPP_MESSAGE_BUILDERS = {
    AccountAdvancePaymentOverdue: (pet) =>
        `Hola, buen día. Nos ponemos en contacto con ustedes respecto a su mascota ${pet}. Han transcurrido más de 48 horas y no hemos recibido anticipo de su servicio de hospitalización. Les agradeceríamos mucho apoyarnos con el pago a la brevedad. Muchas gracias por su atención y comprensión. 🐾`,
    // Fallback genérico (GroomingStatus y cualquier notificación futura que
    // use la misma forma pet/status/type): reutiliza exactamente la misma
    // frase que ya se muestra en la columna de la tabla, no se inventa una
    // redacción nueva.
    default: (pet, status, type) =>
        `Hola, buen día. Le informamos que su mascota ${pet} está ${status} de su servicio de ${type}. 🐾`,
};

function buildWhatsAppMessage(notificationType, pet, status, type) {
    const builder = WHATSAPP_MESSAGE_BUILDERS[notificationType] || WHATSAPP_MESSAGE_BUILDERS.default;
    return builder(pet, status, type);
}

// Mismo endpoint que ya usa el dropdown de notificaciones del navbar
// (layouts/app.blade.php, notifications.read -> NotificationController::
// markAsRead) — no se duplica esa lógica aquí, solo se reutiliza. Por ahora,
// el criterio para marcar como leída en esta tabla es hacer clic en el botón
// de WhatsApp (entrar en contacto con la familia se considera "atendida").
function markNotificationAsRead(notificationId) {
    if (!notificationId) return;

    fetch(route('notifications.read'), {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content'),
        },
        body: JSON.stringify({ id: notificationId }),
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                table.ajax.reload(null, false);
                // fetchNotifications (layouts/app.blade.php) refresca el
                // contador de la campana y el panel de alertas del navbar
                // con esta misma marca, sin esperar a su próximo ciclo de
                // polling de 60s.
                if (typeof fetchNotifications === 'function') {
                    fetchNotifications();
                }
            }
        })
        .catch((error) => console.error("Error al marcar la notificación como leída:", error));
}

async function message(phone, pet, status, type, notificationType, notificationId) {
    event.preventDefault();

    markNotificationAsRead(notificationId);

    const result = await Swal.fire({
        icon: "info",
        title: "Avisar a la Familia",
        text: "Será redirigido a WhatsApp para enviar un mensaje.",
        showConfirmButton: true,
        confirmButtonText: "Continuar",
        showCancelButton: true,
        cancelButtonText: "Cancelar"
    });

    if (result.isConfirmed) {
        const mensaje = buildWhatsAppMessage(notificationType, pet, status, type);

        window.open(
            `https://wa.me/${phone}?text=${encodeURIComponent(mensaje)}`,
            '_blank'
        );
    }
}
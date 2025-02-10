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

                    return 'La mascota ' + data['pet'] + ' esta ' + data['status'] + ' de su servicio de ' + data['type'];
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
                    return `
                        <a class="btn btn-sm btn-primary"  title="Ver Detalles" href="#"
                        onclick="Details(${data.data['reception_type_id']}, ${data.data['reception_id']});" >
                            <span class="ic--twotone-notifications-none"></span>
                        </a>
                        <a type="button" class="btn btn-sm text-primary" onclick="message(${data.data['phone']});">
                          <span class="ri--whatsapp-fill"></span>
                         </a>`;
                }
            },

        ],
    });
});


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

async function message(phone) {
    event.preventDefault();

    const result = await Swal.fire({
        icon: "info",
        title: "Avisar a la Familia",
        text: "Será redirigido a WhatsApp para enviar un mensaje.",
        showConfirmButton: true,
        confirmButtonText: "Continuar",
        showCancelButton: true,
        cancelButtonText: "Cancelar",
    });


    if (result.isConfirmed) {
        // enlace de WhatsApp
        const whatsappURL = `https://wa.me/${phone}?text=Hola!`;

        window.open(whatsappURL, '_blank');
    }
}
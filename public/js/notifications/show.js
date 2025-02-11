function fetchNotifications() {
    $.ajax({
        url: "{{ route('notifications.unreadList') }}",
        type: "GET",
        headers: {
            'X-Requested-With': 'XMLHttpRequest', 
        },
        success: function(data) {
            console.log("Respuesta AJAX:", data);
            let notificationsHtml = "";
            data.forEach(notification => {
                notificationsHtml += `
                    <div class="alert alert-info alert-dismissible fade show notification-alert"
                        data-id="${notification.id}" style="width: 98%" role="alert">
                        <h6 class="text-primary">
                            La mascota ${notification.data.pet} está ${notification.data.status} de su ${notification.data.type}
                        </h6>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>`;
            });
            $("#show-notifications").html(notificationsHtml);
        }
    });
    
}

fetchNotifications();
setInterval(fetchNotifications, 60000);
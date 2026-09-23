<?php

namespace App\Notifications;

use App\Models\Account;
use App\Models\AdvancePayment;
use App\Models\Reception;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

/**
 * Aviso a recepcionistas: una cuenta hospitalaria abierta lleva más de 48
 * horas sin un anticipo nuevo (ver NotifyOverdueAdvancePayments, que decide
 * cuándo disparar esto y evita duplicados vía period_key).
 */
class AccountAdvancePaymentOverdue extends Notification
{
    use Queueable;

    public function __construct(
        private readonly Account $account,
        private readonly Reception $reception,
        private readonly ?AdvancePayment $lastAdvancePayment,
        private readonly Carbon $baseDate,
        private readonly Carbon $dueDate,
        private readonly string $periodKey,
    ) {
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification for the "database" channel.
     *
     * @return array<string, mixed>
     */
    public function toDatabase($notifiable): array
    {
        $pet = $this->account->episode->pet;
        $family = $pet?->family;

        return [
            'account_id' => $this->account->id,
            'episode_id' => $this->account->episode_id,
            // reception_id/reception_type_id: mismas claves que ya usa
            'reception_id' => $this->reception->id,
            'reception_type_id' => (int) $this->reception->reception_type_id,
            'pet_id' => $pet?->id,
            'family_id' => $family?->id,
            'last_advance_payment_id' => $this->lastAdvancePayment?->id,
            'base_date' => $this->baseDate->toDateTimeString(),
            'due_date' => $this->dueDate->toDateTimeString(),
            // Identifica el periodo (cuenta + último anticipo en el momento
            // de notificar) para que NotifyOverdueAdvancePayments no duplique
            // la misma alerta, y para que un anticipo nuevo (que cambia
            // last_advance_payment_id) abra naturalmente un periodo distinto.
            'period_key' => $this->periodKey,
            // pet/status/type/phone: mismas claves que ya usa GroomingStatus
            // (ver app/Notifications/GroomingStatus.php) — permite reutilizar
            // tal cual el render de public/js/notifications/index.js y el
            // banner en vivo de layouts/app.blade.php sin tocarlos.
            'pet' => $pet?->name,
            'status' => 'sin anticipo por más de 48 horas',
            'type' => 'hospitalización',
            'phone' => $family?->phone,
        ];
    }
}

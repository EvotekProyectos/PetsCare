<?php

namespace App\Console\Commands;

use App\Models\Account;
use App\Models\User;
use App\Notifications\AccountAdvancePaymentOverdue;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Notifications\DatabaseNotification;

/**
 * Regla de negocio: una cuenta hospitalaria (Account OPEN cuya reception
 * vigente es de tipo Hospitalización y sigue admitida) que lleve más de 48
 * horas sin un AdvancePayment nuevo debe notificar a las recepcionistas.
 *
 * Fecha base:
 *   - Sin anticipos todavía: entry_date de la reception vigente (fecha real
 *     de admisión a hospitalización, ver Reception::currentForEpisode()).
 *   - Con anticipos: la fecha (Account::latestAdvancePayment()->date) del
 *     más reciente — así un anticipo nuevo reinicia el periodo sin lógica
 *     adicional, simplemente cambia cuál es "el más reciente".
 *
 * Duplicados: period_key = "account:{id}:ap:{last_advance_payment_id|none}"
 * en el propio dato de la notificación. Si ya existe una notification de
 * este tipo con ese period_key, no se repite. Un anticipo nuevo cambia el
 * last_advance_payment_id, así que genera un period_key distinto — el
 * siguiente vencimiento de 48h notifica de nuevo sin duplicar el anterior.
 */
class NotifyOverdueAdvancePayments extends Command
{
    private const HOURS_THRESHOLD = 48;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'advance-payments:notify-overdue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notifica a recepcionistas cuando una cuenta hospitalaria abierta lleva más de 48 horas sin un anticipo nuevo';

    public function handle(): int
    {
        $recepcionistas = User::role('recepcionista')->get();

        if ($recepcionistas->isEmpty()) {
            $this->info('No hay usuarios con rol recepcionista; nada que notificar.');

            return self::SUCCESS;
        }

        $accounts = Account::where('status', Account::STATUS_OPEN)
            ->with([
                'episode.pet.family',
                'episode.receptions.transfersFrom',
                'latestAdvancePayment',
            ])
            ->get();

        $notified = 0;
        $skippedNoHospitalContext = 0;
        $alreadyNotifiedForPeriod = 0;

        foreach ($accounts as $account) {
            $episode = $account->episode;
            if (!$episode || !$episode->pet) {
                $skippedNoHospitalContext++;
                continue;
            }

            // Reception vigente del episodio: la que no ha sido trasladada a
            // otra (mismo criterio que Reception::currentForEpisode(), pero
            // resuelto sobre la colección ya eager-loaded para no repetir
            // una query por cuenta).
            $currentReception = $episode->receptions->first(
                fn ($reception) => $reception->transfersFrom->isEmpty()
            );

            $isOpenHospitalization = $currentReception
                && (int) $currentReception->reception_type_id === 2
                && $currentReception->exit_date === null;

            if (!$isOpenHospitalization) {
                $skippedNoHospitalContext++;
                continue;
            }

            $lastAdvancePayment = $account->latestAdvancePayment;
            $baseDate = $lastAdvancePayment
                ? Carbon::parse($lastAdvancePayment->date)
                : ($currentReception->entry_date ? Carbon::parse($currentReception->entry_date) : null);

            if (!$baseDate) {
                // Sin fecha de admisión ni anticipos: no hay información
                // suficiente para calcular el vencimiento.
                continue;
            }

            $dueDate = $baseDate->copy()->addHours(self::HOURS_THRESHOLD);

            if (now()->lt($dueDate)) {
                continue;
            }

            $periodKey = 'account:' . $account->id . ':ap:' . ($lastAdvancePayment->id ?? 'none');

            $alreadyNotified = DatabaseNotification::where('type', AccountAdvancePaymentOverdue::class)
                ->where('data->period_key', $periodKey)
                ->exists();

            if ($alreadyNotified) {
                $alreadyNotifiedForPeriod++;
                continue;
            }

            $notification = new AccountAdvancePaymentOverdue(
                $account,
                $currentReception,
                $lastAdvancePayment,
                $baseDate,
                $dueDate,
                $periodKey,
            );

            foreach ($recepcionistas as $recepcionista) {
                $recepcionista->notify($notification);
            }

            $notified++;
        }

        $this->info("Cuentas revisadas: {$accounts->count()}");
        $this->info("Cuentas notificadas (nuevo periodo vencido): {$notified}");
        $this->info("Sin contexto de hospitalización abierta: {$skippedNoHospitalContext}");
        $this->info("Ya notificadas para este mismo periodo: {$alreadyNotifiedForPeriod}");

        return self::SUCCESS;
    }
}

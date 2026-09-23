<?php

namespace App\Console\Commands;

use App\Models\AdvancePayment;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * Resuelve account_id para AdvancePayment históricos (creados antes de que
 * existiera la columna), vía la misma cadena que usa
 * AdvancePaymentController::store() para los nuevos:
 * reception_id -> reception.episode_id -> episode.account.
 *
 * Sigue el mismo patrón que episodes:backfill (BackfillEpisodesForReceptions):
 * --dry-run no escribe nada, y solo reporta.
 */
class AdvancePaymentsBackfillAccounts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'advance-payments:backfill-accounts {--dry-run : Solo reporta, no escribe nada}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Liga cada AdvancePayment histórico (sin account_id) con la Account de su reception, cuando pueda determinarse con seguridad';

    public function handle(): int
    {
        $dryRun = (bool) $this->option('dry-run');

        $total = AdvancePayment::count();
        $yaTienenCuenta = AdvancePayment::whereNotNull('account_id')->count();

        $pendientes = AdvancePayment::whereNull('account_id')
            ->with('reception.episode.account')
            ->get();

        $resolvibles = collect();
        $ambiguos = collect();
        $sinCuenta = collect();

        foreach ($pendientes as $advancePayment) {
            $reception = $advancePayment->reception;
            $account = $reception?->episode?->account;

            if (!$account) {
                $sinCuenta->push($advancePayment);
                continue;
            }

            // No cambia CUÁL es la cuenta (la cadena FK es 1:1, sin ambigüedad
            // real posible) — solo marca para revisión manual los casos donde
            // la fecha del anticipo cae fuera de la ventana de vida de la
            // cuenta (antes de abrirse el episodio, o después de cerrarse la
            // cuenta), tal como se identificó en la investigación previa.
            // AdvancePayment.date no está en $casts (llega como string), a
            // diferencia de Episode.opened_at/Account.closed_at que sí son
            // datetime cast — se normaliza aquí para poder comparar.
            $episode = $reception->episode;
            $fecha = $advancePayment->date ? Carbon::parse($advancePayment->date) : null;
            $fueraDeVentana =
                ($episode->opened_at && $fecha && $fecha->lt($episode->opened_at))
                || ($account->closed_at && $fecha && $fecha->gt($account->closed_at));

            if ($fueraDeVentana) {
                $ambiguos->push([$advancePayment, $account]);
            } else {
                $resolvibles->push([$advancePayment, $account]);
            }
        }

        $this->info("Total de anticipos: {$total}");
        $this->info("Ya tenían account_id: {$yaTienenCuenta}");
        $this->info('Pendientes analizados (sin account_id): ' . $pendientes->count());
        $this->info('Resolvibles de forma segura: ' . $resolvibles->count());
        $this->info('Ambiguos (fecha fuera de la ventana de la cuenta, se ligan pero quedan marcados para revisión): ' . $ambiguos->count());
        $this->info('Sin cuenta identificable (quedan con account_id = NULL): ' . $sinCuenta->count());

        if ($sinCuenta->isNotEmpty()) {
            $this->warn('IDs sin cuenta identificable: ' . $sinCuenta->pluck('id')->implode(', '));
        }
        if ($ambiguos->isNotEmpty()) {
            $this->warn('IDs ambiguos (revisar manualmente): ' . collect($ambiguos)->pluck('0.id')->implode(', '));
        }

        if ($dryRun) {
            $this->info('Dry-run: no se escribió nada. Se actualizarían ' . ($resolvibles->count() + $ambiguos->count()) . ' registro(s).');
            return self::SUCCESS;
        }

        $actualizados = 0;
        DB::transaction(function () use ($resolvibles, $ambiguos, &$actualizados) {
            foreach ($resolvibles->concat($ambiguos) as [$advancePayment, $account]) {
                $advancePayment->update(['account_id' => $account->id]);
                $actualizados++;
            }
        });

        $this->info("Listo. {$actualizados} anticipo(s) actualizado(s).");

        return self::SUCCESS;
    }
}

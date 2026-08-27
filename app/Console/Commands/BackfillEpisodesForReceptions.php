<?php

namespace App\Console\Commands;

use App\Models\Account;
use App\Models\Episode;
use App\Models\Reception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class BackfillEpisodesForReceptions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'episodes:backfill {--dry-run : Solo muestra cuántas recepciones se verían afectadas, sin escribir nada}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crea un Episode + Account 1:1 retroactivo para cada Reception existente que aún no tenga episode_id';

    public function handle(): int
    {
        $dryRun = (bool) $this->option('dry-run');

        $total = Reception::whereNull('episode_id')->count();

        if ($total === 0) {
            $this->info('No hay recepciones sin episode_id. Nada que hacer.');
            return self::SUCCESS;
        }

        if ($dryRun) {
            $this->info("Se crearían {$total} Episode(s) + Account(s) (dry-run, no se escribió nada).");
            return self::SUCCESS;
        }

        $this->info("Procesando {$total} recepción(es) sin episode_id...");
        $bar = $this->output->createProgressBar($total);
        $created = 0;

        Reception::whereNull('episode_id')->orderBy('id')->chunkById(200, function ($receptions) use (&$created, $bar) {
            foreach ($receptions as $reception) {
                DB::transaction(function () use ($reception, &$created) {
                    $episode = Episode::create([
                        'pet_id' => $reception->pet_id,
                        'status' => $reception->exit_date ? Episode::STATUS_CLOSED : Episode::STATUS_OPEN,
                        'opened_at' => $reception->entry_date,
                        'closed_at' => $reception->exit_date,
                        'notes' => "Backfill automático desde reception #{$reception->id}",
                    ]);

                    Account::create([
                        'episode_id' => $episode->id,
                        'status' => Account::STATUS_OPEN,
                    ]);

                    // withoutEvents: el ReceptionObserver espera un usuario autenticado
                    // (Auth::user()->id) para registrar el log de edición, lo cual no aplica
                    // en un comando de consola.
                    Reception::withoutEvents(function () use ($reception, $episode) {
                        $reception->update(['episode_id' => $episode->id]);
                    });

                    $created++;
                });

                $bar->advance();
            }
        });

        $bar->finish();
        $this->newLine();
        $this->info("Listo. {$created} Episode(s) + Account(s) creados.");

        return self::SUCCESS;
    }
}

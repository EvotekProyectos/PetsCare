<?php

namespace App\Observers;

use App\Models\Account;
use App\Services\ReceptionVersionService;

/**
 * Solo incrementa la versión global de Recepciones (ver
 * ReceptionVersionService) -Account no tenía NINGÚN Observer antes de este
 * paso, así que un cambio de estatus de cuenta (badge "Cuenta":
 * Abierta/Cerrada/Pagada, ver renderCuentaBadge() en receptions/index.js)
 * nunca disparaba un reload del DataTable hasta ahora-. No se agrega
 * auditoría nueva aquí: no es parte de este paso.
 *
 * created: una Account nueva siempre es un cambio real (no hay "status
 * previo" que comparar).
 * updated: acotado a wasChanged('status') -es el único campo de Account que
 * renderCuentaBadge() muestra en las tablas de Recepciones (ver
 * ReceptionController::list(), episode.account.status). Evita incrementar
 * por cambios en columnas de Account que Recepciones no muestra.
 * deleted: Account usa SoftDeletes -un soft-delete saca la cuenta de
 * episode.account (relación hasOne sin trashed()), lo cual cambia el badge-.
 */
class AccountObserver
{
    public function __construct(private ReceptionVersionService $versionService)
    {
    }

    public function created(Account $account): void
    {
        $this->versionService->touch();
    }

    public function updated(Account $account): void
    {
        if ($account->wasChanged('status')) {
            $this->versionService->touch();
        }
    }

    public function deleted(Account $account): void
    {
        $this->versionService->touch();
    }
}

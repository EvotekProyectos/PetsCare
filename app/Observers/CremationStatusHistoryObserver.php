<?php

namespace App\Observers;

use App\Models\CremationStatusHistory;
use App\Services\ReceptionVersionService;

/**
 * Solo incrementa la versión global de Recepciones (ver
 * ReceptionVersionService) -esta tabla no tenía Observer antes de este paso-.
 * No se agrega auditoría nueva aquí: no es parte de este paso.
 *
 * created/updated: igual que los observers hermanos de status-history.
 * deleted: la migración agrega `deleted_at` pero el modelo
 * CremationStatusHistory NO usa el trait SoftDeletes -->delete() es un
 * borrado real-, y de todas formas cambia qué fila resuelve
 * Reception::currentStatusCremation() (hasOne()->latestOfMany()).
 */
class CremationStatusHistoryObserver
{
    public function __construct(private ReceptionVersionService $versionService)
    {
    }

    public function created(CremationStatusHistory $cremationStatusHistory): void
    {
        $this->versionService->touch();
    }

    public function updated(CremationStatusHistory $cremationStatusHistory): void
    {
        $this->versionService->touch();
    }

    public function deleted(CremationStatusHistory $cremationStatusHistory): void
    {
        $this->versionService->touch();
    }
}

<?php

namespace App\Observers;

use App\Models\HospitalizationStatusHistory;
use App\Services\ReceptionVersionService;

/**
 * Solo incrementa la versión global de Recepciones (ver
 * ReceptionVersionService) -esta tabla no tenía Observer antes de este paso-.
 * No se agrega auditoría nueva aquí: no es parte de este paso.
 *
 * created/updated: igual que los observers hermanos de status-history.
 * deleted: la migración agrega `deleted_at` pero el modelo
 * HospitalizationStatusHistory NO usa el trait SoftDeletes -->delete() es un
 * borrado real-, y de todas formas cambia qué fila resuelve
 * Reception::currentHospitalizationStatus() (hasOne()->latestOfMany('changed_at')).
 */
class HospitalizationStatusHistoryObserver
{
    public function __construct(private ReceptionVersionService $versionService)
    {
    }

    public function created(HospitalizationStatusHistory $hospitalizationStatusHistory): void
    {
        $this->versionService->touch();
    }

    public function updated(HospitalizationStatusHistory $hospitalizationStatusHistory): void
    {
        $this->versionService->touch();
    }

    public function deleted(HospitalizationStatusHistory $hospitalizationStatusHistory): void
    {
        $this->versionService->touch();
    }
}

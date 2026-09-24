<?php

namespace App\Observers;

use App\Models\HotelStatusHistory;
use App\Services\ReceptionVersionService;

/**
 * Solo incrementa la versión global de Recepciones (ver
 * ReceptionVersionService) -esta tabla no tenía Observer antes de este paso,
 * a diferencia de ReceptionStatusHistory/GroomingStatusHistory, que además
 * ya llevan auditoría (Log::create). No se agrega auditoría nueva aquí: no
 * es parte de este paso.
 *
 * created/updated: igual que los observers hermanos de status-history,
 * cubre tanto el alta de un estatus nuevo (caso normal) como una eventual
 * corrección en un registro existente.
 * deleted: aunque la migración de esta tabla agrega `deleted_at`, el modelo
 * HotelStatusHistory NO usa el trait SoftDeletes -por lo tanto ->delete()
 * es un borrado real, no uno lógico-. Aun así sigue siendo relevante: borra
 * una fila que pudo ser la más reciente para Reception::currentHotelStatus()
 * (hasOne()->latestOfMany('changed_at')), cambiando qué fila resuelve esa
 * relación.
 */
class HotelStatusHistoryObserver
{
    public function __construct(private ReceptionVersionService $versionService)
    {
    }

    public function created(HotelStatusHistory $hotelStatusHistory): void
    {
        $this->versionService->touch();
    }

    public function updated(HotelStatusHistory $hotelStatusHistory): void
    {
        $this->versionService->touch();
    }

    public function deleted(HotelStatusHistory $hotelStatusHistory): void
    {
        $this->versionService->touch();
    }
}

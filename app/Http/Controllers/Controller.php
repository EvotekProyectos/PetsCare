<?php

namespace App\Http\Controllers;

use App\Models\Reception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * Impide registrar nuevos servicios sobre una recepción que ya fue
     * trasladada a otra
     */
    protected function guardReceptionNotTransferred(Reception $reception): void
    {
        if ($reception->isTransferred()) {
            abort(422, 'No se pueden registrar más servicios en una recepción que ya fue trasladada.');
        }
    }

    /**
     * Impide registrar nuevos servicios hospitalarios (RedSheet, directos o
     * vía conversión de presupuesto) una vez que el paciente ya fue dado de
     * alta. Reutiliza currentHospitalizationStatus() (misma fuente de
     * verdad que RedSheetController::entry() usa para decidir si el
     * formulario de captura es editable) — no se crea ningún estado nuevo.
     * Para recepciones que no son de Hospitalización, currentHospitalizationStatus
     * siempre es null, así que este guard no las afecta.
     */
    protected function guardReceptionNotDischarged(Reception $reception): void
    {
        $statusName = $reception->currentHospitalizationStatus?->hospitalizationStatus?->name;

        if ($statusName === 'Dado de alta') {
            abort(422, 'El paciente ya se encuentra dado de alta del hospital.');
        }
    }
}

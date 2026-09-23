<?php

namespace App\Http\Controllers;

use App\Http\Requests\BudgetConversionRequest;
use App\Models\Budget;
use App\Models\Reception;
use App\Models\RedSheet;
use App\Services\BudgetConversionService;

/**
 * Conversión de un Presupuesto (creado durante la Consulta, ver
 * BudgetController/BudgetDetailController) en servicios reales de
 * Hospitalización (RedSheet) — ver BudgetConversionService. El presupuesto
 * en sí no cambia de flujo: sigue siendo una cotización; esto solo evita
 * que el médico vuelva a capturar a mano lo que ya había presupuestado.
 *
 * La "aceptación verbal" del cliente no se registra como un paso aparte:
 * el propio traslado a Hospitalización (ReceptionTransferController, sin
 * modificar) es la señal de que el presupuesto fue aceptado — por eso la
 * elegibilidad se resuelve por episodio, nunca por un estado propio del Budget.
 */
class BudgetConversionController extends Controller
{
    /**
     * Si existe al menos un presupuesto FIRMADO en el episodio con algo que
     * convertir, para decidir si el botón "Convertir presupuesto a
     * servicios" debe mostrarse.
     */
    public function eligible(int $id, BudgetConversionService $service)
    {
        $this->authorize('viewAny', Budget::class);

        $reception = Reception::findOrFail($id);

        return response()->json([
            'eligible' => $service->hasEligibleBudget($reception),
        ]);
    }

    /**
     * Líneas de TODOS los presupuestos firmados del episodio, con su estado
     * (convertida/no disponible/posible duplicado) para el modal de
     * selección — nunca de presupuestos sin firmar.
     */
    public function details(int $id, BudgetConversionService $service)
    {
        $this->authorize('viewAny', Budget::class);

        $reception = Reception::findOrFail($id);

        return response()->json([
            'details' => $service->detailsFor($reception),
        ]);
    }

    /**
     * Convierte las líneas seleccionadas en RedSheet reales de esta
     * Hospitalización. Mismo permiso que agregar un servicio manualmente
     * (RedSheetController::store()), porque el efecto final es exactamente
     * ese: un RedSheet nuevo. BudgetConversionService::convert() valida,
     * línea por línea, que cada una pertenezca a un presupuesto de este
     * episodio y que ese presupuesto esté firmado — no se confía en que el
     * front solo haya mandado ids "correctos".
     */
    public function convert(BudgetConversionRequest $request, int $id, BudgetConversionService $service)
    {
        $this->authorize('create', RedSheet::class);

        $reception = Reception::findOrFail($id);
        $this->guardReceptionNotTransferred($reception);
        $this->guardReceptionNotDischarged($reception);

        $result = $service->convert($request->budget_detail_ids, $reception, auth()->id());

        return response()->json($result);
    }
}

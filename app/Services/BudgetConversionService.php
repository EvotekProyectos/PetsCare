<?php

namespace App\Services;

use App\Models\Budget;
use App\Models\BudgetDetail;
use App\Models\Producto;
use App\Models\Reception;
use App\Models\RedSheet;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Convierte líneas de un Presupuesto (Budget/BudgetDetail, creado durante
 * la Consulta) en servicios reales de Hospitalización (RedSheet), evitando
 * capturar dos veces lo mismo. El presupuesto sigue siendo una cotización:
 * esta clase solo reutiliza sus líneas para generar RedSheet, exactamente
 * como si el médico los hubiera agregado a mano desde Hospitalización (ver
 * RedSheetController::store()). El precio de cobro sigue siendo siempre el
 * vigente en Firebird al cerrar la cuenta (AccountStatementService) — aquí
 * nunca se copia budget_details.price como precio de cobro.
 */
class BudgetConversionService
{
    /**
     * Presupuestos FIRMADOS (Budget::isSigned(), ver migración
     * add_signed_at_to_budgets_table) de esta Hospitalización: el Budget
     * queda ligado al reception_id de la Consulta (antes del traslado), no al
     * de esta Hospitalización — ambas Reception comparten el mismo episode_id
     * (ver ReceptionTransferController::store()). Se busca por episodio, no
     * por reception_id, precisamente porque son recepciones distintas.
     *
     * Una consulta puede tener varios Budget (uno por cada vez que se abrió
     * el modal de presupuesto, ver BudgetDetailController::storeBatch()):
     * SOLO los firmados son elegibles, cada uno aporta sus propios servicios
     * -nunca "el último Budget creado", que es justo el bug que esto corrige-.
     */
    private function signedBudgetsFor(Reception $hospitalizationReception): Collection
    {
        if (!$hospitalizationReception->episode_id) {
            return collect();
        }

        $episodeReceptionIds = Reception::where('episode_id', $hospitalizationReception->episode_id)
            ->pluck('id');

        return Budget::whereIn('reception_id', $episodeReceptionIds)
            ->whereNotNull('signed_at')
            ->orderBy('id')
            ->get();
    }

    /**
     * Si existe al menos un presupuesto firmado con algo que convertir, para
     * decidir si el botón "Convertir presupuesto a servicios" debe mostrarse.
     */
    public function hasEligibleBudget(Reception $hospitalizationReception): bool
    {
        $budgetIds = $this->signedBudgetsFor($hospitalizationReception)->pluck('id');

        if ($budgetIds->isEmpty()) {
            return false;
        }

        return BudgetDetail::whereIn('budget_id', $budgetIds)->exists();
    }

    /**
     * Líneas de TODOS los presupuestos firmados de esta Hospitalización, con
     * su estado para la pantalla de selección: convertida, no disponible en
     * catálogo, o posible duplicado (ya existe un servicio equivalente sin
     * eliminar en esta Hospitalización). Cada línea trae su budget_id/
     * budget_date para que el front las pueda agrupar visualmente por
     * presupuesto de origen.
     */
    public function detailsFor(Reception $hospitalizationReception): Collection
    {
        $budgets = $this->signedBudgetsFor($hospitalizationReception)->keyBy('id');

        if ($budgets->isEmpty()) {
            return collect();
        }

        $details = BudgetDetail::whereIn('budget_id', $budgets->keys())->orderBy('budget_id')->orderBy('id')->get();

        $existingProductIds = RedSheet::where('reception_id', $hospitalizationReception->id)
            ->whereNull('removed_at')
            ->get(['service_type_id', 'lab_type_id', 'imaging_type_id'])
            ->flatMap(fn ($row) => array_filter([$row->service_type_id, $row->lab_type_id, $row->imaging_type_id]))
            ->unique();

        $productIds = $details->map(fn ($detail) => $this->typeAndProductId($detail)[1])->filter()->unique()->values();
        $productos = Producto::whereIn('ARTICULO_ID', $productIds)->get()->keyBy('ARTICULO_ID');

        return $details->map(function (BudgetDetail $detail) use ($existingProductIds, $productos, $budgets) {
            [$type, $productId] = $this->typeAndProductId($detail);
            $producto = $productId ? $productos->get($productId) : null;
            $budget = $budgets->get($detail->budget_id);

            return [
                'id' => $detail->id,
                'budget_id' => $detail->budget_id,
                'budget_date' => $budget?->date ? Carbon::parse($budget->date)->format('d/m/Y') : null,
                'type' => $type,
                'product_id' => $productId,
                'name' => $producto->NOMBRE ?? 'Servicio no encontrado en el catálogo',
                'price' => $detail->price,
                'notes' => $detail->notes,
                'is_converted' => $detail->isConverted(),
                'converted_at' => $detail->converted_at?->format('d/m/Y H:i'),
                'is_available' => $producto !== null && $producto->ESTATUS === 'A',
                'possible_duplicate' => $productId !== null && $existingProductIds->contains($productId),
            ];
        })->values();
    }

    /**
     * Convierte las líneas seleccionadas (y no convertidas todavía) en
     * RedSheet reales de $hospitalizationReception. Idempotente: una línea
     * con converted_at ya poblado nunca vuelve a crear un RedSheet, sin
     * importar cuántas veces se reintente. Devuelve qué se convirtió y qué
     * se omitió (con motivo), sin abortar todo el lote por una línea inválida.
     *
     * Cada línea se valida contra SU PROPIO presupuesto (no se recibe ni se
     * confía en un budget_id único de antemano): esto es justo lo que evita
     * que, por una petición manipulada o un id equivocado, se convierta un
     * servicio de un presupuesto que nunca se firmó.
     */
    public function convert(array $budgetDetailIds, Reception $hospitalizationReception, int $vetId): array
    {
        $dayCount = $this->currentDayCount($hospitalizationReception);
        $converted = [];
        $skipped = [];

        $episodeReceptionIds = $hospitalizationReception->episode_id
            ? Reception::where('episode_id', $hospitalizationReception->episode_id)->pluck('id')
            : collect();

        DB::transaction(function () use ($budgetDetailIds, $hospitalizationReception, $vetId, $dayCount, $episodeReceptionIds, &$converted, &$skipped) {
            $details = BudgetDetail::whereIn('id', $budgetDetailIds)
                ->with('budget')
                ->lockForUpdate()
                ->get();

            foreach ($details as $detail) {
                $budget = $detail->budget;

                // El presupuesto de esta línea debe pertenecer al mismo
                // episodio que la hospitalización Y estar firmado — ninguna
                // de las dos cosas se asume, se revisa aquí mismo, por línea.
                if (!$budget || !$episodeReceptionIds->contains($budget->reception_id)) {
                    $skipped[] = ['id' => $detail->id, 'reason' => 'El servicio no pertenece a un presupuesto de esta hospitalización.'];
                    continue;
                }

                if (!$budget->isSigned()) {
                    $skipped[] = ['id' => $detail->id, 'reason' => 'El presupuesto al que pertenece este servicio todavía no ha sido firmado.'];
                    continue;
                }

                if ($detail->isConverted()) {
                    $skipped[] = ['id' => $detail->id, 'reason' => 'Ya había sido convertida anteriormente.'];
                    continue;
                }

                [$type, $productId] = $this->typeAndProductId($detail);

                if (!$productId) {
                    $skipped[] = ['id' => $detail->id, 'reason' => 'La línea no tiene un servicio/producto válido.'];
                    continue;
                }

                $productoActivo = Producto::where('ARTICULO_ID', $productId)->where('ESTATUS', 'A')->exists();
                if (!$productoActivo) {
                    $skipped[] = ['id' => $detail->id, 'reason' => 'El servicio ya no está disponible en el catálogo.'];
                    continue;
                }

                $field = match ($type) {
                    'service' => 'service_type_id',
                    'lab' => 'lab_type_id',
                    'img' => 'imaging_type_id',
                };

                $redSheet = RedSheet::create([
                    'reception_id' => $hospitalizationReception->id,
                    'day_count' => $dayCount,
                    'vet_id' => $vetId,
                    $field => $productId,
                ]);

                $detail->update([
                    'converted_at' => now(),
                    'converted_to_type' => RedSheet::class,
                    'converted_to_id' => $redSheet->id,
                ]);

                $converted[] = ['id' => $detail->id, 'red_sheet_id' => $redSheet->id];
            }
        });

        return ['converted' => $converted, 'skipped' => $skipped];
    }

    /**
     * type/product_id (ARTICULO_ID) de una línea, según cuál de sus 3
     * columnas mutuamente excluyentes esté poblada (mismo patrón que
     * BudgetDetailController::storeBatch()).
     */
    private function typeAndProductId(BudgetDetail $detail): array
    {
        if ($detail->service_id) {
            return ['service', $detail->service_id];
        }

        if ($detail->lab_id) {
            return ['lab', $detail->lab_id];
        }

        if ($detail->img_id) {
            return ['img', $detail->img_id];
        }

        return [null, null];
    }

    /**
     * Mismo cálculo que red-sheet/create.blade.php ($dayCount, el día actual
     * de la hospitalización) — reproducido aquí para no depender de que el
     * frontend lo calcule/envíe correctamente.
     */
    private function currentDayCount(Reception $reception): int
    {
        $entryDate = Carbon::parse($reception->entry_date)->startOfDay();
        $today = Carbon::now()->startOfDay();

        return $entryDate->lte($today) ? $entryDate->diffInDays($today) + 1 : 1;
    }
}

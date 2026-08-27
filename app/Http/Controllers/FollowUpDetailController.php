<?php

namespace App\Http\Controllers;

use App\Models\FollowUp;
use App\Models\FollowupIntern;
use App\Models\FollowupsCritic;
use App\Models\FollowupSurgical;
use Illuminate\Http\JsonResponse;

/**
 * Endpoint único de solo lectura para pintar el detalle de cualquiera de los
 * 4 tipos de seguimiento en su modal correspondiente.
 */
class FollowUpDetailController extends Controller
{
    private const MODELS = [
        'follow_up' => FollowUp::class,
        'followup_surgical' => FollowupSurgical::class,
        'followups_critic' => FollowupsCritic::class,
        'followup_intern' => FollowupIntern::class,
    ];

    public function show(string $type, int $id): JsonResponse
    {
        $modelClass = self::MODELS[$type] ?? null;
        abort_unless($modelClass, 404, 'Tipo de seguimiento no válido.');

        $record = $modelClass::findOrFail($id);
        $this->authorize('view', $record);

        return response()->json($record);
    }
}

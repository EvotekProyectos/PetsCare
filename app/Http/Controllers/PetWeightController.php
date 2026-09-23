<?php

namespace App\Http\Controllers;

use App\Http\Requests\PetWeightRequest;
use App\Models\Pet;
use App\Models\PetWeight;
use App\Models\Reception;
use Illuminate\Support\Facades\DB;

/**
 * Class PetWeightController
 *
 * Historial de peso de mascotas (ver PetWeight): "el peso no se edita, se
 * registra una nueva medición". pets.weight se conserva como peso actual
 * únicamente por compatibilidad con el resto del sistema.
 *
 * @package App\Http\Controllers
 */
class PetWeightController extends Controller
{
    /**
     * Registra una nueva medición de peso y actualiza pets.weight (peso
     * actual) en la misma transacción — nunca se sobrescribe ni se elimina
     * una medición anterior.
     */
    public function store(PetWeightRequest $request)
    {
        $data = $request->validated();

        $petWeight = DB::transaction(function () use ($data) {
            $petWeight = PetWeight::create([
                'pet_id' => $data['pet_id'],
                'reception_id' => $data['reception_id'] ?? null,
                'weight' => $data['weight'],
                'measured_at' => now(),
                'created_by' => auth()->id(),
            ]);

            Pet::where('id', $data['pet_id'])->update(['weight' => $data['weight']]);

            return $petWeight;
        });

        return response()->json([
            'success' => true,
            'id' => $petWeight->id,
            'weight' => $petWeight->weight,
            'measured_at' => $petWeight->measured_at->format('d/m/Y H:i'),
        ]);
    }

    /**
     * Historial completo de una mascota, más reciente primero. Médico y
     * recepción se resuelven vía relaciones (reception->vet) en vez de
     * duplicar esos datos en pet_weights.
     */
    public function history(int $petId)
    {
        $weights = PetWeight::where('pet_id', $petId)
            ->with(['reception.vet', 'reception.receptionType', 'reception.reason', 'createdBy'])
            ->orderByDesc('measured_at')
            ->get();

        return response()->json([
            'weights' => $weights->values()->map(function ($weight, $index) {
                return [
                    'id' => $weight->id,
                    'weight' => $weight->weight,
                    'measured_at' => $weight->measured_at->format('d/m/Y H:i'),
                    'reception_id' => $weight->reception_id,
                    // Tipo de recepción y, si es Consulta (id=1), su motivo —
                    // en vez del id crudo de la recepción, que no dice nada
                    // por sí solo.
                    'reception_label' => $this->receptionLabel($weight->reception),
                    // Solo en Consulta hay un M.V.Z. relevante (reception->vet);
                    // en cualquier otro caso (Grooming/Hotel/Cremación,
                    // Hospitalización o registrado desde el Historial sin
                    // recepción) se muestra quién registró la medición
                    // (created_by) — el front cambia el título de la columna
                    // acorde (ver openWeightHistoryModal en pet-weights/index.js).
                    'registered_by' => $this->registeredBy($weight),
                    'is_current' => $index === 0,
                ];
            }),
        ]);
    }

    /**
     * "Consulta - Vacunación", "Hospitalización", etc. null si la medición
     * no tiene recepción asociada (el front la muestra como "No asociada").
     */
    private function receptionLabel(?Reception $reception): ?string
    {
        if (!$reception) {
            return null;
        }

        $label = $reception->receptionType?->name ?? 'Recepción';

        if ((int) $reception->reception_type_id === 1 && $reception->reason) {
            $label .= ' - ' . $reception->reason->name;
        }

        return $label;
    }

    /**
     * M.V.Z. de la consulta cuando la medición viene de una (reception->vet,
     * ya existente, no se duplica); en cualquier otro caso, el usuario que
     * registró la medición (pet_weights.created_by).
     */
    private function registeredBy(PetWeight $weight): ?string
    {
        if ((int) ($weight->reception?->reception_type_id ?? 0) === 1) {
            return $weight->reception?->vet?->name;
        }

        return $weight->createdBy?->name;
    }
}

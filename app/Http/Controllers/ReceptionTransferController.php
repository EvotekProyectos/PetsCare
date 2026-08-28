<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReceptionTransferRequest;
use App\Models\Appointment;
use App\Models\AttentionStatus;
use App\Models\Episode;
use App\Models\GroomingStatus;
use App\Models\GroomingStatusHistory;
use App\Models\HospitalizationStatus;
use App\Models\HospitalizationStatusHistory;
use App\Models\HotelStatus;
use App\Models\HotelStatusHistory;
use App\Models\Reception;
use App\Models\ReceptionEvent;
use App\Models\ReceptionStatusHistory;
use App\Models\ReceptionTransfer;
use App\Models\ReceptionType;
use App\Services\VoucherService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Class ReceptionTransferController
 *
 * Traslada una Reception a otro tipo de atención DENTRO del mismo Episode
 * (comparte episode_id/Account con la recepción de origen).
 *
 * @package App\Http\Controllers
 */
class ReceptionTransferController extends Controller
{
    public function store(ReceptionTransferRequest $request, int $id, VoucherService $voucherService)
    {
        $origin = Reception::findOrFail($id);
        $this->authorize('update', $origin);

        if ((int) $origin->reception_type_id === 5) {
            abort(422, 'Una recepción de Cremación no puede ser origen de un traslado.');
        }

        if ($origin->isTransferred()) {
            abort(422, 'Esta recepción ya fue trasladada anteriormente.');
        }

        $destinationTypeId = (int) $request->reception_type_id;

        $destination = DB::transaction(function () use ($origin, $destinationTypeId, $request, $voucherService) {
            $destination = Reception::create([
                'pet_id' => $origin->pet_id,
                'family_id' => $origin->family_id,
                'reception_type_id' => $destinationTypeId,
                'veterinarian_id' => auth()->id(),
                'entry_date' => now(),
                'episode_id' => $origin->episode_id,
                'admission_type_id' => $destinationTypeId === 2 ? $request->admission_type_id : null,
                'area_id' => $destinationTypeId === 2 ? $request->area_id : null,
                'reason_id' => $destinationTypeId === 1 ? $request->reason_id : null,
            ]);

            ReceptionTransfer::create([
                'from_reception_id' => $origin->id,
                'to_reception_id' => $destination->id,
                'reason' => $request->reason,
                'created_by' => auth()->id(),
            ]);

            ReceptionEvent::create([
                'reception_id' => $origin->id,
                'event_type' => 'transfer',
                'description' => 'Trasladado a ' . (ReceptionType::find($destinationTypeId)?->name ?? 'otro tipo de recepción'),
                'created_by' => auth()->id(),
            ]);

            $this->markOriginAsTransferred($origin);
            $this->seedInitialStatusHistory($destination);

            // Traslado Hospitalización -> Cremación: se registra automáticamente
            // el alta por fallecimiento de la recepción de origen
            if ((int) $origin->reception_type_id === 2 && $destinationTypeId === 5) {
                app(HospitalizationController::class)->registerDeathDischarge($origin->id, $voucherService);
            }

            // Traslado con origen Consulta que nunca se finalizó la
            // consulta vía EndAppointment()/AppointmentController::store()):
            // se guarda lo que el médico ya haya escrito en appointment.form
            if ((int) $origin->reception_type_id === 1 && !Appointment::where('reception_id', $origin->id)->exists()) {
                Appointment::create([
                    'reception_id' => $origin->id,
                    'anamnesis' => $request->anamnesis,
                    'exam_details' => $request->exam_details,
                    'diagnosis' => (string) $request->diagnosis,
                    'observations' => $request->observations,
                    'service_id' => optional($origin->loadMissing('reason')->reason)->articulo_id,
                ]);
            }

            return $destination;
        });

        $wantsJson = $request->ajax() || $request->wantsJson();

        // Hospitalización -> Cremación (alta por fallecimiento, ya
        // registrada automáticamente arriba): el médico no llena el
        // formulario de Cremación, regresa a su worklist — alguien más
        // completa los datos del servicio desde el botón "Crear" en la
        // tabla de Cremaciones (ver receptions/index.js).
        $redirect = match (true) {
            $destinationTypeId === 1 && (int) $origin->reception_type_id === 2 => route('assignment.index'),
            $destinationTypeId === 2 => route('hospital.list', ['id' => $destination->id]),
            $destinationTypeId === 3 => route('receptions.grooming', $destination->id),
            $destinationTypeId === 4 => route('hotel.create', ['id' => $destination->id]),
            $destinationTypeId === 5 && (int) $origin->reception_type_id === 2 => route('assignment.hospital'),
            $destinationTypeId === 5 => route('new.cremation', ['id' => $destination->id]),
            default => route('receptions.index'),
        };

        if ($wantsJson) {
            return response()->json([
                'success' => true,
                'reception_type_id' => $destinationTypeId,
                'redirect' => $redirect,
            ]);
        }

        return redirect($redirect)->with('success', 'Recepción trasladada exitosamente.');
    }

    /**
     * Marca la recepción de origen como "Trasladado"
     */
    private function markOriginAsTransferred(Reception $origin): void
    {
        match ((int) $origin->reception_type_id) {
            1 => ReceptionStatusHistory::create([
                'reception_id' => $origin->id,
                'attention_status_id' => AttentionStatus::where('name', 'Trasladado')->value('id'),
            ]),
            2 => HospitalizationStatusHistory::create([
                'reception_id' => $origin->id,
                'hospitalization_status_id' => HospitalizationStatus::where('name', 'Trasladado')->value('id'),
                'changed_by' => auth()->id(),
                'changed_at' => now(),
            ]),
            3 => GroomingStatusHistory::create([
                'reception_id' => $origin->id,
                'grooming_status_id' => GroomingStatus::where('name', 'Trasladado')->value('id'),
            ]),
            4 => HotelStatusHistory::create([
                'reception_id' => $origin->id,
                'hotel_status_id' => HotelStatus::where('name', 'Trasladado')->value('id'),
                'changed_by' => auth()->id(),
                'changed_at' => now(),
            ]),
            default => null,
        };
    }

    /**
     * Siembra el primer historial de estatus de la recepción destino, igual
     * que ReceptionController@store hace para una recepción creada de cero.
     */
    private function seedInitialStatusHistory(Reception $destination): void
    {
        match ((int) $destination->reception_type_id) {
            1 => ReceptionStatusHistory::create([
                'reception_id' => $destination->id,
                'attention_status_id' => AttentionStatus::where('name', 'En espera')->value('id'),
            ]),
            2 => HospitalizationStatusHistory::create([
                'reception_id' => $destination->id,
                'hospitalization_status_id' => HospitalizationStatus::where('name', 'Hospitalizado')->value('id'),
                'changed_by' => auth()->id(),
                'changed_at' => now(),
            ]),
            3 => GroomingStatusHistory::create([
                'reception_id' => $destination->id,
                'grooming_status_id' => 1, // Atendiendo
            ]),
            4 => HotelStatusHistory::create([
                'reception_id' => $destination->id,
                'hotel_status_id' => HotelStatus::where('name', 'En estancia')->value('id'),
                'changed_by' => auth()->id(),
                'changed_at' => now(),
            ]),
            default => null,
        };
    }

    /**
   
     * tracking (ver openTransfersTrackingModal()
     * Acepta reception_id (resuelve su episodio, un solo timeline — botón en
     * receptions.index) o pet_id (todos los episodios de esa mascota con al
     * menos un traslado, varios timelines agrupados — botón en pet_history).
     */
    public function tracking(Request $request)
    {
        $this->authorize('viewAny', Reception::class);

        if ($request->filled('reception_id')) {
            $episodeIds = collect([Reception::findOrFail($request->reception_id)->episode_id])->filter();
        } elseif ($request->filled('pet_id')) {
            $episodeIds = Episode::where('pet_id', $request->pet_id)->pluck('id');
        } else {
            abort(422, 'Se requiere reception_id o pet_id.');
        }

        // Solo episodios que realmente tengan al menos un traslado.
        $episodeIds = Reception::whereIn('episode_id', $episodeIds)
            ->whereHas('transfersFrom')
            ->pluck('episode_id')
            ->unique()
            ->values();

        $episodes = $episodeIds->map(function ($episodeId) {
            $chain = Reception::where('episode_id', $episodeId)
                ->orderBy('entry_date')
                ->with(['receptionType', 'vet', 'transfersFrom.createdBy'])
                ->get()
                ->map(fn ($r) => [
                    'reception_id' => $r->id,
                    'reception_type_id' => $r->reception_type_id,
                    'reception_type' => $r->receptionType->name ?? '—',
                    'entry_date' => $r->entry_date,
                    'vet' => $r->vet->name ?? null,
                    'transfer_out' => optional($r->transfersFrom->first(), fn ($t) => [
                        'reason' => $t->reason,
                        'created_by' => $t->createdBy->name ?? null,
                        'created_at' => $t->created_at?->format('Y-m-d H:i:s'),
                    ]),
                ]);

            return ['episode_id' => $episodeId, 'chain' => $chain->values()];
        })->values();

        return response()->json(['episodes' => $episodes]);
    }
}

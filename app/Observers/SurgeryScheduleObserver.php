<?php

namespace App\Observers;

use App\Models\Log;
use App\Models\SurgerySchedule;
use Illuminate\Support\Facades\Auth;
class SurgeryScheduleObserver
{
    /**
     * Handle the SurgerySchedule "created" event.
     */
    public function created(SurgerySchedule $surgerySchedule): void
    {
        Log::create([
            "action" => "ASIGNACIÓN DE CIRUGÍA",
            'description' => 'Se agendó una cirugía ',
            'user_id' => (Auth::user()->id) ?? null
        ]);
    }

    /**
     * Handle the SurgerySchedule "updated" event.
     */
    public function updated(SurgerySchedule $surgerySchedule): void
    {
         Log::create([
            "action" => " ACTUALIZACIÓN DE ASIGNACIÓN DE CIRUGÍA",
            'description' => 'Se editó asignación de cirugía ',
            'user_id' => (Auth::user()->id) ?? null
        ]);
    }

    /**
     * Handle the SurgerySchedule "deleted" event.
     */
    public function deleted(SurgerySchedule $surgerySchedule): void
    {
        Log::create([
            "action" => " SE ELIMINÓ ASIGNACIÓN DE CIRUGÍA",
            'description' => 'Se eliminó una asignación de cirugía ',
            'user_id' => (Auth::user()->id) ?? null
        ]);
    }

    /**
     * Handle the SurgerySchedule "restored" event.
     */
    public function restored(SurgerySchedule $surgerySchedule): void
    {
        //
    }

    /**
     * Handle the SurgerySchedule "force deleted" event.
     */
    public function forceDeleted(SurgerySchedule $surgerySchedule): void
    {
        //
    }
}

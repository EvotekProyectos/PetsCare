<?php

namespace App\Observers;

use App\Models\Log;
use App\Models\Appointment;
use Illuminate\Support\Facades\Auth;

class AppointmentObserver
{
    /**
     * Handle the Appointment "created" event.
     */
    public function created(Appointment $appointment): void
    {
        $pet=$appointment->reception->pet;

        Log::create([
            "action"=>'REGISTRO DE CONSULTA',
            "description"=>'A la mascota ' .$pet->name. ' se le atendio en una consulta',
            "user_id"=>(Auth::user()->id)
        ]);
    }

    /**
     * Handle the Appointment "updated" event.
     */
    public function updated(Appointment $appointment): void
    {
        $pet=$appointment->reception->pet;
        Log::create([
            "action"=>'EDICIÓN DE CONSULTA',
            "description"=>'La consulta de la mascota ' .$pet->name. ' fue modificada',
            "user_id"=>(Auth::user()->id)
        ]);
    }

    /**
     * Handle the Appointment "deleted" event.
     */
    public function deleted(Appointment $appointment): void
    {
        //
    }

    /**
     * Handle the Appointment "restored" event.
     */
    public function restored(Appointment $appointment): void
    {
        //
    }

    /**
     * Handle the Appointment "force deleted" event.
     */
    public function forceDeleted(Appointment $appointment): void
    {
        //
    }
}

<?php

namespace App\Observers;
use App\Models\Log;
use App\Models\ControlDate;
use Illuminate\Support\Facades\Auth;


class ControlDateObserver
{
    /**
     * Handle the ControlDate "created" event.
     */
    public function created(ControlDate $controlDate): void
    {
        Log::create([
            'action' => 'NUEVA CITA CREADA',
            'description' => 'Se creó una nueva cita: ',
            'user_id' => (Auth::user()->id) ?? null
        ]);
    }

    /**
     * Handle the ControlDate "updated" event.
     */
    public function updated(ControlDate $controlDate): void
    {
        Log::create([
            'action' => 'EDICIÓN DE CITA',
            'description' => 'Se editó una cita: ',
            'user_id' => (Auth::user()->id) 
        ]);
    }

    /**
     * Handle the ControlDate "deleted" event.
     */
    public function deleted(ControlDate $controlDate): void
    {
        Log::create([
            'action' => 'ELIMINACIÓN DE CITA',
            'description' => 'Se eliminó una cita',
            'user_id' => (Auth::user()->id) 
        ]);
    }

    /**
     * Handle the ControlDate "restored" event.
     */
    public function restored(ControlDate $controlDate): void
    {
        //
    }

    /**
     * Handle the ControlDate "force deleted" event.
     */
    public function forceDeleted(ControlDate $controlDate): void
    {
        //
    }
}

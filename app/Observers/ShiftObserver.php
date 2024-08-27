<?php

namespace App\Observers;

use App\Models\Log;
use App\Models\Shift;
use Illuminate\Support\Facades\Auth;

class ShiftObserver
{
    /**
     * Handle the Shift "created" event.
     */
    public function created(Shift $shift): void
    {
        Log::create([
            'action' => 'CREACION DE NUEVO TURNO',
            'description' => 'Se creo un nuevo turno: ' . $shift->name,
            'user_id' => (Auth::user()->id) ?? null
        ]);
    }

    /**
     * Handle the Shift "updated" event.
     */
    public function updated(Shift $shift): void
    {
        Log::create([
            'action' => 'EDICIÓN DE TURNO',
            'description' => 'Se edito el turno: ' . $shift->name,
            'user_id' => (Auth::user()->id) 
        ]);
    }

    /**
     * Handle the Shift "deleted" event.
     */
    public function deleted(Shift $shift): void
    {
        Log::create([
            'action' => 'ELIMINACIÓN DE TURNO',
            'description' => 'Se elimino el turno: ' . $shift->name,
            'user_id' => (Auth::user()->id) 
        ]);
    }

    /**
     * Handle the Shift "restored" event.
     */
    public function restored(Shift $shift): void
    {
        //
    }

    /**
     * Handle the Shift "force deleted" event.
     */
    public function forceDeleted(Shift $shift): void
    {
        //
    }
}

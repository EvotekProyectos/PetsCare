<?php

namespace App\Observers;

use App\Models\Log;
use App\Models\PetsStatus;
use Illuminate\Support\Facades\Auth;

class PetsStatusObserver
{
    /**
     * Handle the PetsStatus "created" event.
     */
    public function created(PetsStatus $petsStatus): void
    {
        Log::create([
            'action' => 'CREACIÓN DE NUEVO ESTADO PARA MASCOTAS',
            'description' => 'Se creo un nuevo estado para mascotas: ' .$petsStatus->name,
            'user_id' => (Auth::user()->id) ?? null
        ]);

    }

    /**
     * Handle the PetsStatus "updated" event.
     */
    public function updated(PetsStatus $petsStatus): void
    {
        Log::create([
            'action' => 'EDICIÓN DE ESTADO PARA MASCOTAS',
            'description' => 'Se edito el estado para mascotas: ' .$petsStatus->name,
            'user_id' => (Auth::user()->id) 
        ]);
    }

    /**
     * Handle the PetsStatus "deleted" event.
     */
    public function deleted(PetsStatus $petsStatus): void
    {
        Log::create([
            'action' => 'ELIMINACIÓN DE ESTADO PARA MASCOTAS',
            'description' => 'Se eliminoo el estado para mascotas: ' .$petsStatus->name,
            'user_id' => (Auth::user()->id) 
        ]);
    }

    /**
     * Handle the PetsStatus "restored" event.
     */
    public function restored(PetsStatus $petsStatus): void
    {
        //
    }

    /**
     * Handle the PetsStatus "force deleted" event.
     */
    public function forceDeleted(PetsStatus $petsStatus): void
    {
        //
    }
}

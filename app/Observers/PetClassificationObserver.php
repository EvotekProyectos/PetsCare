<?php

namespace App\Observers;

use App\Models\Log;
use App\Models\PetClassification;
use Illuminate\Support\Facades\Auth;

class PetClassificationObserver
{
    /**
     * Handle the PetClassification "created" event.
     */
    public function created(PetClassification $petClassification): void
    {
        Log::create([
            'action' => 'CREACIÓN DE CLASIFICACIÓN PARA MASCOTAS',
            'description' => 'Se creo una nueva clasificación para mastocas: ' . $petClassification->name,
            'user_id' => (Auth::user()->id) ?? null
        ]);
    }

    /**
     * Handle the PetClassification "updated" event.
     */
    public function updated(PetClassification $petClassification): void
    {
        Log::create([
            'action' => 'EDICIÓN DE CLASIFICACIÓN PARA MASCOTAS',
            'description' => 'Se edito la clasificación para mastocas: ' . $petClassification->name,
            'user_id' => (Auth::user()->id) 
        ]);
    }

    /**
     * Handle the PetClassification "deleted" event.
     */
    public function deleted(PetClassification $petClassification): void
    {
        Log::create([
            'action' => 'ELIMINACIÓN DE CLASIFICACIÓN PARA MASCOTAS',
            'description' => 'Se elimino la clasificación para mastocas: ' . $petClassification->name,
            'user_id' => (Auth::user()->id) 
        ]);
    }

    /**
     * Handle the PetClassification "restored" event.
     */
    public function restored(PetClassification $petClassification): void
    {
        //
    }

    /**
     * Handle the PetClassification "force deleted" event.
     */
    public function forceDeleted(PetClassification $petClassification): void
    {
        //
    }
}

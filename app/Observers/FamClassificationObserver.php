<?php

namespace App\Observers;

use App\Models\Log;
use App\Models\FamClassification;
use Illuminate\Support\Facades\Auth;

class FamClassificationObserver
{
    /**
     * Handle the FamClassification "created" event.
     */
    public function created(FamClassification $famClassification): void
    {
        Log::create([
            'action' => 'CREACIÓN DE NUEVA CLASIFICACIÓN PARA FAMILIAS',
            'description' => 'Se creo una nueva clasificación para familias: ' . $famClassification->name,
            'user_id' => (Auth::user()->id) ?? null
        ]);
    }

    /**
     * Handle the FamClassification "updated" event.
     */
    public function updated(FamClassification $famClassification): void
    {
        Log::create([
            'action' => 'EDICIÓN DE CLASIFICACIÓN PARA FAMILIAS',
            'description' => 'Se edito la clasificación para familias: ' . $famClassification->name,
            'user_id' => (Auth::user()->id) 
        ]);
    }

    /**
     * Handle the FamClassification "deleted" event.
     */
    public function deleted(FamClassification $famClassification): void
    {
        Log::create([
            'action' => 'ELIMINACIÓN DE CLASIFICACIÓN PARA FAMILIAS',
            'description' => 'Se elimino la clasificación para familias: ' . $famClassification->name,
            'user_id' => (Auth::user()->id) 
        ]);
    }

    /**
     * Handle the FamClassification "restored" event.
     */
    public function restored(FamClassification $famClassification): void
    {
        //
    }

    /**
     * Handle the FamClassification "force deleted" event.
     */
    public function forceDeleted(FamClassification $famClassification): void
    {
        //
    }
}

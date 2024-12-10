<?php

namespace App\Observers;

use App\Models\SurgeryPack;
use App\Models\Log;
use Illuminate\Support\Facades\Auth;

class SurgeryPackObserver
{
    /**
     * Handle the SurgeryPack "created" event.
     */
    public function created(SurgeryPack $surgeryPack): void
    {
        Log::create([
            "action" => "CREACION DE NUEVO PAQUETE PARA CIRUGIA",
            'description' => 'Se creo un nuevo paquete de cirugia: ' . $surgeryPack->name,
            'user_id' => (Auth::user()->id) ?? null
        ]);
    }

    /**
     * Handle the SurgeryPack "updated" event.
     */
    public function updated(SurgeryPack $surgeryPack): void
    {
        Log::create([
            "action" => "EDICIÓN DE PAQUETE PARA CIRUGIA",
            'description' => 'Se edito el paquete de cirugia: ' . $surgeryPack->name,
            'user_id' => (Auth::user()->id) 
        ]);
    }

    /**
     * Handle the SurgeryPack "deleted" event.
     */
    public function deleted(SurgeryPack $surgeryPack): void
    {
        Log::create([
            "action" => "ELIMINACIÓN DE PAQUETE PARA CIRUGIA",
            'description' => 'Se elimino el paquete de cirugia: ' . $surgeryPack->name,
            'user_id' => (Auth::user()->id) 
        ]);
    }

    /**
     * Handle the SurgeryPack "restored" event.
     */
    public function restored(SurgeryPack $surgeryPack): void
    {
        //
    }

    /**
     * Handle the SurgeryPack "force deleted" event.
     */
    public function forceDeleted(SurgeryPack $surgeryPack): void
    {
        //
    }
}

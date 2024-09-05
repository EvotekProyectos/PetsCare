<?php

namespace App\Observers;

use App\Models\Log;
use App\Models\Pet;
use Illuminate\Support\Facades\Auth;

class PetObserver
{
    /**
     * Handle the Pet "created" event.
     */
    public function created(Pet $pet): void
    {
        Log::create([
            'action' => 'CREACIÓN DE NUEVA MASCOTA',
            'description' => 'Se creo una nueva mascota: ' .$pet->name,
            'user_id' => (Auth::user()->id) ?? null
        ]);
    }

    /**
     * Handle the Pet "updated" event.
     */
    public function updated(Pet $pet): void
    {
        Log::create([
            'action' => 'EDICIÓN DE MASCOTA',
            'description' => 'Se edito la mascota: ' .$pet->name,
            'user_id' => (Auth::user()->id)
        ]);
    }

    /**
     * Handle the Pet "deleted" event.
     */
    public function deleted(Pet $pet): void
    {
        Log::create([
            'action' => 'ELIMINACIÓN DE MASCOTA',
            'description' => 'Se elimino la mascota: ' .$pet->name,
            'user_id' => (Auth::user()->id)
        ]);
    }

    /**
     * Handle the Pet "restored" event.
     */
    public function restored(Pet $pet): void
    {
        //
    }

    /**
     * Handle the Pet "force deleted" event.
     */
    public function forceDeleted(Pet $pet): void
    {
        //
    }
}

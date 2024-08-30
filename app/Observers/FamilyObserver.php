<?php

namespace App\Observers;

use App\Models\Log;
use App\Models\Family;
use Illuminate\Support\Facades\Auth;

class FamilyObserver
{
    /**
     * Handle the Family "created" event.
     */
    public function created(Family $family): void
    {
        Log::create([
            'action' => 'CREACIÓN DE NUEVA FAMILIA',
            'description' => 'Se creo una nueva familia: ' .$family->name,
            'user_id' => (Auth::user()->id) ?? null
        ]);
    }

    /**
     * Handle the Family "updated" event.
     */
    public function updated(Family $family): void
    {
        Log::create([
            'action' => 'EDICIÓN DE FAMILIA',
            'description' => 'Se edito la familia: ' .$family->name,
            'user_id' => (Auth::user()->id)
        ]);
    }

    /**
     * Handle the Family "deleted" event.
     */
    public function deleted(Family $family): void
    {
        Log::create([
            'action' => 'ELIMINACIÓN DE FAMILIA',
            'description' => 'Se elimino la familia: ' .$family->name,
            'user_id' => (Auth::user()->id)
        ]);
    }

    /**
     * Handle the Family "restored" event.
     */
    public function restored(Family $family): void
    {
        //
    }

    /**
     * Handle the Family "force deleted" event.
     */
    public function forceDeleted(Family $family): void
    {
        //
    }
}

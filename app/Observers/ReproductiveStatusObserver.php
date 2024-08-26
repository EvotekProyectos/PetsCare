<?php

namespace App\Observers;

use App\Models\Log;
use App\Models\ReproductiveStatus;
use Illuminate\Support\Facades\Auth;

class ReproductiveStatusObserver
{
    /**
     * Handle the ReproductiveStatus "created" event.
     */
    public function created(ReproductiveStatus $reproductiveStatus): void
    {
        Log::create([
            'action' => 'CREACION DE ESTADO REPRODUCTIVO',
            'description' => 'Se creo un nuevo estado reproductivo: ' . $reproductiveStatus->name,
            'user_id' => (Auth::user()->id) ?? null
        ]);
    }

    /**
     * Handle the ReproductiveStatus "updated" event.
     */
    public function updated(ReproductiveStatus $reproductiveStatus): void
    {
        Log::create([
            'action' => 'EDICIÓN DE ESTADO REPRODUCTIVO',
            'description' => 'Se edito el estado reproductivo: ' . $reproductiveStatus->name,
            'user_id' => (Auth::user()->id) 
        ]);
    }

    /**
     * Handle the ReproductiveStatus "deleted" event.
     */
    public function deleted(ReproductiveStatus $reproductiveStatus): void
    {
        Log::create([
            'action' => 'ELIMINACIÓN DE ESTADO REPRODUCTIVO',
            'description' => 'Se elimino el estado reproductivo: ' . $reproductiveStatus->name,
            'user_id' => (Auth::user()->id) 
        ]);
    }

    /**
     * Handle the ReproductiveStatus "restored" event.
     */
    public function restored(ReproductiveStatus $reproductiveStatus): void
    {
        //
    }

    /**
     * Handle the ReproductiveStatus "force deleted" event.
     */
    public function forceDeleted(ReproductiveStatus $reproductiveStatus): void
    {
        //
    }
}

<?php

namespace App\Observers;

use App\Models\GroomingStatus;
use App\Models\Log;
use Illuminate\Support\Facades\Auth;

class GroomingStatusObserver
{
    /**
     * Handle the GroomingStatus "created" event.
     */
    public function created(GroomingStatus $groomingStatus): void
    {
        $log = Log::create([
            'action' => 'NUEVO ESTADO DE GROOMING',
            'description' => 'Se creó un nuevo estado de grooming: ' . $groomingStatus->name,
            'user_id' => (Auth::user()->id) ?? null
        ]);
    }

    /**
     * Handle the GroomingStatus "updated" event.
     */
    public function updated(GroomingStatus $groomingStatus): void
    {
        $log = Log::create([
            'action' => 'EDICIÓN DE ESTADO DE GROOMING',
            'description' => 'Se editó al estado de grooming: ' . $groomingStatus->name,
            'user_id' => Auth::user()->id
        ]);
    }

    /**
     * Handle the GroomingStatus "deleted" event.
     */
    public function deleted(GroomingStatus $groomingStatus): void
    {
        $log = Log::create([
            'action' => 'ELIMINACIÓN DE ESTADO DE GROOMING',
            'description' => 'Se eliminó al estado de grooming: ' . $groomingStatus->name,
            'user_id' => Auth::user()->id
        ]);
    }

    /**
     * Handle the GroomingStatus "restored" event.
     */
    public function restored(GroomingStatus $groomingStatus): void
    {
        //
    }

    /**
     * Handle the GroomingStatus "force deleted" event.
     */
    public function forceDeleted(GroomingStatus $groomingStatus): void
    {
        //
    }
}

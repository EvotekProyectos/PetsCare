<?php

namespace App\Observers;

use App\Models\Log;
use App\Models\Reason;
use Illuminate\Support\Facades\Auth;

class ReasonObserver
{
    /**
     * Handle the Reason "created" event.
     */
    public function created(Reason $reason): void
    {
        $log = Log::create([
            'action' => 'NUEVO MOTIVO',
            'description' => 'Se creó un nuevo motivo: ' . $reason->name,
            'user_id' => (Auth::user()->id) ?? null
        ]);
    }

    /**
     * Handle the Reason "updated" event.
     */
    public function updated(Reason $reason): void
    {
        $log = Log::create([
            'action' => 'EDICIÓN DE MOTIVO',
            'description' => 'Se editó el motivo ' . $reason->name,
            'user_id' => Auth::user()->id
        ]);
    }

    /**
     * Handle the Reason "deleted" event.
     */
    public function deleted(Reason $reason): void
    {
        $log = Log::create([
            'action' => 'ELIMINACIÓN DE MOTIVO',
            'description' => 'Se eliminó el motivo ' . $reason->name,
            'user_id' => Auth::user()->id
        ]);
    }

    /**
     * Handle the Reason "restored" event.
     */
    public function restored(Reason $reason): void
    {
        //
    }

    /**
     * Handle the Reason "force deleted" event.
     */
    public function forceDeleted(Reason $reason): void
    {
        //
    }
}

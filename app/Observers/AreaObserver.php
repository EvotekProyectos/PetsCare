<?php

namespace App\Observers;

use App\Models\Log;
use App\Models\Area;
use Illuminate\Support\Facades\Auth;

class AreaObserver
{
    /**
     * Handle the Area "created" event.
     */
    public function created(Area $area): void
    {
        $log = Log::create([
            'action' => 'NUEVA ÁREA',
            'description' => 'Se creó una nueva área: ' . $area->name,
            'user_id' => (Auth::user()->id) ?? null
        ]);
    }

    /**
     * Handle the Area "updated" event.
     */
    public function updated(Area $area): void
    {
        $log = Log::create([
            'action' => 'EDICIÓN DE ÁREA',
            'description' => 'Se editó el área ' . $area->name,
            'user_id' => Auth::user()->id
        ]);
    }

    /**
     * Handle the Area "deleted" event.
     */
    public function deleted(Area $area): void
    {
        $log = Log::create([
            'action' => 'ELIMINACIÓN DE ÁREA',
            'description' => 'Se eliminó el área ' . $area->name,
            'user_id' => Auth::user()->id
        ]);
    }

    /**
     * Handle the Area "restored" event.
     */
    public function restored(Area $area): void
    {
        //
    }

    /**
     * Handle the Area "force deleted" event.
     */
    public function forceDeleted(Area $area): void
    {
        //
    }
}

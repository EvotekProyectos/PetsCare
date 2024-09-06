<?php

namespace App\Observers;

use App\Models\Log;
use App\Models\CoverArea;
use Illuminate\Support\Facades\Auth;

class CoverAreaObserver
{
    /**
     * Handle the CoverArea "created" event.
     */
    public function created(CoverArea $coverArea): void
    {
        Log::create([
            'action' => 'NUEVA ÁREA A CUBRIR',
            'description' => 'Se creó una nueva área a cubrir: ' .$coverArea->name,
            'user_id' => (Auth::user()->id) ?? null
        ]);
    }

    /**
     * Handle the CoverArea "updated" event.
     */
    public function updated(CoverArea $coverArea): void
    {
        Log::create([
            'action' => 'EDICIÓN DE ÁREA A CUBRIR',
            'description' => 'Se editó el área a cubrir: ' .$coverArea->name,
            'user_id' => (Auth::user()->id) 
        ]);
    }

    /**
     * Handle the CoverArea "deleted" event.
     */
    public function deleted(CoverArea $coverArea): void
    {
        Log::create([
            'action' => 'ELIMINACIÓN DE ÁREA A CUBRIR',
            'description' => 'Se eliminó el área a cubrir: ' .$coverArea->name,
            'user_id' => (Auth::user()->id) 
        ]);
    }

    /**
     * Handle the CoverArea "restored" event.
     */
    public function restored(CoverArea $coverArea): void
    {
        //
    }

    /**
     * Handle the CoverArea "force deleted" event.
     */
    public function forceDeleted(CoverArea $coverArea): void
    {
        //
    }
}

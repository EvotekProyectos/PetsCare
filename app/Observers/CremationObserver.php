<?php

namespace App\Observers;
use App\Models\Log;
use App\Models\Cremation;
use Illuminate\Support\Facades\Auth;

class CremationObserver
{
    /**
     * Handle the Cremation "created" event.
     */
    public function created(Cremation $cremation): void
    {
        $pets=$cremation->pet;

        Log::create([
            "action" => 'CREACIÓN DE UNA NUEVA CREMACIÓN',
            'description' => 'Se creó una nueva cremación para ' .  $pets->name,
            'user_id' => (Auth::user()->id) ?? null
        ]);
    }

    /**
     * Handle the Cremation "updated" event.
     */
    public function updated(Cremation $cremation): void
    {
        Log::create([
            "action" => 'EDICIÓN DE CREMACIÓN',
            'description' => 'Se editó la cremación: ' .  $cremation->id,
            'user_id' => (Auth::user()->id) 
        ]);
    }

    /**
     * Handle the Cremation "deleted" event.
     */
    public function deleted(Cremation $cremation): void
    {
        Log::create([
            "action" => 'ELIMINACIÓN DE CREMACIÓN',
            'description' => 'Se eliminó la cremación: ' . $cremation->id,
            'user_id' => (Auth::user()->id) 
        ]);
    }

    /**
     * Handle the Cremation "restored" event.
     */
    public function restored(Cremation $cremation): void
    {
        //
    }

    /**
     * Handle the Cremation "force deleted" event.
     */
    public function forceDeleted(Cremation $cremation): void
    {
        //
    }
}

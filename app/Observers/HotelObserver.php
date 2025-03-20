<?php

namespace App\Observers;

use App\Models\Log;
use App\Models\Hotel;
use Illuminate\Support\Facades\Auth;

class HotelObserver
{
    /**
     * Handle the Hotel "created" event.
     */
    public function created(Hotel $hotel): void
    {
        $pets=$hotel->pet;

        Log::create([
            "action" => 'CREACIÓN DE UNA NUEVA PENSIÓN',
            'description' => 'Se creó una pensión para ' ,
            'user_id' => (Auth::user()->id) ?? null
        ]);
    }

    /**
     * Handle the Hotel "updated" event.
     */
    public function updated(Hotel $hotel): void
    {
        $pets=$hotel->pet;

        Log::create([
            "action" => 'EDICIÓN DE PENSIÓN',
            'description' => 'Se editó la pensión para '   ,
            'user_id' => (Auth::user()->id) 
        ]);
    }

    /**
     * Handle the Hotel "deleted" event.
     */
    public function deleted(Hotel $hotel): void
    {
        $pets=$hotel->pet; 

        Log::create([
            "action" => 'ELIMINACIÓN DE PENSIÓN',
            'description' => 'Se eliminó la pensión para ' ,
            'user_id' => (Auth::user()->id) 
        ]);
    }

    /**
     * Handle the Hotel "restored" event.
     */
    public function restored(Hotel $hotel): void
    {
        //
    }

    /**
     * Handle the Hotel "force deleted" event.
     */
    public function forceDeleted(Hotel $hotel): void
    {
        //
    }
}

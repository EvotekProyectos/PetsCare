<?php

namespace App\Observers;

use App\Models\Format;
use App\Models\Log;
use Illuminate\Support\Facades\Auth;

class FormatsObserver
{
    /**
     * Handle the Format "created" event.
     */
    public function created(Format $format): void
    {
        Log::create([
            "action" => 'CREACIÓN DE FORMATO',
            'description' => 'Se creo un nuevo formato: ' . $format->name,
            'user_id' => (Auth::user()->id) ?? null
        ]);
    }

    /**
     * Handle the Format "updated" event.
     */
    public function updated(Format $format): void
    {
        Log::create([
            "action" => 'EDICIÓN DE FORMATO',
            'description' => 'Se editó el formato: ' . $format->name,
            'user_id' => (Auth::user()->id) 
        ]);
    }

    /**
     * Handle the Format "deleted" event.
     */
    public function deleted(Format $format): void
    {
        Log::create([
            "action" => 'ELIMINACIÓN DE FORMATO',
            'description' => 'Se elimino el formato: ' . $format->name,
            'user_id' => (Auth::user()->id) 
        ]);
    }

    /**
     * Handle the Format "restored" event.
     */
    public function restored(Format $format): void
    {
        //
    }

    /**
     * Handle the Format "force deleted" event.
     */
    public function forceDeleted(Format $format): void
    {
        //
    }
}

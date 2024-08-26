<?php

namespace App\Observers;

use App\Models\Log;
use App\Models\Genre;
use Illuminate\Support\Facades\Auth;

class GenreObserver
{
    /**
     * Handle the Genre "created" event.
     */
    public function created(Genre $genre): void
    {
        Log::create([
            "action" => 'CREACIÓN DE GÉNERO',
            'description' => 'Se creo un nuevo género para mascotas: ' . $genre->name,
            'user_id' => (Auth::user()->id) ?? null
        ]);
    }

    /**
     * Handle the Genre "updated" event.
     */
    public function updated(Genre $genre): void
    {
        Log::create([
            "action" => 'EDICIÓN DE GÉNERO',
            'description' => 'Se edito el género para mascotas: ' . $genre->name,
            'user_id' => (Auth::user()->id) 
        ]);
    }

    /**
     * Handle the Genre "deleted" event.
     */
    public function deleted(Genre $genre): void
    {
        Log::create([
            "action" => 'ELIMINACIÓN DE GÉNERO',
            'description' => 'Se elimino el género para mascotas: ' . $genre->name,
            'user_id' => (Auth::user()->id) 
        ]);
    }

    /**
     * Handle the Genre "restored" event.
     */
    public function restored(Genre $genre): void
    {
        //
    }

    /**
     * Handle the Genre "force deleted" event.
     */
    public function forceDeleted(Genre $genre): void
    {
        //
    }
}

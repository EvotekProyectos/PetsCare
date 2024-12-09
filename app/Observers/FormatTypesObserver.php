<?php

namespace App\Observers;

use App\Models\FormatType;
use App\Models\Log;
use Illuminate\Support\Facades\Auth;

class FormatTypesObserver
{
    /**
     * Handle the FormatType "created" event.
     */
    public function created(FormatType $formatType): void
    {
        Log::create([
            "action" => 'CREACIÓN DE TIPO DE FORMATO',
            'description' => 'Se creo un nuevo tipo de formato: ' . $formatType->name,
            'user_id' => (Auth::user()->id) ?? null
        ]);
    }

    /**
     * Handle the FormatType "updated" event.
     */
    public function updated(FormatType $formatType): void
    {
        Log::create([
            "action" => 'EDICIÓN DE TIPO DE FORMATO',
            'description' => 'Se edito el tipo de formato: ' . $formatType->name,
            'user_id' => (Auth::user()->id) 
        ]);
    }

    /**
     * Handle the FormatType "deleted" event.
     */
    public function deleted(FormatType $formatType): void
    {
        Log::create([
            "action" => 'ELIMINACIÓN DE TIPO DE FORMATO',
            'description' => 'Se elimino el tipo de formato: ' . $formatType->name,
            'user_id' => (Auth::user()->id) 
        ]);
    }

    /**
     * Handle the FormatType "restored" event.
     */
    public function restored(FormatType $formatType): void
    {
        //
    }

    /**
     * Handle the FormatType "force deleted" event.
     */
    public function forceDeleted(FormatType $formatType): void
    {
        //
    }
}

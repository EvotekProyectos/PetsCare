<?php

namespace App\Observers;

use App\Models\Log;
use App\Models\TagType;
use Illuminate\Support\Facades\Auth;
class TagTypeObserver
{
    /**
     * Handle the TagType "created" event.
     */
    public function created(TagType $tagType): void
    {
        Log::create([
            "action" => 'CREACIÓN DE UN NUEVO TIPO DE PLACA',
            'description' => 'Se creó un nuevo tipo de placa' . $tagType->name,
            'user_id' => (Auth::user()->id) ?? null
        ]);
    }

    /**
     * Handle the TagType "updated" event.
     */
    public function updated(TagType $tagType): void
    {
        Log::create([
            "action" => 'EDICIÓN DE TIPO DE PLACA',
            'description' => 'Se editó el tipo de placa: ' . $tagType->name,
            'user_id' => (Auth::user()->id) 
        ]);
    }

    /**
     * Handle the TagType "deleted" event.
     */
    public function deleted(TagType $tagType): void
    {
        Log::create([
            "action" => 'ELIMINACIÓN DE TIPO DE PLACA',
            'description' => 'Se eliminó el tipo de placa: ' . $tagType->name,
            'user_id' => (Auth::user()->id) 
        ]);
    }

    /**
     * Handle the TagType "restored" event.
     */
    public function restored(TagType $tagType): void
    {
        //
    }

    /**
     * Handle the TagType "force deleted" event.
     */
    public function forceDeleted(TagType $tagType): void
    {
        //
    }
}

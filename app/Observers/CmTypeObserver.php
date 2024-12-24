<?php

namespace App\Observers;

use App\Models\Log;
use App\Models\CmType;
use Illuminate\Support\Facades\Auth;

class CmTypeObserver
{
    /**
     * Handle the CmType "created" event.
     */
    public function created(CmType $cmType): void
    {
        Log::create([
            "action" => 'CREACIÓN DE C.M',
            'description' => 'Se creo un nuevo tipo de C.M ' . $cmType->name,
            'user_id' => (Auth::user()->id) ?? null
        ]);
    }

    /**
     * Handle the CmType "updated" event.
     */
    public function updated(CmType $cmType): void
    {
        Log::create([
            "action" => 'EDICIÓN DE C.M',
            'description' => 'Se editó el C.M: ' . $cmType->name,
            'user_id' => (Auth::user()->id) 
        ]);
    }

    /**
     * Handle the CmType "deleted" event.
     */
    public function deleted(CmType $cmType): void
    {
       
        Log::create([
            "action" => 'ELIMINACIÓN DE C.M',
            'description' => 'Se eliminó el C.M: ' . $cmType->name,
            'user_id' => (Auth::user()->id) 
        ]);
    }

    /**
     * Handle the CmType "restored" event.
     */
    public function restored(CmType $cmType): void
    {
        //
    }

    /**
     * Handle the CmType "force deleted" event.
     */
    public function forceDeleted(CmType $cmType): void
    {
        //
    }
}

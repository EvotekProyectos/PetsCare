<?php

namespace App\Observers;

use App\Models\Log;
use App\Models\ReceptionType;
use Illuminate\Support\Facades\Auth;

class ReceptionTypeObserver
{
    /**
     * Handle the ReceptionType "created" event.
     */
    public function created(ReceptionType $receptionType): void
    {
        $log = Log::create([
            'action' => 'NUEVO TIPO DE RECEPCIÓN',
            'description' => 'Se creó un nuevo tipo de recepción: ' . $receptionType->name,
            'user_id' => (Auth::user()->id) ?? null
        ]);
    }

    /**
     * Handle the ReceptionType "updated" event.
     */
    public function updated(ReceptionType $receptionType): void
    {
        $log = Log::create([
            'action' => 'EDICIÓN DE TIPO DE RECEPCIÓN',
            'description' => 'Se editó el tipo de recepción ' . $receptionType->name,
            'user_id' => Auth::user()->id
        ]);
    }

    /**
     * Handle the ReceptionType "deleted" event.
     */
    public function deleted(ReceptionType $receptionType): void
    {
        $log = Log::create([
            'action' => 'ELIMINACIÓN DE TIPO DE RECEPCIÓN',
            'description' => 'Se eliminó el tipo de recepción ' . $receptionType->name,
            'user_id' => Auth::user()->id
        ]);
    }

    /**
     * Handle the ReceptionType "restored" event.
     */
    public function restored(ReceptionType $receptionType): void
    {
        //
    }

    /**
     * Handle the ReceptionType "force deleted" event.
     */
    public function forceDeleted(ReceptionType $receptionType): void
    {
        //
    }
}

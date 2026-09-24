<?php

namespace App\Observers;

use App\Models\Log;
use App\Models\Reception;
use App\Services\ReceptionVersionService;
use Illuminate\Support\Facades\Auth;

class ReceptionObserver
{
    public function __construct(private ReceptionVersionService $versionService)
    {
    }

    /**
     * Handle the Reception "created" event.
     */
    public function created(Reception $reception): void
    {   $pets=$reception->pet;
        $type=$reception->receptionType;

        Log::create([
            "action"=>'CREACION DE RECEPCION',
            "description"=>'Se creo una nueva recepción para ' . $pets->name. ' de tipo: ' .$type->name,
            "user_id"=>(Auth::user()->id)??null
        ]);

        $this->versionService->touch();
    }

    /**
     * Handle the Reception "updated" event.
     */
    public function updated(Reception $reception): void
    {
        $pets=$reception->pet;
        $type=$reception->receptionType;

        Log::create([
            "action"=>'EDICIÓN DE RECEPCION',
            "description"=>'Se edito la recepción de ' . $pets->name. ' de tipo: ' .$type->name,
            "user_id"=>(Auth::user()->id)
        ]);

        $this->versionService->touch();
    }

    /**
     * Handle the Reception "deleted" event.
     */
    public function deleted(Reception $reception): void
    {
        $pets=$reception->pet;
        $type=$reception->receptionType;

        Log::create([
            "action"=>'ELIMINACIÓN DE RECEPCION',
            "description"=>'Se eliminó la recepción de ' . $pets->name. ' de tipo: ' .$type->name,
            "user_id"=>(Auth::user()->id)
        ]);

        $this->versionService->touch();
    }

    /**
     * Handle the Reception "restored" event.
     */
    public function restored(Reception $reception): void
    {
        //
    }

    /**
     * Handle the Reception "force deleted" event.
     */
    public function forceDeleted(Reception $reception): void
    {
        //
    }
}

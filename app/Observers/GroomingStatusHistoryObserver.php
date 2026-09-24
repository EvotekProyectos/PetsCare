<?php

namespace App\Observers;

use App\Models\GroomingStatusHistory;
use App\Models\Log;
use App\Services\ReceptionVersionService;
use Illuminate\Support\Facades\Auth;

class GroomingStatusHistoryObserver
{
    public function __construct(private ReceptionVersionService $versionService)
    {
    }

    /**
     * Handle the GroomingStatusHistory "created" event.
     */
    public function created(GroomingStatusHistory $groomingStatusHistory): void
    {
        $attention=$groomingStatusHistory->groomingStatus;
        $pet=$groomingStatusHistory->reception->pet;

        Log::create([
            "action"=>'ESTATUS DE GROOMING',
            "description"=>'La mascota ' .$pet->name. ' está ' .$attention->name,
            "user_id"=>(Auth::user()->id)??null
        ]);

        $this->versionService->touch();
    }

    /**
     * Handle the GroomingStatusHistory "updated" event.
     */
    public function updated(GroomingStatusHistory $groomingStatusHistory): void
    {
        $attention=$groomingStatusHistory->groomingStatus;
        $pet=$groomingStatusHistory->reception->pet;

        Log::create([
            "action"=>'ESTATUS DE GROOMING',
            "description"=>'La mascota ' .$pet->name. ' está ' .$attention->name,
            "user_id"=>(Auth::user()->id)??null
        ]);

        $this->versionService->touch();
    }

    /**
     * Handle the GroomingStatusHistory "deleted" event.
     */
    public function deleted(GroomingStatusHistory $groomingStatusHistory): void
    {
        //
    }

    /**
     * Handle the GroomingStatusHistory "restored" event.
     */
    public function restored(GroomingStatusHistory $groomingStatusHistory): void
    {
        //
    }

    /**
     * Handle the GroomingStatusHistory "force deleted" event.
     */
    public function forceDeleted(GroomingStatusHistory $groomingStatusHistory): void
    {
        //
    }
}

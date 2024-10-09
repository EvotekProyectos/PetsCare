<?php

namespace App\Observers;

use App\Models\Log;
use App\Models\ReceptionStatusHistory;
use Illuminate\Support\Facades\Auth;

class receptionStatusHistoryObserver
{
    /**
     * Handle the ReceptionStatusHistory "created" event.
     */
    public function created(ReceptionStatusHistory $reception): void
    {
        $attention=$reception->attentionStatus;
        $pet=$reception->reception->pet;

        Log::create([
            "action"=>'ESTATUS DE RECEPCION',
            "description"=>'La mascota ' .$pet->name. ' está en ' .$attention->name,
            "user_id"=>(Auth::user()->id)??null
        ]);
    }

    /**
     * Handle the ReceptionStatusHistory "updated" event.
     */
    public function updated(ReceptionStatusHistory $reception): void
    {
        $attention=$reception->attentionStatus;
        $pet=$reception->reception->pet;

        Log::create([
            "action"=>'ESTATUS DE RECEPCION',
            "description"=>'La mascota ' .$pet->name. ' está en ' .$attention->name,
            "user_id"=>(Auth::user()->id)??null
        ]);
    }

    /**
     * Handle the ReceptionStatusHistory "deleted" event.
     */
    public function deleted(ReceptionStatusHistory $reception): void
    {
     
    }

    /**
     * Handle the ReceptionStatusHistory "restored" event.
     */
    public function restored(ReceptionStatusHistory $receptionStatusHistory): void
    {
        //
    }

    /**
     * Handle the ReceptionStatusHistory "force deleted" event.
     */
    public function forceDeleted(ReceptionStatusHistory $receptionStatusHistory): void
    {
        //
    }
}

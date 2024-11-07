<?php

namespace App\Observers;

use App\Models\Log;
use App\Models\Prescription;
use Illuminate\Support\Facades\Auth;

class prescriptionsObserver
{
    /**
     * Handle the Prescription "created" event.
     */
    public function created(Prescription $prescription): void
    {
        $pet=$prescription->pet;

        Log::create([
            
            "action"=>'CREACIÓN DE FÓRMULA MÉDICA',
            "description"=>'Se creó una nueva formula medica para '  .$pet->name. '',
            "user_id"=>(Auth::user()->id)??null
        ]);
    }

    /**
     * Handle the Prescription "updated" event.
     */
    public function updated(Prescription $prescription): void
    {
        Log::create([
            "action"=>'EDICIÓN DE FORMULA MEDICA',
            "description"=>'Se editó una formula medica ',
            "user_id"=>(Auth::user()->id)??null
        ]);
    }

    /**
     * Handle the Prescription "deleted" event.
     */
    public function deleted(Prescription $prescription): void
    {
        Log::create([
            "action"=>'ELIMINACIÓN DE FORMULA MEDICA',
            "description"=>'Se eliminó una formula medica ',
            "user_id"=>(Auth::user()->id)??null
        ]);
    }

    /**
     * Handle the Prescription "restored" event.
     */
    public function restored(Prescription $prescription): void
    {
        //
    }

    /**
     * Handle the Prescription "force deleted" event.
     */
    public function forceDeleted(Prescription $prescription): void
    {
        //
    }
}

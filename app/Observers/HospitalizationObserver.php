<?php

namespace App\Observers;

use App\Models\Hospitalization;
use App\Models\Log;
use Illuminate\Support\Facades\Auth;

class HospitalizationObserver
{
    /**
     * Handle the Hospitalization "created" event.
     */
    public function created(Hospitalization $hospitalization): void
    {
        //$pets= $hospitalization->reception->pet;
        if ($hospitalization->reception && $hospitalization->reception->pet) {
            $pets = $hospitalization->reception->pet;

        Log::create([
            "action" => "REGISTRO DE HOSPITALIZACIÓN",
            'description' => 'A la mascota ' . $pets->name. ' se le atendio en el hospital ',
            'user_id' => (Auth::user()->id) ?? null
        ]);
    }
    }
    /**
     * Handle the Hospitalization "updated" event.
     */
    public function updated(Hospitalization $hospitalization): void
    {
        //
    }

    /**
     * Handle the Hospitalization "deleted" event.
     */
    public function deleted(Hospitalization $hospitalization): void
    {
        //
    }

    /**
     * Handle the Hospitalization "restored" event.
     */
    public function restored(Hospitalization $hospitalization): void
    {
        //
    }

    /**
     * Handle the Hospitalization "force deleted" event.
     */
    public function forceDeleted(Hospitalization $hospitalization): void
    {
        //
    }
}

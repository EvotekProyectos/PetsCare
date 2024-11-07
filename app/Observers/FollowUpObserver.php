<?php

namespace App\Observers;

use App\Models\FollowUp;
use App\Models\Log;
use Illuminate\Support\Facades\Auth;

class FollowUpObserver
{
    /**
     * Handle the FollowUp "created" event.
     */
    public function created(FollowUp $followUp): void
    {
        $pets= $followUp->reception->pet;

        Log::create([
            "action" => "REGISTRO DE SEGUIMIENTO",
            'description' => 'A la mascota ' . $pets->name. ' se le registro un seguimiento ',
            'user_id' => (Auth::user()->id) ?? null
        ]);
    }

    /**
     * Handle the FollowUp "updated" event.
     */
    public function updated(FollowUp $followUp): void
    {
        //
    }

    /**
     * Handle the FollowUp "deleted" event.
     */
    public function deleted(FollowUp $followUp): void
    {
        //
    }

    /**
     * Handle the FollowUp "restored" event.
     */
    public function restored(FollowUp $followUp): void
    {
        //
    }

    /**
     * Handle the FollowUp "force deleted" event.
     */
    public function forceDeleted(FollowUp $followUp): void
    {
        //
    }
}

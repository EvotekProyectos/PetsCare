<?php

namespace App\Observers;

use App\Models\FollowupsCritic;
use App\Models\Log;
use Illuminate\Support\Facades\Auth;

class FollowupsCriticObserver
{
    /**
     * Handle the FollowupsCritic "created" event.
     */
    public function created(FollowupsCritic $followupsCritic): void
    {
        $pets= $followupsCritic->reception->pet;

        Log::create([
            "action" => "REGISTRO DE SEGUIMIENTO DE CRITICOS",
            'description' => 'A la mascota ' . $pets->name. ' se le registro un seguimiento de criticos ',
            'user_id' => (Auth::user()->id) ?? null
        ]);
    }

    /**
     * Handle the FollowupsCritic "updated" event.
     */
    public function updated(FollowupsCritic $followupsCritic): void
    {
        //
    }

    /**
     * Handle the FollowupsCritic "deleted" event.
     */
    public function deleted(FollowupsCritic $followupsCritic): void
    {
        //
    }

    /**
     * Handle the FollowupsCritic "restored" event.
     */
    public function restored(FollowupsCritic $followupsCritic): void
    {
        //
    }

    /**
     * Handle the FollowupsCritic "force deleted" event.
     */
    public function forceDeleted(FollowupsCritic $followupsCritic): void
    {
        //
    }
}

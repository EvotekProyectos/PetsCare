<?php

namespace App\Observers;
use App\Models\Log;
use App\Models\FollowupIntern;
use Illuminate\Support\Facades\Auth;

class FollowupInternObserver
{
    /**
     * Handle the FollowupIntern "created" event.
     */
    public function created(FollowupIntern $followupIntern): void
    {
        
        Log::create([
            "action" => "REGISTRO PASE DE GUARDIA INTERNO",
            'description' => 'Se registró un pase de guardia interno ',
            'user_id' => (Auth::user()->id) ?? null
        ]);
    }

    /**
     * Handle the FollowupIntern "updated" event.
     */
    public function updated(FollowupIntern $followupIntern): void
    {
        //
    }

    /**
     * Handle the FollowupIntern "deleted" event.
     */
    public function deleted(FollowupIntern $followupIntern): void
    {
        //
    }

    /**
     * Handle the FollowupIntern "restored" event.
     */
    public function restored(FollowupIntern $followupIntern): void
    {
        //
    }

    /**
     * Handle the FollowupIntern "force deleted" event.
     */
    public function forceDeleted(FollowupIntern $followupIntern): void
    {
        //
    }
}

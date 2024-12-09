<?php

namespace App\Observers;

use App\Models\FollowupSurgical;
use App\Models\Log;
use Illuminate\Support\Facades\Auth;

class FollowupSurgicalObserver
{
    /**
     * Handle the FollowupSurgical "created" event.
     */
    public function created(FollowupSurgical $followupSurgical): void
    {
        Log::create([
            "action" => "REGISTRO PASE DE GUARDIA QUIRÚRGICO",
            'description' => 'Se registró un pase de guardia quirírgico',
            'user_id' => (Auth::user()->id) ?? null
        ]);
    }

    /**
     * Handle the FollowupSurgical "updated" event.
     */
    public function updated(FollowupSurgical $followupSurgical): void
    {
        //
    }

    /**
     * Handle the FollowupSurgical "deleted" event.
     */
    public function deleted(FollowupSurgical $followupSurgical): void
    {
        //
    }

    /**
     * Handle the FollowupSurgical "restored" event.
     */
    public function restored(FollowupSurgical $followupSurgical): void
    {
        //
    }

    /**
     * Handle the FollowupSurgical "force deleted" event.
     */
    public function forceDeleted(FollowupSurgical $followupSurgical): void
    {
        //
    }
}

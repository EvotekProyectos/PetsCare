<?php

namespace App\Observers;

use App\Models\Log;
use App\Models\Surgery;
use Illuminate\Support\Facades\Auth;

class SurgeryObserver
{
    /**
     * Handle the Surgery "created" event.
     */
    public function created(Surgery $surgery): void
    {   

        Log::create([
            "action" => "REALIZACIÓN DE CIRUGÍA",
            'description' => 'Se realizó una cirugía ',
            'user_id' => (Auth::user()->id) ?? null
        ]);
    }

    /**
     * Handle the Surgery "updated" event.
     */
    public function updated(Surgery $surgery): void
    {
        //
    }

    /**
     * Handle the Surgery "deleted" event.
     */
    public function deleted(Surgery $surgery): void
    {
        //
    }

    /**
     * Handle the Surgery "restored" event.
     */
    public function restored(Surgery $surgery): void
    {
        //
    }

    /**
     * Handle the Surgery "force deleted" event.
     */
    public function forceDeleted(Surgery $surgery): void
    {
        //
    }
}

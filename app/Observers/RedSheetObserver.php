<?php

namespace App\Observers;

use App\Models\RedSheet;
use App\Models\Log;
use Illuminate\Support\Facades\Auth;

class RedSheetObserver
{
    /**
     * Handle the RedSheet "created" event.
     */
    public function created(RedSheet $redSheet): void
    {
        $pets= $redSheet->reception->pet;

        Log::create([
            "action" => "REGISTRO EN HOJA ROJA",
            'description' => 'A la mascota ' . $pets->name. ' se le agrego un servicio ',
            'user_id' => (Auth::user()->id) ?? null
        ]);
    }

    /**
     * Handle the RedSheet "updated" event.
     */
    public function updated(RedSheet $redSheet): void
    {
        //
    }

    /**
     * Handle the RedSheet "deleted" event.
     */
    public function deleted(RedSheet $redSheet): void
    {
        //
    }

    /**
     * Handle the RedSheet "restored" event.
     */
    public function restored(RedSheet $redSheet): void
    {
        //
    }

    /**
     * Handle the RedSheet "force deleted" event.
     */
    public function forceDeleted(RedSheet $redSheet): void
    {
        //
    }
}

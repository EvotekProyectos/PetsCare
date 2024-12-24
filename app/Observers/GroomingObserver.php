<?php

namespace App\Observers;

use App\Models\Grooming;
use App\Models\Log;
use Illuminate\Support\Facades\Auth;

class GroomingObserver
{
    /**
     * Handle the Grooming "created" event.
     */
    public function created(Grooming $grooming): void
    {
        $pets= $grooming->reception->pet;

        Log::create([
            "action" => "REGISTRO DE SERVICIO EN GROOMING",
            'description' => 'A la mascota ' . $pets->name. ' se le agrego un servicio de grooming ',
            'user_id' => (Auth::user()->id) ?? null
        ]);
    }

    /**
     * Handle the Grooming "updated" event.
     */
    public function updated(Grooming $grooming): void
    {
        $pets= $grooming->reception->pet;

        Log::create([
            "action" => "EDICIÓN DE SERVICIO EN GROOMING",
            'description' => 'A la mascota ' . $pets->name. ' se le edito un servicio de grooming ',
            'user_id' => (Auth::user()->id) 
        ]);
    }

    /**
     * Handle the Grooming "deleted" event.
     */
    public function deleted(Grooming $grooming): void
    {
        $pets= $grooming->reception->pet;

        Log::create([
            "action" => "ELIMINACIÓN DE SERVICIO EN GROOMING",
            'description' => 'A la mascota ' . $pets->name. ' se le elimino un servicio de grooming ',
            'user_id' => (Auth::user()->id) 
        ]);
    }

    /**
     * Handle the Grooming "restored" event.
     */
    public function restored(Grooming $grooming): void
    {
        //
    }

    /**
     * Handle the Grooming "force deleted" event.
     */
    public function forceDeleted(Grooming $grooming): void
    {
        //
    }
}

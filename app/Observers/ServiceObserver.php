<?php

namespace App\Observers;

use App\Models\Log;
use App\Models\Service;
use Illuminate\Support\Facades\Auth;

class ServiceObserver
{
    /**
     * Handle the Service "created" event.
     */
    public function created(Service $service): void
    {
        Log::create([
            "action" => "CREACION DE NUEVO SERVICIO",
            'description' => 'Se creo un nuevo servicio: ' ,
            'user_id' => (Auth::user()->id) ?? null
        ]);
    }

    /**
     * Handle the Service "updated" event.
     */
    public function updated(Service $service): void
    {
        Log::create([
            'action' => 'EDICIÓN DE SERVICIO',
            'description' => 'Se edito el servicio: ' ,
            'user_id' => Auth::user()->id
        ]);
    }

    /**
     * Handle the Service "deleted" event.
     */
    public function deleted(Service $service): void
    {
        Log::create([
            'action' => 'ELIMINACION DE SERVICIO',
            'description' => 'Se elimino el servicio: ' ,
            'user_id' => Auth::user()->id
        ]);
    }

    /**
     * Handle the Service "restored" event.
     */
    public function restored(Service $service): void
    {
        //
    }

    /**
     * Handle the Service "force deleted" event.
     */
    public function forceDeleted(Service $service): void
    {
        //
    }
}

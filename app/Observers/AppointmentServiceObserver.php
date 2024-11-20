<?php

namespace App\Observers;

use App\Models\AppointmentService;
use App\Models\Log;
use Illuminate\Support\Facades\Auth;

class AppointmentServiceObserver
{
    /**
     * Handle the AppointmentService "created" event.
     */
    public function created(AppointmentService $appointmentService): void
    {
        $pets= $appointmentService->reception->pet;

        Log::create([
            "action" => "REGISTRO DE SERVICIO EN CONSULTA",
            'description' => 'A la mascota ' . $pets->name. ' se le agrego un servicio ',
            'user_id' => (Auth::user()->id) ?? null
        ]);
    }

    /**
     * Handle the AppointmentService "updated" event.
     */
    public function updated(AppointmentService $appointmentService): void
    {
        //
    }

    /**
     * Handle the AppointmentService "deleted" event.
     */
    public function deleted(AppointmentService $appointmentService): void
    {
        //
    }

    /**
     * Handle the AppointmentService "restored" event.
     */
    public function restored(AppointmentService $appointmentService): void
    {
        //
    }

    /**
     * Handle the AppointmentService "force deleted" event.
     */
    public function forceDeleted(AppointmentService $appointmentService): void
    {
        //
    }
}

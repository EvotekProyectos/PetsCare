<?php

namespace App\Observers;

use App\Models\Log;
use App\Models\Schedule;
use Illuminate\Support\Facades\Auth;

class ScheduleObserver
{
    /**
     * Handle the Schedule "created" event.
     */
    public function created(Schedule $schedule): void
    {
        Log::create([
            'action' => 'CREACIÓN DE NUEVO HORARIO',
            'description' => 'Se creo un nuevo horario: ' .$schedule->name,
            'user_id' => (Auth::user()->id) ?? null
        ]);
    }

    /**
     * Handle the Schedule "updated" event.
     */
    public function updated(Schedule $schedule): void
    {
        Log::create([
            'action' => 'EDICIÓN DE HORARIO',
            'description' => 'Se edito el horario: ' .$schedule->name,
            'user_id' => (Auth::user()->id) 
        ]);
    }

    /**
     * Handle the Schedule "deleted" event.
     */
    public function deleted(Schedule $schedule): void
    {
        Log::create([
            'action' => 'ELIMINACIÓN DE HORARIO',
            'description' => 'Se elimino el horario: ' .$schedule->name,
            'user_id' => (Auth::user()->id) 
        ]);
    }

    /**
     * Handle the Schedule "restored" event.
     */
    public function restored(Schedule $schedule): void
    {
        //
    }

    /**
     * Handle the Schedule "force deleted" event.
     */
    public function forceDeleted(Schedule $schedule): void
    {
        //
    }
}

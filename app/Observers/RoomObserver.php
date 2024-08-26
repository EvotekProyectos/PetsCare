<?php

namespace App\Observers;

use App\Models\Log;
use App\Models\Room;
use Illuminate\Support\Facades\Auth;

class RoomObserver
{
    /**
     * Handle the Room "created" event.
     */
    public function created(Room $room): void
    {
        Log::create([
            "action" => "CREACION DE NUEVO CONSULTORIO",
            'description' => 'Se creo un nuevo consultorio: ' . $room->name,
            'user_id' => (Auth::user()->id) ?? null
        ]);
    }

    /**
     * Handle the Room "updated" event.
     */
    public function updated(Room $room): void
    {
        Log::create([
            'action' => 'EDICIÓN DE CONSULTORIO',
            'description' => 'Se edito el consultorio: ' . $room->name,
            'user_id' => Auth::user()->id
        ]);
    }

    /**
     * Handle the Room "deleted" event.
     */
    public function deleted(Room $room): void
    {
        Log::create([
            'action' => 'ELIMINACION DE CONSULTORIO',
            'description' => 'Se elimino el consultorio: ' . $room->name,
            'user_id' => Auth::user()->id
        ]);
    }

    /**
     * Handle the Room "restored" event.
     */
    public function restored(Room $room): void
    {
        //
    }

    /**
     * Handle the Room "force deleted" event.
     */
    public function forceDeleted(Room $room): void
    {
        //
    }
}

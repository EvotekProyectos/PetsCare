<?php

namespace App\Observers;

use App\Models\Log;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        $log = Log::create([
            'action' => 'NUEVO USUARIO',
            'description' => 'Se creó un nuevo usuario: ' . $user->name,
            'user_id' => (Auth::user()->id) ?? null
        ]);
   
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        $log = Log::create([
            'action' => 'EDICIÓN DE USUARIO',
            'description' => 'Se editó al usuario ' . $user->name,
            'user_id' => Auth::user()->id
        ]);
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        $log = Log::create([
            'action' => 'ELIMINACIÓN DE USUARIO',
            'description' => 'Se eliminó al usuario ' . $user->name,
            'user_id' => Auth::user()->id
        ]);
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }
}

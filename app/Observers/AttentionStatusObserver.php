<?php

namespace App\Observers;

use App\Models\Log;
use App\Models\AttentionStatus;
use Illuminate\Support\Facades\Auth;

class AttentionStatusObserver
{
    /**
     * Handle the AttentionStatus "created" event.
     */
    public function created(AttentionStatus $attentionStatus): void
    {
        $log = Log::create([
            'action' => 'NUEVO ESTADO DE ATENCIÓN',
            'description' => 'Se creó un nuevo estado de atención: ' . $attentionStatus->name,
            'user_id' => (Auth::user()->id) ?? null
        ]);
    }

    /**
     * Handle the AttentionStatus "updated" event.
     */
    public function updated(AttentionStatus $attentionStatus): void
    {
        $log = Log::create([
            'action' => 'EDICIÓN DE ESTADO DE ATENCIÓN',
            'description' => 'Se editó al estado de atención ' . $attentionStatus->name,
            'user_id' => Auth::user()->id
        ]);
    }

    /**
     * Handle the AttentionStatus "deleted" event.
     */
    public function deleted(AttentionStatus $attentionStatus): void
    {
        $log = Log::create([
            'action' => 'ELIMINACIÓN DE ESTADO DE ATENCIÓN',
            'description' => 'Se eliminó al estado de atención ' . $attentionStatus->name,
            'user_id' => Auth::user()->id
        ]);
    }

    /**
     * Handle the AttentionStatus "restored" event.
     */
    public function restored(AttentionStatus $attentionStatus): void
    {
        //
    }

    /**
     * Handle the AttentionStatus "force deleted" event.
     */
    public function forceDeleted(AttentionStatus $attentionStatus): void
    {
        //
    }
}

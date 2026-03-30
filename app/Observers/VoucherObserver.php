<?php

namespace App\Observers;

use App\Models\Log;
use App\Models\Voucher;

class VoucherObserver
{
    /**
     * Handle the Voucher "created" event.
     */
    public function created(Voucher $voucher): void
    {
        Log::create([
            'action'      => 'CREACIÓN DE VALE',
            'description' => "Se creó el vale {$voucher->folio} para la recepción {$voucher->reception_id}",
            'user_id'     => auth()->id(),
        ]);
    }


    /**
     * Handle the Voucher "updated" event.
     */
    public function updated(Voucher $voucher): void
    {
        if (!$voucher->wasChanged('status')) return;

        $statusNuevo    = $voucher->status;

        $mensajes = [
            'Surtido' => [
                'action'      => 'VALE SURTIDO',
                'description' => "Se surtió el vale {$voucher->folio}",
            ],
            'Rechazado' => [
                'action'      => 'VALE RECHAZADO',
                'description' => "Se rechazó el vale {$voucher->folio}. Motivo: {$voucher->rejection_reason}",
            ],
            'Cancelado' => [
                'action'      => 'VALE CANCELADO',
                'description' => "Se canceló el vale {$voucher->folio}. Motivo: {$voucher->cancellation_reason}",
            ],
        ];

        if (!isset($mensajes[$statusNuevo])) return;

        Log::create([
            'action'      => $mensajes[$statusNuevo]['action'],
            'description' => $mensajes[$statusNuevo]['description'],
            'user_id'     => auth()->id(),
        ]);
    }

    /**
     * Handle the Voucher "deleted" event.
     */
    public function deleted(Voucher $voucher): void
    {
        //
    }

    /**
     * Handle the Voucher "restored" event.
     */
    public function restored(Voucher $voucher): void
    {
        //
    }

    /**
     * Handle the Voucher "force deleted" event.
     */
    public function forceDeleted(Voucher $voucher): void
    {
        //
    }
}

<?php

namespace App\Observers;

use App\Models\AdvancePayment;
use App\Models\Log;
use App\Services\ReceptionVersionService;
use Illuminate\Support\Facades\Auth;

class AdvancePaymentObserver
{
    public function __construct(private ReceptionVersionService $versionService)
    {
    }

    /**
     * Handle the AdvancePayment "created" event.
     */
    public function created(AdvancePayment $advancePayment): void
    {
        $pet = $advancePayment->reception->pet;
        Log::create([
            "action" => "REGISTRO DE ANTICIPO",
            'description' => 'A la mascota ' . $pet->name. ' se le genero un pago de anticipo ',
            'user_id' => (Auth::user()->id) ?? null
        ]);

        $this->versionService->touch();
    }

    /**
     * Handle the AdvancePayment "updated" event.
     */
    public function updated(AdvancePayment $advancePayment): void
    {
        $pet = $advancePayment->reception->pet;
        Log::create([
            "action" => "EDICIÓN DE ANTICIPO",
            'description' => 'A la mascota ' . $pet->name. ' se le edito un pago de anticipo ',
            'user_id' => (Auth::user()->id)
        ]);

        $this->versionService->touch();
    }

    /**
     * Handle the AdvancePayment "deleted" event.
     */
    public function deleted(AdvancePayment $advancePayment): void
    {
        $pet = $advancePayment->reception->pet;
        Log::create([
            "action" => "ELIMINACIÓN DE ANTICIPO",
            'description' => 'A la mascota ' . $pet->name. ' se le elimino un pago de anticipo ',
            'user_id' => (Auth::user()->id)
        ]);

        $this->versionService->touch();
    }

    /**
     * Handle the AdvancePayment "restored" event.
     */
    public function restored(AdvancePayment $advancePayment): void
    {
        //
    }

    /**
     * Handle the AdvancePayment "force deleted" event.
     */
    public function forceDeleted(AdvancePayment $advancePayment): void
    {
        //
    }
}

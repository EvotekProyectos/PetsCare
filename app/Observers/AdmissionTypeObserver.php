<?php

namespace App\Observers;

use App\Models\Log;
use App\Models\AdmissionType;
use Illuminate\Support\Facades\Auth;

class AdmissionTypeObserver
{
    /**
     * Handle the AdmissionType "created" event.
     */
    public function created(AdmissionType $admissionType): void
    {
        $log = Log::create([
            'action' => 'NUEVO TIPO DE INGRESO',
            'description' => 'Se creó un nuevo tipo de ingreso: ' . $admissionType->name,
            'user_id' => (Auth::user()->id) ?? null
        ]);
    }

    /**
     * Handle the AdmissionType "updated" event.
     */
    public function updated(AdmissionType $admissionType): void
    {
        $log = Log::create([
            'action' => 'EDICIÓN DE TIPO DE INGRESO',
            'description' => 'Se editó el tipo de ingreso ' . $admissionType->name,
            'user_id' => Auth::user()->id
        ]);
    }
    /**
     * Handle the AdmissionType "deleted" event.
     */
    public function deleted(AdmissionType $admissionType): void
    {
        $log = Log::create([
            'action' => 'ELIMINACIÓN DE TIPO DE INGRESO',
            'description' => 'Se eliminó el tipo de ingreso ' . $admissionType->name,
            'user_id' => Auth::user()->id
        ]);
    }


    /**
     * Handle the AdmissionType "restored" event.
     */
    public function restored(AdmissionType $admissionType): void
    {
        //
    }

    /**
     * Handle the AdmissionType "force deleted" event.
     */
    public function forceDeleted(AdmissionType $admissionType): void
    {
        //
    }
}

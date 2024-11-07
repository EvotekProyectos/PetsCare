<?php

namespace App\Observers;

use App\Models\Log;
use App\Models\VaccineCertificate;
use Illuminate\Support\Facades\Auth;

class VaccineCertificateObserver
{
    /**
     * Handle the VaccineCertificate "created" event.
     */
    public function created(VaccineCertificate $vaccineCertificate): void
    {
        $pets= $vaccineCertificate->pet;
        $type = $vaccineCertificate->service;

        Log::create([
            "action" => "NUEVO REGISTRO EN CARTILLA VIRTUAL",
            'description' => 'A la mascota ' . $pets->name. ' se le registro una ' .$type->name,
            'user_id' => (Auth::user()->id) ?? null
        ]);
    }

    /**
     * Handle the VaccineCertificate "updated" event.
     */
    public function updated(VaccineCertificate $vaccineCertificate): void
    {
        Log::create([
            'action' => 'EDICIÓN DE CERTIFICADO VACUNACION',
            'description' => 'Se edito el certificado vacunacion: ' ,
            'user_id' => Auth::user()->id
        ]);
    }

    /**
     * Handle the VaccineCertificate "deleted" event.
     */
    public function deleted(VaccineCertificate $vaccineCertificate): void
    {
        Log::create([
            'action' => 'ELIMINACION DE CERTIFICADO VACUNACION',
            'description' => 'Se elimino el certificado vacunacion: ' ,
            'user_id' => Auth::user()->id
        ]);
    }

    /**
     * Handle the VaccineCertificate "restored" event.
     */
    public function restored(VaccineCertificate $vaccineCertificate): void
    {
        //
    }

    /**
     * Handle the VaccineCertificate "force deleted" event.
     */
    public function forceDeleted(VaccineCertificate $vaccineCertificate): void
    {
        //
    }
}

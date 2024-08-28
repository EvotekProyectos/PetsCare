<?php

namespace Database\Seeders;

use App\Models\PetsStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PetStatusesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $Statuses = [
            ["name" => 'PACIENTE EN ESTADO MUY GAVE (U.C.I)', "description" => "Revisar constantemente fisiológias, constantemente vía permeable y vía aéra libre. Concentración de oxigeno", "color" => "#F03124"],
            ["name" => 'PACIENTE GRAVE ESTABLE', "description" => "Revisar constantemente fisiológias, constantemente vía permeable y vía aéra libre.", "color" => "#E8E478"],
            ["name" => 'PACIENTE ESTABLE', "description" => "Revisar constantemente fisiológias, constantemente vía permeable y vía aéra libre.", "color" => "#347356"],
            ["name" => 'PACIENTE GRAVE ESTABLE ', "description" => "Revisar constantemente fisiológias, constantemente vía permeable y vía aéra libre. Estado de conciencia y tiempo de llenado capilar (hemorragias)", "color" => "#3a48a1"],
        ];

        foreach ($Statuses as $status) {
            PetsStatus::Create($status);
        }
    }
}

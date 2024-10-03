<?php

namespace Database\Seeders;

use App\Models\Reason;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReasonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $reasons = [
            ['name' => 'Consulta General'],
            ['name' => 'Consulta de Seguimiento'],
            ['name' => 'Medicina Preventiva'],
            ['name' => 'Consulta especialidad'],
            ['name' => 'Curación/Cambio de vendaje'],
            ['name' => 'Retiro de sutura'],
            ['name' => 'Servicios externos'],
            ['name' => 'Estudios de laboratorio'],
        ];

        foreach ($reasons as $reason){
            Reason::create($reason);
        }
    }
}

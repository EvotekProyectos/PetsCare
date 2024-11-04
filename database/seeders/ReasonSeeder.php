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
            ['name' => 'Consulta General', "color" => '#6cc3e3'],
            ['name' => 'Consulta de Seguimiento', "color" => '#917aac'],
            ['name' => 'Medicina Preventiva', "color" => 'f8a693'],
            ['name' => 'Consulta especialidad', "color" => 'fff795'],
            ['name' => 'Curación/Cambio de vendaje',"color" => '#95ffea'],
            ['name' => 'Retiro de sutura', "color" => '#FF69B4'],
            ['name' => 'Servicios externos', "color" => '#A52A2A'],
            ['name' => 'Estudios de laboratorio',"color" => '#FF69B4'],
        ];

        foreach ($reasons as $reason){
            Reason::create($reason);
        }
    }
}

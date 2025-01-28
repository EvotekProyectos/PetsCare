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
            ['name' => 'Consulta General', "color" => '#5EB1E4'],
            ['name' => 'Consulta de Seguimiento', "color" => '#8C70AE'],
            ['name' => 'Medicina Preventiva', "color" => '#FF8160'],
            ['name' => 'Consulta especialidad', "color" => '#FFF176'],
            ['name' => 'Curación/Cambio de vendaje',"color" => '#82EAD1'],
            ['name' => 'Retiro de sutura', "color" => '#FF6FB0'],
            ['name' => 'Servicios externos', "color" => '#FF426D'],
            ['name' => 'Estudios de laboratorio',"color" => '#C1F387'],
        ];

        foreach ($reasons as $reason){
            Reason::create($reason);
        }
    }
}

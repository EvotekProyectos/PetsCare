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
            ['name' => 'Consulta General', "color" => '#2E7FB5', 'articulo_id'=>'104684'],
            ['name' => 'Consulta de Seguimiento', "color" => '#623F87','articulo_id'=>'104684'],
            ['name' => 'Medicina Preventiva', "color" => '#E64A26','articulo_id'=>'104684'],
            ['name' => 'Consulta especialidad', "color" => '#CC9900','articulo_id'=>'104702'],
            ['name' => 'Curación/Cambio de vendaje',"color" => '#0A8568','articulo_id'=>'104732'],
            ['name' => 'Retiro de sutura', "color" => '#E62E86', 'articulo_id'=>'104684'],
            ['name' => 'Servicios externos', "color" => '#CC1141', 'articulo_id'=>'104684'],
            ['name' => 'Estudios de laboratorio',"color" => '#00004D','articulo_id'=>'105582'],
        ];

        foreach ($reasons as $reason){
            Reason::create($reason);
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\DateType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class dateTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dateTypes = [
            ['name' => 'Consulta General', 'reason_id'=>'1'],
            ['name' => 'Consulta de Seguimiento','reason_id'=>'2'],
            ['name' => 'Medicina Preventiva','reason_id'=>'3'],
            ['name' => 'Consulta especialidad','reason_id'=>'4'],
            ['name' => 'Curación/Cambio de vendaje', 'reason_id'=>'5'],
            ['name' => 'Retiro de sutura', 'reason_id'=>'6'],
            ['name' => 'Servicios externos', 'reason_id'=>'7'],
            ['name' => 'Estudios de laboratorio', 'reason_id'=>'8'],
            ['name' => 'Grooming'],
          
        ];

        foreach ($dateTypes as $dateType){
            DateType::create($dateType);
        }
    }
}

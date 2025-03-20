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
            ['name' => 'Consulta General'],
            ['name' => 'Consulta de Seguimiento'],
            ['name' => 'Medicina Preventiva'],
            ['name' => 'Curación/Cambio de vendaje'],
            ['name' => 'Retiro de sutura'],
            ['name' => 'Grooming'],
          
        ];

        foreach ($dateTypes as $dateType){
            DateType::create($dateType);
        }
    }
}

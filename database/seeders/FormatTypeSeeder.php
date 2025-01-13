<?php

namespace Database\Seeders;

use App\Models\FormatType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FormatTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $formatTypes = [
            ['name' => 'Autorización para Hospitalización'],
            ['name' => 'Alta Voluntaria'],
            ['name' => 'Autorización de Procedimientos Anestésicos y Quirúrgicos'],
            ['name' => 'Responsiva de Estudios de Gabinete'],
        ];

        foreach ($formatTypes as $formatType){
            FormatType::create($formatType);
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\ReceptionType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReceptionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    
    public function run(): void
    {
        $receptionTypes = [
            ["name" => "Consulta"],
            ["name" => "Hospitalización"],
            ["name" => "Estética"],
            ["name" => "Hotel"],
            ["name" => "Cremación"],
        ];

        foreach ($receptionTypes as $receptionType) {
            ReceptionType::create($receptionType);
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\ReproductiveStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReproductiveStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $reproductivestatuses = [
            ['name' => 'Esterilizado'],
            ['name' => 'No esterilizado'],
            ['name' => 'Desconocido'],
        ];

        foreach ($reproductivestatuses as $reproductivestatus){
            ReproductiveStatus::create($reproductivestatus);
        }
    }
}

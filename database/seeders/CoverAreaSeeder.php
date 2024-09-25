<?php

namespace Database\Seeders;

use App\Models\CoverArea;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CoverAreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $areas = [
            ['name' => 'Hospita Internos', 'color' => '#ffc000'],
            ['name' => 'Consulta', 'color' => '#01b0f1'],
            ['name' => 'Auxiliar', 'color' => '#fb0200'],
            ['name' => 'Quirurgicos', 'color' => '#0070c2'],
            ['name' => 'Nocturno', 'color' => '#91cf4f'],
        ];

        foreach ($areas as $area){
            CoverArea::create($area);
        }
    }
}

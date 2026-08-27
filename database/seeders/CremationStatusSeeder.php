<?php

namespace Database\Seeders;

use App\Models\CremationStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CremationStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cremationStatuses = [
            ['name' => 'En espera de recolectar ', 'color' => '#FF2C2C'],
            ['name' => 'En proceso', 'color' => '#FFBE33'],
            ['name' => 'Completado', 'color' => '#0275d8'],
            ['name' => 'Entregado', 'color' => '#5cb85c'],
            ['name' => 'Creado', 'color' => '#0dcaf0'],
        ];

        foreach ($cremationStatuses as $as) {
            CremationStatus::firstOrCreate(['name' => $as['name']], $as);
        }
    }
}

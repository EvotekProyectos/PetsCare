<?php

namespace Database\Seeders;

use App\Models\GroomingStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GroomingStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $groomingStatuses = [
            ["name" => 'Atendiendo', "color" => "#FFBE33"],
            ["name" => 'Listo', "color" => "#5cb85c"],
            ["name" => 'En espera', "color" => "#FF2C2C"],
            ["name" => 'Trasladado', "color" => "#6c757d"],
            ["name" => 'Creado', "color" => "#0dcaf0"],
        ];

        foreach ($groomingStatuses as $as) {
            GroomingStatus::firstOrCreate(['name' => $as['name']], $as);
        }
    }
}

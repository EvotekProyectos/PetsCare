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
            ["name" => 'Atendiendo', "color" => "#F3E586"],
            ["name" => 'Listo', "color" => "#84E78A"],
        ];

        foreach ($groomingStatuses as $as) {
            GroomingStatus::Create($as);
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\StatusSurgery;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StatusSurgeriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $StatusSurgeries = [
            ["name" => 'En espera', "color" => "#FF2C2C"],
            ["name" => 'En cirugía', "color" => "#F3E586"],
            ["name" => 'Cirugía completada', "color" => "#84E78A"],
        ];

        foreach ($StatusSurgeries  as $ss) {
            StatusSurgery::Create($ss);
        }
    }
}

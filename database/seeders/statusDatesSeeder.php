<?php

namespace Database\Seeders;

use App\Models\StatusDate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class statusDatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statusDates = [
            ["name" => 'En espera de confirmar', "color" => "#FF2C2C"],
            ["name" => 'Reagendada', "color" => "#F3E586"],
            ["name" => 'Confirmada', "color" => "#84E78A"],
        ];

        foreach ($statusDates  as $statusDate) {
            StatusDate::Create($statusDate);
        }
    }
}

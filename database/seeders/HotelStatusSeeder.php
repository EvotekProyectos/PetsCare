<?php

namespace Database\Seeders;

use App\Models\HotelStatus;
use Illuminate\Database\Seeder;

class HotelStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $hotelStatuses = [
            ["name" => 'En estancia', "color" => "#FFBE33"],
            ["name" => 'Finalizado', "color" => "#5cb85c"],
            ['name' => 'Trasladado', 'color' => '#6c757d'],
        ];

        foreach ($hotelStatuses as $hs) {
            HotelStatus::firstOrCreate(['name' => $hs['name']], $hs);
        }
    }
}

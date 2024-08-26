<?php

namespace Database\Seeders;

use App\Models\Room;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rooms = [
            ['name' => 'Consultorio 1'],
            ['name' => 'Consultorio 2'],
        ];

        foreach ($rooms as $room){
            Room::create($room);
        }
    }
}

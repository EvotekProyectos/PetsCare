<?php

namespace Database\Seeders;

use App\Models\Shift;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ShiftSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $shifts = [
            ["name" => 'Matutino', "begin" => "8:00", "end" => "16:00"],
            ["name" => 'Vespertino', "begin" => "16:00", "end" => "00:00"],
            ["name" => 'Nocturno', "begin" => "00:00", "end" => "8:00"],
        ];

        foreach ($shifts as $shift){
            Shift::create($shift);
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\HospitalDischarge;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HospitalDischargeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $discharges = [
            ['name' => 'Alta normal', "color" => '#2BEA91'],
            ['name' => 'Alta voluntaria ', "color" => '#2DAAF8'],
            ['name' => 'Alta por fallecimiento', "color" => '#F862AA'],
        ];

        foreach ($discharges as $discharge){
           HospitalDischarge::create($discharge);
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\HospitalizationStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HospitalizationStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $hospitalStatuses = [
            ['name' => 'Hospitalizado', 'color' => '#FFBE33'],
            ['name' => 'Dado de alta', 'color' => '#5cb85c',],
            ['name' => 'Trasladado', 'color' => '#6c757d'],
        ];

        foreach ($hospitalStatuses as $as) {
            HospitalizationStatus::firstOrCreate(['name' => $as['name']], $as);
        }
    }
}

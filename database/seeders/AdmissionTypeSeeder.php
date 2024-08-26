<?php

namespace Database\Seeders;

use App\Models\AdmissionType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdmissionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admissionTypes = [
            ["name" => "Hospital normal"],
            ["name" => "Terapia intensiva"],
            ["name" => "Ambulatorio"],
            ["name" => "Pensión médica"],
        ];

        foreach ($admissionTypes as $admissionType) {
            AdmissionType::create($admissionType);
            
        }
    }
}

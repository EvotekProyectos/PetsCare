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
            ["name" => "Hospital normal", "articulo_id"=>"104674"],
            ["name" => "Terapia intensiva", "articulo_id"=>"29641"],
            ["name" => "Ambulatorio", "articulo_id"=>"104681"],
            ["name" => "Pensión médica",  "articulo_id"=>"136085"],
        ];

        foreach ($admissionTypes as $admissionType) {
            AdmissionType::create($admissionType);
            
        }
    }
}

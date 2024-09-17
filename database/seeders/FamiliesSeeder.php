<?php

namespace Database\Seeders;

use App\Models\Family;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FamiliesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $families = [
            ["name" => 'Alamilla Valdes', "phone" => "8442477138", "email" => "alejandra.alamilla@evotek.com.mx",
        "address" => "Saltillo, La Aurora", "contact_name" => "Lily", "contact_number" => "8442477148"],
        ];

        foreach ($families as $fam) {
            Family::create($fam);
        }
    }
}

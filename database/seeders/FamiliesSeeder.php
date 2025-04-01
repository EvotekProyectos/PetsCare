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
            ["name" => 'Alamilla Valdes', "phone" => "8442477138", "email" => "alealamilla27@gmail.com",
        "address" => "Saltillo, La Aurora", "contact_name" => "Lily", "contact_number" => "8442477148"],
        ["name" => 'Lopez Armendariz', "phone" => "8443922106", "email" => "estrella.armendariz@evotek.com.mx",
        "address" => "Calle Azel #1254", "contact_name" => "Estrella", "contact_number" => "8443922106"],
        ];

        foreach ($families as $fam) {
            Family::create($fam);
        }
    }
}

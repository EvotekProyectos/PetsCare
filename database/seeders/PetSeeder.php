<?php

namespace Database\Seeders;

use App\Models\Pet;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pets = [
            ["family_id" => '1', "number_chip" => "1527", "name" => 'Happy', "specie" => 'canino', "raza" => 'French Poodle',
        "gender_id" => '2', "birthday" => '2010-07-21', "reproductive_status_id" => '2', "weight" => '10kg', 
        "physic_descrip" => 'Perro blanco chico', "notes" => 'Por su edad tiene varios granos en su cuerpo',
        "pet_classification_id" => '2', "deceased" => '0' ],
        ];

        foreach ($pets as $pet) {
            Pet::create($pet);
        }
    }
}

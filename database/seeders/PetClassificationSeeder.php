<?php

namespace Database\Seeders;

use App\Models\PetClassification;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PetClassificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $petclassifications = [
            ['name' => 'Agresivo'],
            ['name' => 'Nervioso'],
        ];

        foreach ($petclassifications as $petclassification){
            PetClassification::create($petclassification);
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\Species;
use Illuminate\Database\Seeder;

class SpeciesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $species = [
            ['name' => 'Caninos', 'icon' => 'cil--dog'],
            ['name' => 'Felinos', 'icon' => 'fa6-solid--cat'],
            ['name' => 'Aves', 'icon' => 'mingcute--bird-line'],
            ['name' => 'Roedores ', 'icon' => 'fluent-emoji-high-contrast--hamster'],
            ['name' => 'Equinos / Équidos', 'icon' => 'la--horse-head'],
            ['name' => 'Bovinos / Vacunos', 'icon' => 'mdi--cow'],
            ['name' => 'Ovinos y Caprinos', 'icon' => 'griddy-icons--sheep'],
            ['name' => 'Porcinos / Suidos', 'icon' => 'griddy-icons--pig'],
            ['name' => 'Camélidos', 'icon' => 'hugeicons--camel'],
            ['name' => 'Primates', 'icon' => 'emojione-monotone--monkey '],
            ['name' => 'Úrsidos / Osos', 'icon' => 'pinhead--bear'],
            ['name' => 'Cetáceos', 'icon' => 'icon-park-outline--dolphin'],
            ['name' => 'Reptiles', 'icon' => 'fluent-emoji-high-contrast--lizard'],
            ['name' => 'Anfibios', 'icon' => 'fa6-solid--frog'],
            ['name' => 'Peces', 'icon' => 'tabler--fish']
        ];

        foreach ($species as $row) {
            Species::updateOrCreate(
                ['name' => $row['name']],
                ['icon' => $row['icon'], 'active' => true]
            );
        }
    }
}

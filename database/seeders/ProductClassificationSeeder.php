<?php

namespace Database\Seeders;

use App\Models\ProductClassification;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductClassificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $classifications = [
            ['name' => 'Cirugias'],
            ['name' => 'Laboratorio'],
            ['name' => 'Imagen'],
            ['name' => 'Servicios de Hospital']
        ];

        foreach ($classifications as $class) {
            ProductClassification::create($class);
        }
    }
}

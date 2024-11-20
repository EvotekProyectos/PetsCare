<?php

namespace Database\Seeders;

use App\Models\FormatType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FormatTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $formatTypes = [
            ['name' => 'Hospital_authorization'],

        ];

        foreach ($formatTypes as $formatType){
            FormatType::create($formatType);
        }
    }
}

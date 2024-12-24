<?php

namespace Database\Seeders;

use App\Models\CmType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CmTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cms = [
            ['name' => 'Interno'],
            ['name' => 'Externo'],
        ];

        foreach ($cms as $cm){
            CmType::create($cm);
        }
    }
}

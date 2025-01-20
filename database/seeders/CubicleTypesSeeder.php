<?php

namespace Database\Seeders;

use App\Models\CubicleType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CubicleTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            ['name' => 'Pensión chica', 'id_microsip'=>'136655'],
            ['name' => 'Pensión mediana','id_microsip'=>'136659'],
            ['name' => 'Pensión suite', 'id_microsip'=>'136663']
        ];

        foreach ($types as $type){
            CubicleType::create($type);
        }
    }
}

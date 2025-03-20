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
            ['name' => 'Pension chica', 'color' =>'#65d8bd','id_microsip'=>'136655'],
            ['name' => 'Pension mediana','color' =>'#4fd4ff','id_microsip'=>'136659'],
            ['name' => 'Pension suite', 'color' =>'#ff7c82','id_microsip'=>'136663'],
            ['name' => 'Pension gatos', 'color' =>'#d780ff','id_microsip'=>'136667']
                ];

        foreach ($types as $type){
            CubicleType::create($type);
        }
    }
}

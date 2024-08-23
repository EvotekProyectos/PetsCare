<?php

namespace Database\Seeders;

use App\Models\Area;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $areas = [
            [
                'name' => 'Quirúgicos'
            ],
            [
                'name' => 'Cuidado intensivo'
            ],
            [
                'name' => 'Internos'
            ],
            [
                'name' => 'Felinos y exóticos'
            ],
            [
                'name' => 'Infecciosos'
            ],
        ];

        foreach ($areas as $area) {
            Area::create($area);
        }
    }
}

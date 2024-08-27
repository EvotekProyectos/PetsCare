<?php

namespace Database\Seeders;

use App\Models\FamClassification;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FamClassificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $famclassifications = [
            ['name' => 'Aprensivo'],
            ['name' => 'Conflictivo'],
        ];

        foreach ($famclassifications as $famclassification){
            FamClassification::create($famclassification);
        }
    }
}

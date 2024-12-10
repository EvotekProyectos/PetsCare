<?php

namespace Database\Seeders;

use App\Models\SurgeryPack;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SurgeryPackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $packs = [
            ['name' => 'Paquete Básico', 'total' => '750.00', 'catheterization_price' => '50.00',
             'preanesthetic_price' => '200.00', 'monitoring_price' => '100.00', 'surgical_clothing_price' => '200.00', 'preparations_price' => '100.00', 'observation_price' => '100.00'],
        ];

        foreach ($packs as $pack){
            SurgeryPack::create($pack);
        }
    }
}

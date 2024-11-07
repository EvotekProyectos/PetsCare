<?php

namespace Database\Seeders;

use App\Models\ProductType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            ['product_classification_id' => '4', 'microsip_id' => '5712', 'name' => 'DIA DE HOSPITAL CHICO-MEDIANO', 'price' => '350.00'],
            ['product_classification_id' => '3', 'microsip_id' => '5602', 'name' => 'ULTRASONIDO CARDIACO', 'price' => '1500.00'],
            ['product_classification_id' => '2', 'microsip_id' => '5628', 'name' => 'Q.S (15 VALORES)', 'price' => '1200.00'],
            ['product_classification_id' => '1', 'microsip_id' => '5721', 'name' => 'OVH (1-10KG)', 'price' => '2300.00'],
        ];

        foreach ($types as $type) {
            ProductType::create($type);
        }
    }
}

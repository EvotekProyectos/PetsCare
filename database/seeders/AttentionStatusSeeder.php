<?php

namespace Database\Seeders;

use App\Models\AttentionStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AttentionStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $attentionStatuses = [
            ["name" => 'Finalizada', "color" => "#5cb85c"],
            ["name" => 'En espera', "color" => "#FF2C2C"],
            ["name" => 'En consulta', "color" => "#FFBE33"],
            ["name" => 'Trasladado', "color" => "#6c757d"],
        ];

        foreach ($attentionStatuses as $as) {
            AttentionStatus::firstOrCreate(['name' => $as['name']], $as);
        }
    }
}

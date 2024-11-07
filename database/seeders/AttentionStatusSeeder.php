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
            ["name" => 'Atendido', "color" => "#56BF2F"],
            ["name" => 'En espera', "color" => "#FF2C2C"],
            ["name" => 'En consulta', "color" => "#FFBE33"],
        ];

        foreach ($attentionStatuses as $as) {
            AttentionStatus::Create($as);
        }
    }
}

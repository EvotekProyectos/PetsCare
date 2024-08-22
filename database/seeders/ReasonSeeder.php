<?php

namespace Database\Seeders;

use App\Models\Reason;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReasonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $reasons = [
            ['name' => 'Revisión'],
            ['name' => 'Básica'],
            ['name' => 'Especialidad'],
        ];

        foreach ($reasons as $reason){
            Reason::create($reason);
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\Cubicle;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use PhpParser\Node\Stmt\Foreach_;

class CubiclesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       $cubicles=[
        ['name' => '01', 'cubicle_type_id'=>'1', 'state'=>'0'],
        ['name' => '02', 'cubicle_type_id'=>'1', 'state'=>'0'],
        ['name' => '03', 'cubicle_type_id'=>'2', 'state'=>'0'],
        ['name' => '04', 'cubicle_type_id'=>'2', 'state'=>'0'],
        ['name' => '05', 'cubicle_type_id'=>'3', 'state'=>'0'],
        ['name' => '06', 'cubicle_type_id'=>'3', 'state'=>'0'],
       ];

       foreach($cubicles as $cubicle){
        Cubicle::create($cubicle);
       }
    }
}

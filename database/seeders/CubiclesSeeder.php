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
        //PENSION PISO C ->chicas
        ['name' => 'C1',  'length'=>'1.50' , 'width'=>'1.00','cubicle_type_id'=>'1', 'state'=>'0'],
        ['name' => 'C2',  'length'=>'1.50' , 'width'=>'1.00','cubicle_type_id'=>'1', 'state'=>'0'],
        ['name' => 'C3',  'length'=>'1.50' , 'width'=>'1.00','cubicle_type_id'=>'1', 'state'=>'0'],
        ['name' => 'C4',  'length'=>'1.50' , 'width'=>'1.00','cubicle_type_id'=>'1', 'state'=>'0'],
        ['name' => 'C5',  'length'=>'1.50' , 'width'=>'1.00','cubicle_type_id'=>'1', 'state'=>'0'],
        ['name' => 'C6',  'length'=>'1.50' , 'width'=>'1.00','cubicle_type_id'=>'1', 'state'=>'0'],
        ['name' => 'C7',  'length'=>'1.50' , 'width'=>'1.00','cubicle_type_id'=>'1', 'state'=>'0'],
        ['name' => 'C8',  'length'=>'1.50' , 'width'=>'1.00','cubicle_type_id'=>'1', 'state'=>'0'],
        ['name' => 'C9',  'length'=>'1.50' , 'width'=>'1.00','cubicle_type_id'=>'1', 'state'=>'0'],
        ['name' => 'C10', 'length'=>'1.50' , 'width'=>'1.00','cubicle_type_id'=>'1', 'state'=>'0'],
        ['name' => 'C11', 'length'=>'1.50' , 'width'=>'1.00','cubicle_type_id'=>'1', 'state'=>'0'],
        ['name' => 'C12', 'length'=>'1.50' , 'width'=>'1.00','cubicle_type_id'=>'1', 'state'=>'0'],
        ['name' => 'C13', 'length'=>'1.50' , 'width'=>'1.00','cubicle_type_id'=>'1', 'state'=>'1'],
        ['name' => 'C14', 'length'=>'1.50' , 'width'=>'1.00','cubicle_type_id'=>'1', 'state'=>'0'],
        ['name' => 'C15', 'length'=>'1.50' , 'width'=>'1.00','cubicle_type_id'=>'1', 'state'=>'0'],
        ['name' => 'C16', 'length'=>'1.50' , 'width'=>'1.00','cubicle_type_id'=>'1', 'state'=>'0'],
        ['name' => 'C17', 'length'=>'1.50' , 'width'=>'1.00','cubicle_type_id'=>'1', 'state'=>'0'],

        //PENSION PISO B ->medianas
        ['name' => 'B1', 'length'=>'1.45' , 'width'=>'1.10','cubicle_type_id'=>'2', 'state'=>'0'],
        ['name' => 'B2', 'length'=>'1.45' , 'width'=>'1.10','cubicle_type_id'=>'2', 'state'=>'0'],
        ['name' => 'B3', 'length'=>'1.45' , 'width'=>'1.10','cubicle_type_id'=>'2', 'state'=>'0'],
        ['name' => 'B4', 'length'=>'1.45' , 'width'=>'1.10','cubicle_type_id'=>'2', 'state'=>'0'],
        ['name' => 'B5', 'length'=>'1.45' , 'width'=>'1.10','cubicle_type_id'=>'2', 'state'=>'0'],
        ['name' => 'B6', 'length'=>'1.45' , 'width'=>'1.10','cubicle_type_id'=>'2', 'state'=>'0'],
        ['name' => 'B7', 'length'=>'1.45' , 'width'=>'1.10','cubicle_type_id'=>'2', 'state'=>'0'],
        ['name' => 'B8', 'length'=>'1.45' , 'width'=>'1.10','cubicle_type_id'=>'2', 'state'=>'0'],
        ['name' => 'B9', 'length'=>'1.45' , 'width'=>'1.10','cubicle_type_id'=>'2', 'state'=>'0'],
        ['name' => 'B10', 'length'=>'1.45' , 'width'=>'1.10','cubicle_type_id'=>'2', 'state'=>'0'],
        ['name' => 'B11', 'length'=>'1.45' , 'width'=>'1.10','cubicle_type_id'=>'2', 'state'=>'0'],
        ['name' => 'B12', 'length'=>'1.45' , 'width'=>'1.10','cubicle_type_id'=>'2', 'state'=>'0'],
        ['name' => 'B13', 'length'=>'1.45' , 'width'=>'1.10','cubicle_type_id'=>'2', 'state'=>'0'],
        ['name' => 'B14', 'length'=>'1.45' , 'width'=>'1.10','cubicle_type_id'=>'2', 'state'=>'0'],
        ['name' => 'B15', 'length'=>'1.45' , 'width'=>'1.10','cubicle_type_id'=>'2', 'state'=>'0'],
        ['name' => 'B16', 'length'=>'1.45' , 'width'=>'1.10','cubicle_type_id'=>'2', 'state'=>'0'],
        ['name' => 'B17', 'length'=>'1.45' , 'width'=>'1.10','cubicle_type_id'=>'2', 'state'=>'0'],

        //PENSION PISO A -> suite
        ['name' => 'A1',  'length'=>'1.50' , 'width'=>'1.00','cubicle_type_id'=>'3', 'state'=>'0'],
        ['name' => 'A2',  'length'=>'1.50' , 'width'=>'1.00','cubicle_type_id'=>'3', 'state'=>'0'],
        ['name' => 'A3',  'length'=>'1.50' , 'width'=>'1.00','cubicle_type_id'=>'3', 'state'=>'0'],
        ['name' => 'A4',  'length'=>'1.50' , 'width'=>'1.00','cubicle_type_id'=>'3', 'state'=>'0'],
        ['name' => 'A5',  'length'=>'1.50' , 'width'=>'1.00','cubicle_type_id'=>'3', 'state'=>'0'],
        ['name' => 'A6',  'length'=>'1.50' , 'width'=>'1.00','cubicle_type_id'=>'3', 'state'=>'0'],
        ['name' => 'A7',  'length'=>'1.50' , 'width'=>'1.00','cubicle_type_id'=>'3', 'state'=>'0'],
        ['name' => 'A8',  'length'=>'1.50' , 'width'=>'1.00','cubicle_type_id'=>'3', 'state'=>'0'],
        ['name' => 'A9',  'length'=>'2.04' , 'width'=>'2.12','cubicle_type_id'=>'3', 'state'=>'0'],
        ['name' => 'A10',  'length'=>'2.10' , 'width'=>'1.72','cubicle_type_id'=>'3', 'state'=>'0'],
        ['name' => 'A11',  'length'=>'2.10' , 'width'=>'1.72','cubicle_type_id'=>'3', 'state'=>'0'],
        ['name' => 'A12',  'length'=>'3.25' , 'width'=>'2.23','cubicle_type_id'=>'3', 'state'=>'0'],
        ['name' => 'A13',  'length'=>'1.48' , 'width'=>'1.35','cubicle_type_id'=>'3', 'state'=>'0'],
        ['name' => 'A14',  'length'=>'2.30' , 'width'=>'1.35','cubicle_type_id'=>'3', 'state'=>'0'],
        ['name' => 'A15',  'length'=>'2.30' , 'width'=>'1.35','cubicle_type_id'=>'3', 'state'=>'0'],
        ['name' => 'A16',  'length'=>'2.26' , 'width'=>'1.35','cubicle_type_id'=>'3', 'state'=>'0'],

        //PENSION GATOS
        ['name' => 'G1',  'length'=>'0.90' , 'width'=>'0.70','cubicle_type_id'=>'4', 'state'=>'0'],
        ['name' => 'G2',  'length'=>'0.90' , 'width'=>'0.70','cubicle_type_id'=>'4', 'state'=>'0'],
        ['name' => 'G3',  'length'=>'0.96' , 'width'=>'0.88','cubicle_type_id'=>'4', 'state'=>'0'],
        ['name' => 'G4',  'length'=>'0.90' , 'width'=>'0.85','cubicle_type_id'=>'4', 'state'=>'0'],
        ['name' => 'G5',  'length'=>'0.90' , 'width'=>'0.85','cubicle_type_id'=>'4', 'state'=>'0'],
        ['name' => 'G6',  'length'=>'0.90' , 'width'=>'0.75','cubicle_type_id'=>'4', 'state'=>'0'],
        ['name' => 'G7',  'length'=>'0.90' , 'width'=>'0.85','cubicle_type_id'=>'4', 'state'=>'0'],
        ['name' => 'G8',  'length'=>'0.90' , 'width'=>'0.76','cubicle_type_id'=>'4', 'state'=>'0'],
        ['name' => 'G9',  'length'=>'0.90' , 'width'=>'0.85','cubicle_type_id'=>'4', 'state'=>'0'],
        ['name' => 'G10',  'length'=>'0.89' , 'width'=>'1.35','cubicle_type_id'=>'4', 'state'=>'0'],
        ['name' => 'G11',  'length'=>'0.90' , 'width'=>'0.80','cubicle_type_id'=>'4', 'state'=>'0'],

        //PENSION EXPO ->chicas
        ['name' => 'D1',  'length'=>'0.84' , 'width'=>'1.10','cubicle_type_id'=>'1', 'state'=>'0'],
        ['name' => 'D2',  'length'=>'0.84' , 'width'=>'1.10','cubicle_type_id'=>'1', 'state'=>'0'],
        ['name' => 'D3',  'length'=>'0.84' , 'width'=>'1.10','cubicle_type_id'=>'1', 'state'=>'0'],
        ['name' => 'D4',  'length'=>'0.64' , 'width'=>'0.56','cubicle_type_id'=>'1', 'state'=>'0'],
        ['name' => 'D5',  'length'=>'0.64' , 'width'=>'0.56','cubicle_type_id'=>'1', 'state'=>'0'],
        ['name' => 'D6',  'length'=>'0.64' , 'width'=>'0.56','cubicle_type_id'=>'1', 'state'=>'0'],
        ['name' => 'D7',  'length'=>'0.64' , 'width'=>'0.56','cubicle_type_id'=>'1', 'state'=>'0'],
        ['name' => 'D8',  'length'=>'0.84' , 'width'=>'1.10','cubicle_type_id'=>'1', 'state'=>'0'],
        ['name' => 'D9',  'length'=>'0.84' , 'width'=>'1.10','cubicle_type_id'=>'1', 'state'=>'0'],
        ['name' => 'D10',  'length'=>'0.84' , 'width'=>'1.10','cubicle_type_id'=>'1', 'state'=>'0'],
        ['name' => 'D11',  'length'=>'0.64' , 'width'=>'0.56','cubicle_type_id'=>'1', 'state'=>'0'],
        ['name' => 'D12',  'length'=>'0.64' , 'width'=>'0.56','cubicle_type_id'=>'1', 'state'=>'0'],
        ['name' => 'D13',  'length'=>'0.64' , 'width'=>'0.56','cubicle_type_id'=>'1', 'state'=>'0'],
        ['name' => 'D14',  'length'=>'0.64' , 'width'=>'0.56','cubicle_type_id'=>'1', 'state'=>'0'],
        ['name' => 'D15',  'length'=>'0.84' , 'width'=>'1.10','cubicle_type_id'=>'1', 'state'=>'0'],
        ['name' => 'D16',  'length'=>'0.84' , 'width'=>'1.10','cubicle_type_id'=>'1', 'state'=>'0'],
        ['name' => 'D17',  'length'=>'0.84' , 'width'=>'1.10','cubicle_type_id'=>'1', 'state'=>'0'],
        ['name' => 'D18',  'length'=>'0.64' , 'width'=>'0.56','cubicle_type_id'=>'1', 'state'=>'0'],
        ['name' => 'D19',  'length'=>'0.64' , 'width'=>'0.56','cubicle_type_id'=>'1', 'state'=>'0'],
        ['name' => 'D20',  'length'=>'0.64' , 'width'=>'0.56','cubicle_type_id'=>'1', 'state'=>'0'],
        ['name' => 'D21',  'length'=>'0.64' , 'width'=>'0.56','cubicle_type_id'=>'1', 'state'=>'0'],
        

      
       ];

       foreach($cubicles as $cubicle){
        Cubicle::create($cubicle);
       }
    }
}

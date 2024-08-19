<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Patricia Briones',
            'email' => 'patricia.briones@evotek.com.mx',
            'password' => Hash::make('8442036052'),
        ]);

        User::create([
            'name' => 'Admin',
            'email' => 'alejandra.alamilla@evotek.com.mx',
            'password' => Hash::make('8442477138')]);
    }
}

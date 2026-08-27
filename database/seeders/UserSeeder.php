<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $user = User::create([
        //     'name' => 'Patricia Briones',
        //     'email' => 'patricia.briones@evotek.com.mx',
        //     'password' => Hash::make('8442036052'),
        //     'remember_token' => 'sqd4HLT3nolNw9JUSz8Hh9P9hYZBLHG9xi3buifOPKUgPSJj3jDajoch9olN',
        // ])->assignRole('administrador');

        // $user =  User::create([
        //     'name' => 'Ale Alamilla',
        //     'email' => 'alejandra.alamilla@evotek.com.mx',
        //     'password' => Hash::make('8442477138')
        // ])->assignRole('administrador');

        $user =  User::create([
            'name' => 'Estrella Lopez',
            'email' => 'estrella.armendariz@evotek.com.mx',
            'password' => Hash::make('8443922106')
        ])->assignRole('administrador');

        $user =  User::create([
            'name' => 'Recepcionista Alondra',
            'email' => 'alondra@petscare.com',
            'password' => Hash::make('12345678')
        ])->assignRole('recepcionista');

        $user =  User::create([
            'name' => 'Medico Humberto',
            'email' => 'humberto@petscare.com',
            'password' => Hash::make('12345678')
        ])->assignRole('medico');

        $user =  User::create([
            'name' => 'Colaborador Ernesto',
            'email' => 'ernesto@petscare.com',
            'password' => Hash::make('12345678')
        ])->assignRole('colaborador');

        $user =  User::create([
            'name' => 'Almacenista Antonio',
            'email' => 'antonio@petscare.com',
            'password' => Hash::make('12345678')
        ])->assignRole('almacenista');
    }
}

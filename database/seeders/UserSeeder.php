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
        $user = User::create([
            'name' => 'Patricia Briones',
            'email' => 'patricia.briones@evotek.com.mx',
            'password' => Hash::make('8442036052'),
        ])->assignRole('admin');

        $user->givePermissionTo(Permission::all());

        $user =  User::create([
            'name' => 'Ale Alamilla',
            'email' => 'alejandra.alamilla@evotek.com.mx',
            'password' => Hash::make('8442477138')
        ])->assignRole('admin');

        $user->givePermissionTo(Permission::all());
    }
}

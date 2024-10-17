<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        $this->call(RolesAndPermissionsSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(ReasonSeeder::class);
        $this->call(AreaSeeder::class);
        $this->call(AttentionStatusSeeder::class);
        $this->call(ReceptionTypeSeeder::class);
        $this->call(RoomSeeder::class);
        $this->call(GenreSeeder::class);
        $this->call(ReproductiveStatusSeeder::class);
        $this->call(AdmissionTypeSeeder::class);
        $this->call(FamClassificationSeeder::class);
        $this->call(PetClassificationSeeder::class);
        $this->call(ShiftSeeder::class);
        $this->call(PetStatusesSeeder::class);
        $this->call(FamiliesSeeder::class);
        $this->call(PetSeeder::class);
        $this->call(CoverAreaSeeder::class);
        $this->call(ServiceSeeder::class);
    }
}

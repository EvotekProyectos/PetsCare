<?php

namespace Database\Factories;

use App\Models\Reception;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AppointmentService>
 */
class AppointmentServiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'reception_id' => Reception::factory(), // crea recepcion random
            'lab_type_id' => rand(0,1) ? $this->faker->numberBetween(1, 1000) : null, // relacion opcional con id de lab de microsip
            'imaging_type_id' => rand(0,1) ? $this->faker->numberBetween(1, 1000) : null, // relacion opcional con id de imagen de microsip
            'observations' => rand(0,1) ? $this->faker->sentence() : null, // crea observaciones opcionales
            'vet_id' => User::factory(), // Crea un usuario veterinario
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\Reception;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RedSheet>
 */
class RedSheetFactory extends Factory
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
            'service_type_id' => rand(0,1) ? $this->faker->numberBetween(1, 1000) : null, // relacion opcional con id de servicios de microsip
            'imaging_type_id' => rand(0,1) ? $this->faker->numberBetween(1, 1000) : null, // relacion opcional con id de imagenes de microsip
            'lab_type_id' => rand(0,1) ? $this->faker->numberBetween(1, 1000) : null, // relacion opcional con id de lab de microsip
            'observations' => rand(0,1) ? $this->faker->sentence() : null, // Genera una oracion aleatoria
			'day_count' => $this->faker->numberBetween(1, 75), // genera un numero aleatorio
            'vet_id' => User::factory(), // usuario random
        ];
    }
}

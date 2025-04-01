<?php

namespace Database\Factories;

use App\Models\Reception;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Grooming>
 */
class GroomingFactory extends Factory
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
			'service_id' => $this->faker->numberBetween(1, 1000), // relacion  con id de servicios de microsip,
			'notes' => rand(0,1) ? $this->faker->sentence() : null, //oracion aleatoria
        ];
    }
}

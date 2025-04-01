<?php

namespace Database\Factories;

use App\Models\Pet;
use App\Models\Reception;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Hospitalization>
 */
class HospitalizationDischargeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
           'pet_id' => Pet::factory(), // Crea una mascota aleatoria
            'reception_id' => Reception::factory(), // crea recepcion random
			'reason' => rand(0,1) ? $this->faker->word() : null, //oracion aleatoria
            'total_days' => rand(0,1) ? $this->faker->numberBetween(1, 39) : null, //numeros aleatoria
            'total_payment' => rand(0,1) ? $this->faker->numberBetween(100, 10000) : null, //numeros aleatoria
            'already_paid' => rand(0,1) ? $this->faker->numberBetween(0, 9) : null, //numeros aleatoria
            'exit_date' => rand(0,1) ? $this->faker->dateTime() : null, //fecha aleatoria
        ];
    }
}

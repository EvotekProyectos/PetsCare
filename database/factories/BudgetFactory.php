<?php

namespace Database\Factories;

use App\Models\Pet;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Budget>
 */
class BudgetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
           'pet_id' => Pet::factory(), //crea una mascota aleatoria
			'date' => $this->faker->date(), // fecha aleatoria
			'total' => $this->faker->numberBetween(1, 1000), // genera un numero aleatorio
            'others' => $this->faker->sentence(), // Genera una oracion aleatoria
            'vet_id' => User::factory(), // usuario random
            'reception_id' => User::factory(), // usuario random
        ];
    }
}

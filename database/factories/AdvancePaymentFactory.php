<?php

namespace Database\Factories;

use App\Models\Reception;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AdvancePayment>
 */
class AdvancePaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'reception_id' => Reception::factory(), // recepcion random
            'reference' => $this->faker->word, // Genera una palabra aleatoria
            'concept' => $this->faker->sentence(), // Genera una palabra aleatoria
            'date' => $this->faker->date, // genera una fecha aleatoria
            'amount' => $this->faker->numberBetween(1, 1000), // genera un numero aleatorio
            'user_id' => User::factory(), // usuario random
            'status' => $this->faker->boolean(), // status aleatorio
        ];
    }
}

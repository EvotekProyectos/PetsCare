<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PetsStatus>
 */
class PetsStatusFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word, // Genera un nombre aleatorio
            'description' => $this->faker->sentence(), //oracion aleatoria
            'color' => $this->faker->hexColor(), // Genera un color aleatorio
        ];
    }
}

<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CubicleType>
 */
class CubicleTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word(), //nombre aleatorio
            'id_microsip'=> $this->faker->numberBetween(1, 1000), // relacion con id de servicios de microsip
        ];
    }
}

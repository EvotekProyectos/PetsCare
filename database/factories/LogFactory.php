<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Log>
 */
class LogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'action' =>  $this->faker->sentence() , //oracion aleatoria
			'description' => $this->faker->sentence(), //oracion aleatoria
            'user_id' => User::factory(), // usuario random
        ];
    }
}

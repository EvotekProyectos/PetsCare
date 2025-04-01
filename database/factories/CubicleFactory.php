<?php

namespace Database\Factories;

use App\Models\CubicleType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cubicle>
 */
class CubicleFactory extends Factory
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
            'cubicle_type_id'=> CubicleType::factory(), //genera tipo cubiculo aleatorio
			'state' => $this->faker->boolean(), // status aleatorio
        ];
    }
}

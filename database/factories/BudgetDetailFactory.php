<?php

namespace Database\Factories;

use App\Models\Budget;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BudgetDetail>
 */
class BudgetDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
           'budget_id' => Budget::factory(), // genera presupuesto aletorio
			'service_id' => rand(0,1) ? $this->faker->numberBetween(1, 1000) : null, // relacion opcional con id de servicios de microsip
            'img_id' => rand(0,1) ? $this->faker->numberBetween(1, 1000) : null, // relacion opcional con id de imagenes de microsip
            'lab_id' => rand(0,1) ? $this->faker->numberBetween(1, 1000) : null, // relacion opcional con id de lab de microsip
			'price' => $this->faker->numberBetween(1, 1000), // genera un numero aleatorio
			'notes' => rand(0,1) ? $this->faker->sentence() : null, // genera oracion de notas opcional
        ];
    }
}

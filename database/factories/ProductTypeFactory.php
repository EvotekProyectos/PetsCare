<?php

namespace Database\Factories;

use App\Models\ProductClassification;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductType>
 */
class ProductTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_classification_id' => ProductClassification::factory(), //genera clasificaicon random
			'microsip_id' => $this->faker->numberBetween(1, 1000), // relacion con id de servicios de microsip
			'name' =>  $this->faker->word() , //palabra aleatoria
			'price' => $this->faker->numberBetween(1, 1000), // genera un numero aleatorio
        ];
    }
}

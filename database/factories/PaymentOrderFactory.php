<?php

namespace Database\Factories;

use App\Models\Reception;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PaymentOrder>
 */
class PaymentOrderFactory extends Factory
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
			'folio_odv' => $this->faker->sentence(), //oracion aleatoria
        ];
    }
}

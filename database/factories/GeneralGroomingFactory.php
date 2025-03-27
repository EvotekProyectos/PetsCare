<?php

namespace Database\Factories;

use App\Models\Reception;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\GeneralGrooming>
 */
class GeneralGroomingFactory extends Factory
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
            'instructions' => rand(0,1) ? $this->faker->sentence() : null, //oracion aleatoria
			'next_service' => $this->faker->dateTimeBetween('now', '+4 week'), //fecha aleatoria
			'critic_status' => $this->faker->boolean(), // status aleatorio
			'delivery_service' => $this->faker->boolean(), // status aleatorio
            'delivery_references' => rand(0,1) ? $this->faker->sentence() : null, //oracion aleatoria
			'folio' => $this->faker->numberBetween(0,200), // generanumero aleatorio para folio
        ];
    }
}

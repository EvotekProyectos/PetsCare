<?php

namespace Database\Factories;

use App\Models\Reception;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Hotel>
 */
class HotelFactory extends Factory
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
			'food' => $this->faker->sentence(), // oracion aleatoria
			'objects' => $this->faker->sentence(), // oracion aleatoria
			'observations' => $this->faker->sentence(), // oracion aleatoria
			'number_days' => $this->faker->numberBetween(1, 30), // genera un numero aleatorio
            'extension' => $this->faker->boolean(), // status aleatorio
            'service_type_id' =>  $this->faker->numberBetween(1, 1000), // relacion con id de servicios de microsip
            'finish_date' => $this->faker->dateTimeBetween('now', '+1 week'), // fecha aleatoria futura
            'video' => $this->faker->boolean(), // status aleatorio
            'status' => $this->faker->boolean(), // status aleatorio
            'cubicle_id' => 'nullable|integer|exists:cubicles,id',
        ];
    }
}

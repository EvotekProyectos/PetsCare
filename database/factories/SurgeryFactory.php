<?php

namespace Database\Factories;

use App\Models\Reception;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Surgery>
 */
class SurgeryFactory extends Factory
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
            'product_type_id' => $this->faker->numberBetween(1, 1000), // relacion  con id de servicios de microsip,
            'date'=> $this->faker->dateTime(), //genera fecha random
            'observations'=> rand(0,1) ? $this->faker->sentence() : null, // Genera una oracion aleatoria
            'vet_id'=> User::factory(), //genera usuario random
        ];
    }
}

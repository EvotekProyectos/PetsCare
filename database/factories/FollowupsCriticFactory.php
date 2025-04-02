<?php

namespace Database\Factories;

use App\Models\Reception;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FollowupsCritic>
 */
class FollowupsCriticFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'reception_id' => Reception::factory(), // genera recepcion random
			'pet_status' => rand(0,1) ? $this->faker->sentence() : null, //oracion aleatoria
			'preasure' => rand(0,1) ? $this->faker->numberBetween(29, 131) : null, //numeros aleatoria
			'temperature' => rand(0,1) ? $this->faker->numberBetween(37, 39) : null, //numeros aleatoria
			'glycemia' => rand(0,1) ? $this->faker->numberBetween(62, 120) : null, //numeros aleatoria
            'throwup' => $this->faker->boolean(), // status aleatorio
			'throwup_detail' => rand(0,1) ? $this->faker->sentence() : null, //oracion aleatoria
            'defecate' => $this->faker->boolean(), // status aleatorio
			'defecate_detail' => rand(0,1) ? $this->faker->sentence() : null, //oracion aleatoria
            'orino' => $this->faker->boolean(), // status aleatorio
			'orino_detail' => rand(0,1) ? $this->faker->sentence() : null, //oracion aleatoria
            'eat' => $this->faker->boolean(), // status aleatorio
			'eat_detail' => rand(0,1) ? $this->faker->sentence() : null, //oracion aleatoria
            'infusions' => $this->faker->boolean(), // status aleatorio
			'infusions_detail' => rand(0,1) ? $this->faker->sentence() : null, //oracion aleatoria
            'terapeutic' => $this->faker->boolean(), // status aleatorio
			'terapeutic_detail' => rand(0,1) ? $this->faker->sentence() : null, //oracion aleatoria
            'imaging' => $this->faker->boolean(), // status aleatorio
			'imaging_detail' => rand(0,1) ? $this->faker->sentence() : null, //oracion aleatoria
			'pends' => rand(0,1) ? $this->faker->sentence() : null, //oracion aleatoria
            'vet_id' => User::factory(), // Crea un usuario veterinario
        ];
    }
}

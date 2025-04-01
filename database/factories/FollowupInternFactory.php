<?php

namespace Database\Factories;

use App\Models\Reception;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FollowupIntern>
 */
class FollowupInternFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'reception_id' =>   Reception::factory(), // genera recepcion random
			'date' => $this->faker->dateTime(), //fecha  aleatoria
            'alterations'=> $this->faker->boolean(), // status aleatorio
			'which_alterations' => rand(0,1) ? $this->faker->sentence() : null, //oracion aleatoria
            'therapeutic'=> $this->faker->boolean(), // status aleatorio
			'which_therapeutic' => rand(0,1) ? $this->faker->sentence() : null, //oracion aleatoria
            'vomiting'=> $this->faker->boolean(), // status aleatorio
			'quantity_vomiting' => rand(0,1) ? $this->faker->sentence() : null, //oracion aleatoria
            'defecation'=> $this->faker->boolean(), // status aleatorio
			'quantity_defecation' => rand(0,1) ? $this->faker->sentence() : null, //oracion aleatoria
            'urine'=> $this->faker->boolean(), // status aleatorio
			'quantity_urine' => rand(0,1) ? $this->faker->sentence() : null, //oracion aleatoria
            'feeding'=> $this->faker->boolean(), // status aleatorio
			'type_feeding' => rand(0,1) ? $this->faker->sentence() : null, //oracion aleatoria
			'pendings' => rand(0,1) ? $this->faker->sentence() : null, //oracion aleatoria
            'ultrasounds'=> $this->faker->boolean(), // status aleatorio
			'observations_ultrasounds' => rand(0,1) ? $this->faker->sentence() : null, //oracion aleatoria
			'observations' => rand(0,1) ? $this->faker->sentence() : null, //oracion aleatoria
            'vet_id' => User::factory(), // Crea un usuario veterinario
        ];
    }
}

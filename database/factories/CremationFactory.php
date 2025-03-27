<?php

namespace Database\Factories;

use App\Models\CmType;
use App\Models\Pet;
use App\Models\Reception;
use App\Models\TagType;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cremation>
 */
class CremationFactory extends Factory
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
            'pet_id' => Pet::factory(), // Crea una mascota aleatoria
            'date_death'=> $this->faker->dateTime, // fecha aleatoria
            'date_finish'=> $this->faker->dateTimeBetween('now', '+1 week'), //fecha futura aleatoria
			'servicie' => rand(0,1) ? $this->faker->numberBetween(1, 1000) : null, // relacion opcional con id de servicios de microsip
			'CM_id' => CmType::factory(), //crea CM aleatorio
			'type_urn' => $this->faker->word(), //nombre aleatorio
            'vet_id' => User::factory(), // Crea un usuario veterinario
			'urn_model' => $this->faker->word(), //nombre aleatorio
            'text_placa' => rand(0,1) ? $this->faker->word() : null, //palabra aleatoria
			'observations' => rand(0,1) ? $this->faker->sentence() : null, //oracion aleatoria
			'placa_type_id' => TagType::factory(), //crea tipo de placa aleatoria
            'status' => $this->faker->word(), //palabra aleatoria
			'price' => $this->faker->numberBetween(1, 1000), // genera un numero aleatorio
        ];
    }
}

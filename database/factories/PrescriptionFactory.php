<?php

namespace Database\Factories;

use App\Models\Pet;
use App\Models\Reason;
use App\Models\Reception;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Prescription>
 */
class PrescriptionFactory extends Factory
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
            'veterinarian_id' => User::factory(), // Crea un usuario veterinario
            'recepcionist_id' => User::factory(), // Crea un usuario recepcionits
            'pet_id' => Pet::factory(), // Crea una mascota aleatoria
			'date' => $this->faker->dateTime(), //genera fecha random
			'medicine' => $this->faker->sentence(), //oracion aleatoria
			'diagnosis' => $this->faker->sentence(), //oracion aleatoria
			'observations' => $this->faker->sentence(), //oracion aleatoria
            'day_next_check' => $this->faker->date(), //fecha aleaotia 
            'time_next_check' => rand(0,1) ?  $this->faker->time() : null, //hora aleatoeria
            'reason_next_check_id' => Reason::factory(), //geenra razon random
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\Family;
use App\Models\Pet;
use App\Models\Reception;
use App\Models\StatusSurgery;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SurgerySchedule>
 */
class SurgeryScheduleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'family_id' => Family::factory(), // crea una familia 
            'pet_id' => Pet::factory(), // Crea una mascota aleatoria
            'reception_id' => Reception::factory(), // crea recepcion random
            'surgical_procedures_type_id' => $this->faker->numberBetween(1, 1000), // relacion con id de servicios de microsip
			'day' => $this->faker->dateTimeBetween('-7 days', '+15 days')->format('Y-m-d'), //fecha aleaotria
			'hour' => $this->faker->time(), // Genera una hora aleatorio
            'number_ticket'=> $this->faker->numberBetween(1, 1000), // numero random
            'veterinarian_id' => User::factory(), // genera usuario random
            'status_surgery_id' => StatusSurgery::factory(), // genera status de cirgua random
        ];
    }
}

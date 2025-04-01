<?php

namespace Database\Factories;

use App\Models\DateType;
use App\Models\Family;
use App\Models\Pet;
use App\Models\Reception;
use App\Models\StatusDate;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ControlDate>
 */
class ControlDateFactory extends Factory
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
            'family_id' => Family::factory(), // crea una familia 
            'pet_id' => Pet::factory(), // Crea una mascota aleatoria
            'date_type_id' => DateType::factory(), // crea tipo de cita aleatorio
            'status_type_id' => StatusDate::factory(), //crea status de cita aleatorio
			'date' => $this->faker->dateTimeBetween('now', '+1 week'), //genera fecha aletoria
            'user_id' => User::factory(), // Crea un usuario random
        ];
    }
}

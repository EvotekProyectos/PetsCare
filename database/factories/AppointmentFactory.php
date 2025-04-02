<?php

namespace Database\Factories;

use App\Models\Reason;
use App\Models\Reception;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Appointment>
 */
class AppointmentFactory extends Factory
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
			'anamnesis' => $this->faker->sentence(), // oracion aleatoria
			'exam_details' => $this->faker->sentence(),  // oracion aleatoria
			'diagnosis' => $this->faker->sentence(), // oracion aleatoria
			'observations' => $this->faker->sentence(), // oracion aleatoria
			'day_next_check' => $this->faker->dateTimeBetween('now', '+3 week'), //fecha futura aleatoria
            'time_next_check' => $this->faker->time, //hora random
            'reason_next_check_id' => rand(0,1) ? Reason::factory() : null, // Relacion a las razones de ingreso opcional
        ];
    }
}

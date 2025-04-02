<?php

namespace Database\Factories;

use App\Models\AdmissionType;
use App\Models\Area;
use App\Models\Family;
use App\Models\Pet;
use App\Models\Reason;
use App\Models\ReceptionType;
use App\Models\Room;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reception>
 */
class ReceptionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    { 
        return [
            'pet_id' => Pet::factory(), // Crea una mascota aleatoria
            'reception_type_id' =>   ReceptionType::factory(), // Crea un tipo de recepción
            'admission_type_id' => rand(0,1) ? AdmissionType::factory() : null, // Relation de tipo de admision opcional
            'area_id' => rand(0,1) ? Area::factory() : null, // Relacion con las areas opcional
            'family_id' => Family::factory(), // crea una familia 
            'reason_id' => rand(0,1) ? Reason::factory() : null, // Relacion a las razones de ingreso opcional
            'veterinarian_id' => User::factory(), // Crea un usuario veterinario
            'recepcionist_id' => User::factory(), // Crea un usuario recepcionista
            'room_id' => rand(0,1) ? Room::factory() : null, // Relacion con consultorio opcional
            'entry_date' => $this->faker->dateTimeBetween('-1 week', 'now'), //fecha de ingreso aleatoria
            'exit_date' => rand(0,1) ? $this->faker->dateTimeBetween('now', '+1 week') : null, //fecha de salida aleatroia y opcional
            'num' => rand(0,1) ? $this->faker->numberBetween(1, 100) : null, // numero de collar/arete de ingreso opcional
        ];
    }
}

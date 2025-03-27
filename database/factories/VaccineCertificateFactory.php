<?php

namespace Database\Factories;

use App\Models\Pet;
use App\Models\Reception;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\VaccineCertificate>
 */
class VaccineCertificateFactory extends Factory
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
            'service_id' => Service::factory(), //genera servicio aleatorio
            'product' => $this->faker->numberBetween(1, 1000), // relacion con id de servicios de microsip
            'lab' => rand(0,1) ? $this->faker->sentence() : null, //oracion aleatoria
            'lote' => rand(0,1) ? $this->faker->sentence() : null, //oracion aleatoria
            'dose' => rand(0,1) ? $this->faker->sentence() : null, //oracion aleatoria
            'application_date' =>$this->faker->dateTimeBetween('now', '+1 days')->format('Y-m-d'), //fecha aleaotria
            'last_deworming_date' => $this->faker->dateTimeBetween('-45 days', '-7 days')->format('Y-m-d'), //fecha aleaotria,
            'next_application_date' => $this->faker->dateTimeBetween('+7 days', '+300 days')->format('Y-m-d'), //fecha aleaotria
            'observations' => rand(0,1) ? $this->faker->sentence() : null, //oracion aleatoria
            'reception_id' => rand(0,1) ?  Reception::factory() :  null, //genera recepciton random opcionalmente
            'vet_id' => User::factory(), //genera usuario random
        ];
    }
}

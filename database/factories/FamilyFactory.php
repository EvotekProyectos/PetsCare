<?php

namespace Database\Factories;

use App\Models\FamClassification;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Family>
 */
class FamilyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->lastName, // Nombre ficticio de familia
            'phone' => $this->faker->phoneNumber, // Número de teléfono ficticio
            'email' => $this->faker->unique()->safeEmail, // Correo aleatorio
            'address' => $this->faker->address, // Dirección ficticia
            'contact_name' => $this->faker->name, // Nombre de contacto ficticio
            'contact_number' => $this->faker->phoneNumber, // Teléfono de contacto ficticio
            'fam_classification_id' => rand(0,1) ? FamClassification::factory() : null , // Relacionado con FamClassification (opcional)
        ];
    }
}

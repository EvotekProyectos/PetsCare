<?php

namespace Database\Factories;

use App\Models\Family;
use App\Models\Genre;
use App\Models\PetClassification;
use App\Models\ReproductiveStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pet>
 */
class PetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'family_id' => Family::factory(), // Relacionado con Family
            'name' => $this->faker->word, // Nombre de mascota
            'number_chip' => $this->faker->word, // Número de chip aleatorio
            'picture_id' => null, // De momento no genera una imagen, si se necesita, se puede enlazar con una Factory de File
            'specie' => $this->faker->word, // Especie (ej. Perro, Gato)
            'raza' => $this->faker->word, // Raza de la mascota
            'gender_id' => Genre::factory(), // Relacionado con Gender
            'birthday' => $this->faker->date(), // Fecha de nacimiento
            'reproductive_status_id' => ReproductiveStatus::factory(), // Relacionado con ReproductiveStatus
            'weight' => $this->faker->randomFloat(2, 1, 20), // Peso aleatorio de la mascota
            'physic_descrip' => $this->faker->sentence, // Descripción física
            'notes' => $this->faker->sentence, // Notas adicionales
            'pet_classification_id' => rand(0, 1) ? PetClassification::factory() : null, // Relacionado con PetClassification (opcional)
            'deceased' => $this->faker->boolean(), // Estado si está muerto o no
        ];
    }
}

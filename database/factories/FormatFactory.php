<?php

namespace Database\Factories;

use App\Models\FormatType;
use App\Models\Pet;
use App\Models\Reception;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Format>
 */
class FormatFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'format_type_id' => FormatType::factory(), //genera tipo de formato aleatorio
			'reception_id' => Reception::factory(), // crea recepcion random
            'pet_id' => Pet::factory(), // Crea una mascota aleatoria
            'format_pdf' => "/storage/receptions/" . $this->faker->unique()->md5() . "_" . time() . ".pdf", //simulacion de path de un pdf a guaradr
        ];
    }
}

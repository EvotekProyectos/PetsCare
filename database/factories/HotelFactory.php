<?php

namespace Database\Factories;

use App\Models\Cubicle;
use App\Models\Hotel;
use App\Models\Reception;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Hotel>
 */

class HotelFactory extends Factory
{
    protected $model = Hotel::class;

    public function definition()
    {
        return [
            'reception_id' => Reception::factory(),
            'food' => $this->faker->sentence(),
            'objects' => $this->faker->sentence(),
            'observations' => $this->faker->sentence(),
            'number_days' => $this->faker->numberBetween(1, 10),
            'extension' => 0,
            'service_type_id' => 1, // Asegúrate que exista el ID 1 en service_types o ajusta según tus datos
            'finish_date' => $this->faker->dateTimeBetween('now', '+1 month'),
            'video' => $this->faker->boolean(),
            'status' => $this->faker->randomElement([0, 1]),
            'folio' => $this->faker->unique()->randomNumber(),
            'cubicle_id' => Cubicle::factory(),
        ];
    }
}
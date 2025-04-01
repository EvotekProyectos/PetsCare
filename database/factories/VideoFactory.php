<?php

namespace Database\Factories;

use App\Models\Reception;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Video>
 */
class VideoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'reception_id' => rand(0,1) ?  Reception::factory() :  null, //genera recepciton random opcionalmente
            'vet_id' => User::factory(), //genera usuario random
            'send_date' => $this->faker->dateTime(), //genera fehca y hora random
            'status' => $this->faker->boolean(), // status aleatorio
        ];
    }
}

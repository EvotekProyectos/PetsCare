<?php

namespace Database\Factories;

use App\Models\GroomingStatus;
use App\Models\Reception;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\GroomingStatusHistory>
 */
class GroomingStatusHistoryFactory extends Factory
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
            'grooming_status_id' => GroomingStatus::factory(), // crea status de grooming randoms
        ];
    }
}

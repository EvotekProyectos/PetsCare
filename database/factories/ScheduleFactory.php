<?php

namespace Database\Factories;

use App\Models\CoverArea;
use App\Models\Shift;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Schedule>
 */
class ScheduleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'begin' => $this->faker->dateTimeBetween('-7 days', '+15 days')->format('Y-m-d'), //fecha aleaotria
			'end' => $this->faker->dateTimeBetween('now', '+15 days')->format('Y-m-d'), //fecha aleaotria
            'shift_id' => Shift::factory(), //genera turno random
            'user_id' => User::factory(), //genera usuario random
            'cover_area_id' => CoverArea::factory(), //genera area a cubriri random
        ];
    }
}

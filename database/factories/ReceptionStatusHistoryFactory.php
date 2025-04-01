<?php

namespace Database\Factories;

use App\Models\AttentionStatus;
use App\Models\Reception;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ReceptionStatusHistory>
 */
class ReceptionStatusHistoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
           'reception_id' => Reception::factory(), //genera recepcion random
            'attention_status_id' => AttentionStatus::factory(), //genera estado de atencion random
        ];
    }
}

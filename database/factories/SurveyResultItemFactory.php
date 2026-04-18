<?php

namespace Database\Factories;

use App\Models\SurveyResult;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SurveyResultItem>
 */
class SurveyResultItemFactory extends Factory
{
     public function definition(): array
    {
        $price = fake()->numberBetween(10000, 500000);
        $qty   = fake()->numberBetween(1, 10);
 
        return [
            'survey_result_id' => SurveyResult::factory(),
            'component_id'     => null,
            'type'             => fake()->randomElement(['tool', 'material', 'sparepart', 'component']),
            'name'             => fake()->words(3, true),
            'qty'              => $qty,
            'unit'             => fake()->randomElement(['pcs', 'meter', 'liter', 'kg', 'kaleng']),
            'price'            => $price,
            'subtotal'         => $price * $qty,
            'description'      => fake()->optional(0.5)->sentence(),
        ];
    }
 
    public function sparepart(): static
    {
        return $this->state(['type' => 'sparepart']);
    }
 
    public function material(): static
    {
        return $this->state(['type' => 'material']);
    }
}

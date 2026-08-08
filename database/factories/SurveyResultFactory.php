<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\SurveyResult;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SurveyResult>
 */
class SurveyResultFactory extends Factory
{
   public function definition(): array
    {
        $serviceCost   = fake()->numberBetween(200000, 1000000);
        $sparepartCost = fake()->numberBetween(100000, 800000);
        $otherCost     = fake()->numberBetween(0, 200000);
 
        return [
            'booking_id'           => Booking::factory(),
            'technician_id'        => User::factory()->technician(),
            'inspection_result'    => fake()->paragraph(3),
            'problem_summary'      => fake()->paragraph(2),
            'recommended_action'   => fake()->paragraph(2),
            'estimated_duration'   => fake()->numberBetween(60, 480),
            'service_cost'         => $serviceCost,
            'sparepart_cost'       => $sparepartCost,
            'other_cost'           => $otherCost,
            'estimated_total_cost' => $serviceCost + $sparepartCost + $otherCost,
            'status'               => SurveyResult::STATUS_DRAFT,
            'rejection_reason'     => null,
            'submitted_at'         => null,
            'approved_at'          => null,
            'rejected_at'          => null,
        ];
    }
 
    public function draft(): static
    {
        return $this->state(['status' => SurveyResult::STATUS_DRAFT]);
    }
 
    public function submitted(): static
    {
        return $this->state([
            'status'       => SurveyResult::STATUS_SUBMITTED,
            'submitted_at' => now(),
        ]);
    }
 
    public function approved(): static
    {
        return $this->state([
            'status'       => SurveyResult::STATUS_APPROVED,
            'submitted_at' => now()->subHours(6),
            'approved_at'  => now()->subHours(2),
        ]);
    }
 
    public function rejected(): static
    {
        return $this->state([
            'status'           => SurveyResult::STATUS_REJECTED,
            'submitted_at'     => now()->subHours(6),
            'rejected_at'      => now()->subHours(2),
            'rejection_reason' => fake('id_ID')->sentence(),
        ]);
    }
}

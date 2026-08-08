<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\MaintenanceReport;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MaintenanceReport>
 */
class MaintenanceReportFactory extends Factory
{
     public function definition(): array
    {
        return [
            'booking_id'       => Booking::factory(),
            'technician_id'    => User::factory()->technician(),
            'report'           => fake()->paragraphs(3, true),
            'before_condition' => fake()->paragraph(2),
            'after_condition'  => fake()->paragraph(2),
            'action_taken'     => fake()->paragraph(2),
            'recommendation'   => fake()->paragraph(2),
            'condition'        => fake()->randomElement([
                MaintenanceReport::CONDITION_GOOD,
                MaintenanceReport::CONDITION_NEED_ATTENTION,
                MaintenanceReport::CONDITION_CRITICAL,
            ]),
            'work_duration' => fake()->numberBetween(60, 480),
            'reported_at'   => now(),
        ];
    }
 
    public function good(): static
    {
        return $this->state(['condition' => MaintenanceReport::CONDITION_GOOD]);
    }
 
    public function needAttention(): static
    {
        return $this->state(['condition' => MaintenanceReport::CONDITION_NEED_ATTENTION]);
    }
 
    public function critical(): static
    {
        return $this->state(['condition' => MaintenanceReport::CONDITION_CRITICAL]);
    }
}

<?php

namespace Database\Factories;

use App\Models\MaintenanceReport;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ReportPhoto>
 */
class ReportPhotoFactory extends Factory
{
     public function definition(): array
    {
        return [
            'report_id' => MaintenanceReport::factory(),
            'photo'     => 'images/report_photos/dummy.jpg',
            'type'      => fake()->randomElement(['before', 'after', 'documentation']),
            'caption'   => fake()->optional(0.6)->sentence(),
        ];
    }
 
    public function before(): static
    {
        return $this->state(['type' => 'before']);
    }
 
    public function after(): static
    {
        return $this->state(['type' => 'after']);
    }
 
    public function documentation(): static
    {
        return $this->state(['type' => 'documentation']);
    }
}

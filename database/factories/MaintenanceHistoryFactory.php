<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\MaintenanceLocation;
use App\Models\MaintenanceReport;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MaintenanceHistory>
 */
class MaintenanceHistoryFactory extends Factory
{
  public function definition(): array
    {
        return [
            'location_id'      => MaintenanceLocation::factory(),
            'booking_id'       => Booking::factory(),
            'maintenance_date' => fake()->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
            'summary'          => fake()->paragraph(2),
            'condition_result' => fake()->randomElement([
                MaintenanceReport::CONDITION_GOOD,
                MaintenanceReport::CONDITION_NEED_ATTENTION,
                MaintenanceReport::CONDITION_CRITICAL,
            ]),
        ];
    }
}

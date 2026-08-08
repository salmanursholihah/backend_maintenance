<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\BookingTechnician;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BookingTechnician>
 */
class BookingTechnicianFactory extends Factory
{
    public function definition(): array
    {
        return [
            'booking_id'    => Booking::factory(),
            'technician_id' => User::factory()->technician(),
            'is_lead'       => true,
            'status'        => BookingTechnician::STATUS_ASSIGNED,
            'note'          => null,
            'assigned_at'   => now(),
            'responded_at'  => null,
        ];
    }
 
    public function accepted(): static
    {
        return $this->state([
            'status'       => BookingTechnician::STATUS_ACCEPTED,
            'responded_at' => now(),
        ]);
    }
 
    public function rejected(): static
    {
        return $this->state([
            'status'       => BookingTechnician::STATUS_REJECTED,
            'note'         => fake('id_ID')->sentence(),
            'responded_at' => now(),
        ]);
    }
 
    public function working(): static
    {
        return $this->state([
            'status'       => BookingTechnician::STATUS_WORKING,
            'responded_at' => now()->subHours(2),
        ]);
    }
 
    public function completed(): static
    {
        return $this->state([
            'status'       => BookingTechnician::STATUS_COMPLETED,
            'responded_at' => now()->subDays(2),
        ]);
    }
}

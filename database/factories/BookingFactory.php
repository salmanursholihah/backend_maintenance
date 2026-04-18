<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\MaintenanceLocation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Booking>
 */
class BookingFactory extends Factory
{
      public function definition(): array
    {
        return [
            'user_id'               => User::factory()->customer(),
            'location_id'           => MaintenanceLocation::factory(),
            'booking_code'          => 'BK-' . strtoupper(fake()->unique()->bothify('????####')),
            'booking_date'          => fake()->dateTimeBetween('-3 months', '+1 month')->format('Y-m-d'),
            'booking_time'          => fake()->time('H:i'),
            'status'                => Booking::STATUS_WAITING_TECHNICIAN,
            'payment_status'        => Booking::PAYMENT_UNPAID,
            'complaint'             => fake('id_ID')->sentence(10),
            'customer_note'         => fake()->optional(0.4)->sentence(),
            'cancel_reason'         => null,
            'estimated_total_price' => 0,
            'final_total_price'     => 0,
            'survey_scheduled_at'   => null,
            'approved_at'           => null,
            'started_at'            => null,
            'completed_at'          => null,
            'cancelled_at'          => null,
        ];
    }
 
    // Status: menunggu assign teknisi
    public function waitingTechnician(): static
    {
        return $this->state([
            'status' => Booking::STATUS_WAITING_TECHNICIAN,
        ]);
    }
 
    // Status: teknisi sudah di-assign
    public function surveyScheduled(): static
    {
        return $this->state([
            'status'              => Booking::STATUS_SURVEY_SCHEDULED,
            'survey_scheduled_at' => now(),
        ]);
    }
 
    // Status: menunggu approval estimasi
    public function waitingEstimation(): static
    {
        return $this->state([
            'status'                => Booking::STATUS_WAITING_ESTIMATION_APPROVAL,
            'estimated_total_price' => fake()->numberBetween(300000, 3000000),
            'survey_scheduled_at'   => now()->subDay(),
        ]);
    }
 
    // Status: estimasi disetujui, siap dikerjakan
    public function estimationApproved(): static
    {
        $price = fake()->numberBetween(300000, 3000000);
        return $this->state([
            'status'                => Booking::STATUS_ESTIMATION_APPROVED,
            'estimated_total_price' => $price,
            'survey_scheduled_at'   => now()->subDays(2),
            'approved_at'           => now()->subDay(),
        ]);
    }
 
    // Status: sedang dikerjakan
    public function onProgress(): static
    {
        $price = fake()->numberBetween(300000, 3000000);
        return $this->state([
            'status'                => Booking::STATUS_MAINTENANCE_ON_PROGRESS,
            'estimated_total_price' => $price,
            'survey_scheduled_at'   => now()->subDays(3),
            'approved_at'           => now()->subDays(2),
            'started_at'            => now()->subDay(),
        ]);
    }
 
    // Status: selesai dan sudah bayar
    public function completed(): static
    {
        $price = fake()->numberBetween(300000, 3000000);
        return $this->state([
            'status'                => Booking::STATUS_COMPLETED,
            'payment_status'        => Booking::PAYMENT_PAID,
            'estimated_total_price' => $price,
            'final_total_price'     => $price,
            'survey_scheduled_at'   => now()->subDays(5),
            'approved_at'           => now()->subDays(4),
            'started_at'            => now()->subDays(3),
            'completed_at'          => now()->subDays(1),
            'booking_date'          => now()->subDays(5)->format('Y-m-d'),
        ]);
    }
 
    // Status: dibatalkan
    public function cancelled(): static
    {
        return $this->state([
            'status'        => Booking::STATUS_CANCELLED,
            'cancel_reason' => fake('id_ID')->sentence(),
            'cancelled_at'  => now()->subDays(fake()->numberBetween(1, 30)),
        ]);
    }
}

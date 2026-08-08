<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
   public function definition(): array
    {
        return [
            'booking_id'       => Booking::factory(),
            'order_id'         => 'IPAL-BK-' . strtoupper(fake()->unique()->bothify('????####')) . '-' . time(),
            'amount'           => fake()->numberBetween(300000, 3000000),
            'status'           => Payment::STATUS_PENDING,
            'payment_type'     => null,
            'transaction_id'   => null,
            'va_number'        => null,
            'payment_code'     => null,
            'snap_token'       => null,
            'payment_metadata' => null,
            'paid_at'          => null,
            'expired_at'       => now()->addHours(24),
        ];
    }
 
    public function pending(): static
    {
        return $this->state(['status' => Payment::STATUS_PENDING]);
    }
 
    public function paid(): static
    {
        return $this->state([
            'status'         => Payment::STATUS_PAID,
            'payment_type'   => fake()->randomElement(['bank_transfer', 'gopay', 'qris']),
            'transaction_id' => 'TXN-' . strtoupper(fake()->unique()->bothify('??########')),
            'paid_at'        => now()->subHours(fake()->numberBetween(1, 48)),
        ]);
    }
 
    public function failed(): static
    {
        return $this->state(['status' => Payment::STATUS_FAILED]);
    }
 
    public function expired(): static
    {
        return $this->state([
            'status'     => Payment::STATUS_EXPIRED,
            'expired_at' => now()->subHours(1),
        ]);
    }
}

<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BookingDetail>
 */
class BookingDetailFactory extends Factory
{
     public function definition(): array
    {
        $price = fake()->randomElement([200000, 250000, 300000, 350000, 450000, 500000, 750000, 850000]);
        $qty   = fake()->numberBetween(1, 3);
 
        return [
            'booking_id' => Booking::factory(),
            'service_id' => Service::factory(),
            'price'      => $price,
            'qty'        => $qty,
            'subtotal'   => $price * $qty,
        ];
    }
}

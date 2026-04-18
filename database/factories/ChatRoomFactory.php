<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ChatRoom>
 */
class ChatRoomFactory extends Factory
{
    public function definition(): array
    {
        return [
            'booking_id'    => Booking::factory(),
            'customer_id'   => User::factory()->customer(),
            'technician_id' => User::factory()->technician(),
        ];
    }
}

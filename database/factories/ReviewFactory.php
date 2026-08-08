<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Review>
 */
class ReviewFactory extends Factory
{
   public function definition(): array
    {
        return [
            'booking_id'    => Booking::factory(),
            'user_id'       => User::factory()->customer(),
            'technician_id' => User::factory()->technician(),
            'rating'        => fake()->numberBetween(1, 5),
            'review'        => fake()->optional(0.7)->paragraph(),
        ];
    }
 
    public function rating5(): static
    {
        return $this->state([
            'rating' => 5,
            'review' => 'Teknisi sangat profesional dan cepat. IPAL berfungsi kembali dengan baik.',
        ]);
    }
 
    public function rating3(): static
    {
        return $this->state(['rating' => 3]);
    }
 
    public function rating1(): static
    {
        return $this->state(['rating' => 1]);
    }
}

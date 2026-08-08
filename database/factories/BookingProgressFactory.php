<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BookingProgress>
 */
class BookingProgressFactory extends Factory
{
   private static array $titles = [
        'Tiba di lokasi',
        'Pemeriksaan awal kondisi IPAL',
        'Pembersihan filter dimulai',
        'Pembersihan filter selesai',
        'Penggantian media biofilter',
        'Pengujian pompa aerasi',
        'Kalibrasi sensor pH',
        'Pengecekan pipa dan valve',
        'Pengurasan lumpur selesai',
        'Uji coba sistem berjalan',
        'Pembersihan area kerja',
        'Dokumentasi selesai',
    ];
 
    public function definition(): array
    {
        return [
            'booking_id'       => Booking::factory(),
            'technician_id'    => User::factory()->technician(),
            'title'            => fake()->randomElement(self::$titles),
            'description'      => fake()->paragraph(2),
            'progress_percent' => fake()->numberBetween(10, 100),
            'photo'            => null,
            'progress_at'      => fake()->dateTimeBetween('-2 days', 'now'),
        ];
    }
}

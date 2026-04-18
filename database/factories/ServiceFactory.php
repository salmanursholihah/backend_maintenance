<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Service>
 */
class ServiceFactory extends Factory
{
   // Data layanan IPAL realistis
    private static array $services = [
        ['name' => 'Pembersihan Filter IPAL',       'price' => 350000,  'duration' => 120],
        ['name' => 'Penggantian Media Biofilter',    'price' => 850000,  'duration' => 240],
        ['name' => 'Desinfeksi Unit IPAL',           'price' => 450000,  'duration' => 180],
        ['name' => 'Kalibrasi Sensor pH',            'price' => 300000,  'duration' => 90],
        ['name' => 'Penggantian Pompa Aerasi',       'price' => 1200000, 'duration' => 300],
        ['name' => 'Inspeksi Pipa & Valve',          'price' => 250000,  'duration' => 60],
        ['name' => 'Pengurasan Lumpur (Sludge)',     'price' => 750000,  'duration' => 360],
        ['name' => 'Uji Kualitas Efluen',            'price' => 500000,  'duration' => 120],
        ['name' => 'Konsultasi & Pemeriksaan Rutin', 'price' => 200000,  'duration' => 60],
    ];
 
    public function definition(): array
    {
        $service = fake()->randomElement(self::$services);
 
        return [
            'name'                => $service['name'],
            'description'         => fake()->sentence(12),
            'base_price'          => $service['price'],
            'duration_estimation' => $service['duration'],
            'is_active'           => true,
        ];
    }
 
    public function inactive(): static
    {
        return $this->state(['is_active' => false]);
    }
}

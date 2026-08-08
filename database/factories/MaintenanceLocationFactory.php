<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MaintenanceLocation>
 */
class MaintenanceLocationFactory extends Factory
{
   private static array $ipalTypes = [
        'Biofilter Aerobik',
        'Anaerob',
        'SBR (Sequential Batch Reactor)',
        'Constructed Wetland',
        'Rotating Biological Contactor',
    ];
 
    private static array $installationTypes = [
        'Tanam',
        'Portable',
        'Semi-permanen',
        'Atas tanah',
    ];
 
    public function definition(): array
    {
        $intervalDays = fake()->randomElement([30, 60, 90, 180]);
 
        return [
            'user_id'                   => User::factory()->customer(),
            'location_name'             => fake('id_ID')->company() . ' - ' . fake('id_ID')->city(),
            'address'                   => fake('id_ID')->address(),
            'latitude'                  => fake()->latitude(-8.8, -7.5),
            'longitude'                 => fake()->longitude(110.0, 111.5),
            'ipal_type'                 => fake()->randomElement(self::$ipalTypes),
            'capacity'                  => fake()->numberBetween(1, 100) . ' m³/hari',
            'installation_type'         => fake()->randomElement(self::$installationTypes),
            'next_maintenance_date'     => fake()->dateTimeBetween('-30 days', '+90 days')->format('Y-m-d'),
            'maintenance_interval_days' => $intervalDays,
            'description'               => fake()->paragraph(),
            'is_active'                 => true,
        ];
    }
 
    // Lokasi yang sudah overdue maintenance
    public function overdue(): static
    {
        return $this->state([
            'next_maintenance_date' => fake()->dateTimeBetween('-90 days', '-1 day')->format('Y-m-d'),
        ]);
    }
}

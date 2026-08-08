<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Component>
 */
class ComponentFactory extends Factory
{
     private static array $components = [
        ['name' => 'Pompa Aerasi 1/4 HP',         'type' => 'sparepart', 'unit' => 'pcs',    'price' => 450000],
        ['name' => 'Pompa Aerasi 1/2 HP',         'type' => 'sparepart', 'unit' => 'pcs',    'price' => 750000],
        ['name' => 'Diffuser Membran',             'type' => 'sparepart', 'unit' => 'pcs',    'price' => 85000],
        ['name' => 'Elektroda Sensor pH',          'type' => 'sparepart', 'unit' => 'pcs',    'price' => 350000],
        ['name' => 'Ball Valve PVC 3/4 inch',      'type' => 'sparepart', 'unit' => 'pcs',    'price' => 35000],
        ['name' => 'Media Bio-Ball',               'type' => 'material',  'unit' => 'liter',  'price' => 25000],
        ['name' => 'Media Sarang Tawon',           'type' => 'material',  'unit' => 'liter',  'price' => 18000],
        ['name' => 'Kaporit',                      'type' => 'material',  'unit' => 'kg',     'price' => 22000],
        ['name' => 'Karbon Aktif Granular',        'type' => 'material',  'unit' => 'kg',     'price' => 55000],
        ['name' => 'Sewa Pompa Sedot Lumpur',      'type' => 'tool',      'unit' => 'hari',   'price' => 350000],
        ['name' => 'Pipa PVC 2 inch',              'type' => 'component', 'unit' => 'meter',  'price' => 28000],
        ['name' => 'Pipa PVC 3 inch',              'type' => 'component', 'unit' => 'meter',  'price' => 45000],
    ];
 
    public function definition(): array
    {
        $component = fake()->randomElement(self::$components);
 
        return [
            'name'          => $component['name'],
            'type'          => $component['type'],
            'unit'          => $component['unit'],
            'default_price' => $component['price'],
            'description'   => fake()->sentence(8),
            'is_active'     => true,
        ];
    }
 
    public function sparepart(): static
    {
        return $this->state(['type' => 'sparepart']);
    }
 
    public function material(): static
    {
        return $this->state(['type' => 'material']);
    }
 
    public function tool(): static
    {
        return $this->state(['type' => 'tool']);
    }
}

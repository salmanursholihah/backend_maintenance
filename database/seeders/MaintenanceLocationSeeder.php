<?php

namespace Database\Seeders;

use App\Models\MaintenanceLocation;
use App\Models\User;
use Illuminate\Database\Seeder;

class MaintenanceLocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
           $customer = User::where('email', 'customer@ipal.test')->first();
 
        // ── Lokasi tetap untuk akun dev customer ────────────────────
        if ($customer) {
            $locations = [
                [
                    'location_name'             => 'IPAL Kantor Pusat',
                    'address'                   => 'Jl. Sudirman No. 12, Yogyakarta',
                    'latitude'                  => '-7.7956',
                    'longitude'                 => '110.3695',
                    'ipal_type'                 => 'Biofilter Aerobik',
                    'capacity'                  => '5 m³/hari',
                    'installation_type'         => 'Tanam',
                    'maintenance_interval_days' => 90,
                    'next_maintenance_date'     => now()->addDays(15)->format('Y-m-d'),
                    'description'               => 'IPAL untuk gedung perkantoran 5 lantai kapasitas 200 orang.',
                    'is_active'                 => true,
                ],
                [
                    'location_name'             => 'IPAL Gudang Selatan',
                    'address'                   => 'Jl. Ring Road Selatan No. 88, Bantul',
                    'latitude'                  => '-7.8890',
                    'longitude'                 => '110.3500',
                    'ipal_type'                 => 'Anaerob',
                    'capacity'                  => '10 m³/hari',
                    'installation_type'         => 'Semi-permanen',
                    'maintenance_interval_days' => 60,
                    'next_maintenance_date'     => now()->subDays(5)->format('Y-m-d'), // overdue
                    'description'               => 'IPAL untuk kawasan pergudangan dan workshop.',
                    'is_active'                 => true,
                ],
                [
                    'location_name'             => 'IPAL Mess Karyawan',
                    'address'                   => 'Jl. Wates Km 12, Kulon Progo',
                    'latitude'                  => '-7.8012',
                    'longitude'                 => '110.1823',
                    'ipal_type'                 => 'SBR (Sequential Batch Reactor)',
                    'capacity'                  => '3 m³/hari',
                    'installation_type'         => 'Tanam',
                    'maintenance_interval_days' => 90,
                    'next_maintenance_date'     => now()->addDays(45)->format('Y-m-d'),
                    'description'               => 'IPAL untuk asrama karyawan 50 orang.',
                    'is_active'                 => true,
                ],
            ];
 
            foreach ($locations as $loc) {
                MaintenanceLocation::updateOrCreate(
                    [
                        'user_id'       => $customer->id,
                        'location_name' => $loc['location_name'],
                    ],
                    [...$loc, 'user_id' => $customer->id]
                );
            }
        }
 
        // ── Lokasi random untuk customer lain ───────────────────────
        $otherCustomers = User::where('role', 'customer')
            ->where('email', '!=', 'customer@ipal.test')
            ->get();
 
        foreach ($otherCustomers as $c) {
            MaintenanceLocation::factory()
                ->count(fake()->numberBetween(1, 3))
                ->create(['user_id' => $c->id]);
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
          // ── Akun tetap untuk development & testing ─────────────────
        // Admin
        User::updateOrCreate(
            ['email' => 'admin@ipal.test'],
            [
                'name'      => 'Admin IPAL',
                'password'  => Hash::make('password'),
                'role'      => 'admin',
                'phone'     => '081200000001',
                'address'   => 'Jl. Sudirman No. 1, Yogyakarta',
                'is_active' => true,
            ]
        );
 
        // Customer
        User::updateOrCreate(
            ['email' => 'customer@ipal.test'],
            [
                'name'      => 'Budi Santoso',
                'password'  => Hash::make('password'),
                'role'      => 'customer',
                'phone'     => '081200000002',
                'address'   => 'Jl. Magelang No. 45, Yogyakarta',
                'is_active' => true,
            ]
        );
 
        // Teknisi 1
        User::updateOrCreate(
            ['email' => 'teknisi1@ipal.test'],
            [
                'name'      => 'Agus Prasetyo',
                'password'  => Hash::make('password'),
                'role'      => 'technician',
                'phone'     => '081200000003',
                'address'   => 'Jl. Kaliurang Km 5, Yogyakarta',
                'is_active' => true,
            ]
        );
 
        // Teknisi 2
        User::updateOrCreate(
            ['email' => 'teknisi2@ipal.test'],
            [
                'name'      => 'Doni Kurniawan',
                'password'  => Hash::make('password'),
                'role'      => 'technician',
                'phone'     => '081200000004',
                'address'   => 'Jl. Solo Km 8, Yogyakarta',
                'is_active' => true,
            ]
        );
 
        // ── Data random untuk testing ───────────────────────────────
        User::factory()->customer()->count(8)->create();
        User::factory()->technician()->count(4)->create();
    }
    
}

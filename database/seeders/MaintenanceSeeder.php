<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class MaintenanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | USERS
        |--------------------------------------------------------------------------
        */

        $adminId = DB::table('users')->insertGetId([
            'name' => 'Admin Maintenance',
            'email' => 'admin@maintenance.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '081111111111',
            'address' => 'Kantor Admin',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $customerId = DB::table('users')->insertGetId([
            'name' => 'Customer Satu',
            'email' => 'customer@maintenance.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'phone' => '082222222222',
            'address' => 'Alamat Customer',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $technicianId = DB::table('users')->insertGetId([
            'name' => 'Technician Satu',
            'email' => 'technician@maintenance.com',
            'password' => Hash::make('password'),
            'role' => 'technician',
            'phone' => '083333333333',
            'address' => 'Alamat Teknisi',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        /*
        |--------------------------------------------------------------------------
        | SERVICES
        |--------------------------------------------------------------------------
        */

        $serviceId = DB::table('services')->insertGetId([
            'name' => 'Perawatan IPAL',
            'description' => 'Service maintenance rutin IPAL',
            'base_price' => 500000,
            'duration_estimation' => 120,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        /*
        |--------------------------------------------------------------------------
        | LOCATION
        |--------------------------------------------------------------------------
        */

        $locationId = DB::table('maintenance_locations')->insertGetId([
            'user_id' => $customerId,
            'location_name' => 'Rumah Customer',
            'address' => 'Jl. Mawar No.1',
            'latitude' => '-7.250445',
            'longitude' => '112.768845',
            'ipal_type' => 'IPAL Rumah Tangga',
            'capacity' => '1000L',
            'installation_type' => 'Portable',
            'description' => 'Lokasi utama maintenance',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        /*
        |--------------------------------------------------------------------------
        | BOOKINGS
        |--------------------------------------------------------------------------
        */

        $bookingId = DB::table('bookings')->insertGetId([
            'user_id' => $customerId,
            'location_id' => $locationId,
            'booking_code' => 'BOOK-001',
            'booking_date' => now()->toDateString(),
            'booking_time' => now()->format('H:i:s'),
            'status' => 'waiting_technician',
            'survey_status' => 'pending',
            'payment_status' => 'unpaid',
            'complaint' => 'Pompa tidak menyala',
            'estimated_total_price' => 750000,
            'final_total_price' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        /*
        |--------------------------------------------------------------------------
        | BOOKING DETAILS
        |--------------------------------------------------------------------------
        */

        DB::table('booking_details')->insert([
            'booking_id' => $bookingId,
            'service_id' => $serviceId,
            'price' => 500000,
            'qty' => 1,
            'subtotal' => 500000,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        /*
        |--------------------------------------------------------------------------
        | BOOKING TECHNICIAN
        |--------------------------------------------------------------------------
        */

        DB::table('booking_technicians')->insert([
            'booking_id' => $bookingId,
            'technician_id' => $technicianId,
            'status' => 'assigned',
            'assigned_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        /*
        |--------------------------------------------------------------------------
        | SURVEY RESULTS
        |--------------------------------------------------------------------------
        */

        $surveyId = DB::table('survey_results')->insertGetId([
            'booking_id' => $bookingId,
            'technician_id' => $technicianId,
            'inspection_result' => 'Pompa mengalami kerusakan ringan',
            'problem_summary' => 'Seal bocor',
            'recommended_action' => 'Ganti seal',
            'estimated_duration' => 90,
            'service_cost' => 300000,
            'sparepart_cost' => 150000,
            'other_cost' => 50000,
            'estimated_total_cost' => 500000,
            'status' => 'submitted',
            'submitted_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        /*
        |--------------------------------------------------------------------------
        | SURVEY RESULT ITEMS
        |--------------------------------------------------------------------------
        */

        DB::table('survey_result_items')->insert([
            'survey_result_id' => $surveyId,
            'type' => 'sparepart',
            'name' => 'Seal Pompa',
            'qty' => 1,
            'unit' => 'pcs',
            'price' => 150000,
            'subtotal' => 150000,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        /*
        |--------------------------------------------------------------------------
        | TECHNICIAN PRICE REFERENCES
        |--------------------------------------------------------------------------
        */

        DB::table('technician_price_references')->insert([
            'technician_id' => $technicianId,
            'component_name' => 'Pompa Air',
            'damage_level' => 'Ringan',
            'work_type' => 'Perbaikan',
            'price' => 300000,
            'description' => 'Harga jasa perbaikan pompa',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        /*
        |--------------------------------------------------------------------------
        | BOOKING PROGRESSES
        |--------------------------------------------------------------------------
        */

        DB::table('booking_progresses')->insert([
            'booking_id' => $bookingId,
            'technician_id' => $technicianId,
            'title' => 'Pemeriksaan Awal',
            'description' => 'Pengecekan kondisi pompa',
            'progress_percent' => 25,
            'progress_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}

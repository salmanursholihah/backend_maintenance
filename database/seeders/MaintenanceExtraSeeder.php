<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MaintenanceExtraSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * NOTE: Seeder ini melengkapi tabel yang BELUM diisi oleh
     * MaintenanceSeeder: maintenance_reports, report_photos, payments,
     * maintenance_histories, reviews, notifications, chat_rooms,
     * chat_messages.
     *
     * Jalankan MaintenanceSeeder dulu sebelum seeder ini, karena semua
     * ID di sini diambil lewat query (bukan hardcode) supaya tetap valid
     * kalau urutan auto-increment berbeda.
     */
    public function run(): void
    {
        $customer = DB::table('users')->where('email', 'customer@maintenance.com')->first();
        $technician = DB::table('users')->where('email', 'technician@maintenance.com')->first();
        $booking = DB::table('bookings')->where('booking_code', 'BOOK-001')->first();
        $location = DB::table('maintenance_locations')
            ->where('user_id', $customer->id)
            ->first();

        if (!$customer || !$technician || !$booking || !$location) {
            $this->command?->warn(
                'MaintenanceExtraSeeder dilewati: data dasar dari MaintenanceSeeder belum ditemukan. Jalankan MaintenanceSeeder terlebih dahulu.'
            );
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | MAINTENANCE REPORT + REPORT PHOTOS
        |--------------------------------------------------------------------------
        */

        $reportId = DB::table('maintenance_reports')->insertGetId([
            'booking_id' => $booking->id,
            'technician_id' => $technician->id,
            'report' => 'Pompa berhasil diperbaiki dengan mengganti seal yang bocor.',
            'before_condition' => 'Pompa tidak menyala, seal bocor menyebabkan tekanan air turun.',
            'after_condition' => 'Pompa menyala normal, tidak ada kebocoran.',
            'action_taken' => 'Mengganti seal pompa dan membersihkan filter.',
            'recommendation' => 'Lakukan pengecekan seal setiap 6 bulan sekali.',
            'condition' => 'good',
            'work_duration' => 90,
            'reported_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('report_photos')->insert([
            [
                'report_id' => $reportId,
                'photo' => 'reports/before_1.jpg',
                'type' => 'before',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'report_id' => $reportId,
                'photo' => 'reports/after_1.jpg',
                'type' => 'after',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'report_id' => $reportId,
                'photo' => 'reports/documentation_1.jpg',
                'type' => 'documentation',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | PAYMENT
        |--------------------------------------------------------------------------
        */

        DB::table('payments')->insert([
            'booking_id' => $booking->id,
            'amount' => 500000,
            'status' => 'paid',
            'payment_method' => 'transfer_bank',
            'transaction_id' => 'TRX-' . strtoupper(uniqid()),
            'payment_proof' => 'payments/proof_1.jpg',
            'paid_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        /*
        |--------------------------------------------------------------------------
        | MAINTENANCE HISTORY
        |--------------------------------------------------------------------------
        */

        DB::table('maintenance_histories')->insert([
            'location_id' => $location->id,
            'booking_id' => $booking->id,
            'maintenance_date' => now()->toDateString(),
            'summary' => 'Perbaikan pompa IPAL akibat seal bocor, sudah selesai dan berjalan normal.',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        /*
        |--------------------------------------------------------------------------
        | REVIEW
        |--------------------------------------------------------------------------
        */

        DB::table('reviews')->insert([
            'booking_id' => $booking->id,
            'user_id' => $customer->id,
            'technician_id' => $technician->id,
            'rating' => 5,
            'review' => 'Teknisi datang tepat waktu dan pekerjaan rapi. Pompa langsung normal kembali.',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        /*
        |--------------------------------------------------------------------------
        | NOTIFICATIONS
        |--------------------------------------------------------------------------
        */
        DB::table('notifications')->insert([
            [
                'user_id' => $customer->id,
                'title' => 'Booking Dikonfirmasi',
                'message' => 'Booking Anda BOOK-001 telah dikonfirmasi dan teknisi sedang dijadwalkan.',
                'type' => 'booking',
                'reference_id' => $booking->id,
                'is_read' => false,
                'read_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $customer->id,
                'title' => 'Pembayaran Berhasil',
                'message' => 'Pembayaran untuk booking BOOK-001 telah berhasil diverifikasi.',
                'type' => 'payment',
                'reference_id' => $booking->id,
                'is_read' => false,
                'read_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $technician->id,
                'title' => 'Booking Baru Ditugaskan',
                'message' => 'Anda ditugaskan untuk menangani booking BOOK-001.',
                'type' => 'booking',
                'reference_id' => $booking->id,
                'is_read' => true,
                'read_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
        /*
        |--------------------------------------------------------------------------
        | CHAT ROOM + CHAT MESSAGES
        |--------------------------------------------------------------------------
        */

        $chatRoomId = DB::table('chat_rooms')->insertGetId([
            'booking_id' => $booking->id,
            'customer_id' => $customer->id,
            'technician_id' => $technician->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('chat_messages')->insert([
            [
                'chat_room_id' => $chatRoomId,
                'sender_id' => $customer->id,
                'message' => 'Halo, saya ingin diskusi terkait survei IPAL.',
                'is_read' => true,
                'read_at' => now(),
                'created_at' => now()->subMinutes(10),
                'updated_at' => now()->subMinutes(10),
            ],
            [
                'chat_room_id' => $chatRoomId,
                'sender_id' => $technician->id,
                'message' => 'Baik, silakan. Saya bantu jelaskan detail biaya dan pengerjaannya.',
                'is_read' => true,
                'read_at' => now(),
                'created_at' => now()->subMinutes(8),
                'updated_at' => now()->subMinutes(8),
            ],
            [
                'chat_room_id' => $chatRoomId,
                'sender_id' => $customer->id,
                'message' => 'Apakah jadwal dan harga masih bisa disesuaikan?',
                'is_read' => false,
                'read_at' => null, // ditambahkan supaya konsisten dengan 2 baris di atas
                'created_at' => now()->subMinutes(5),
                'updated_at' => now()->subMinutes(5),
            ],
        ]);
    }
}

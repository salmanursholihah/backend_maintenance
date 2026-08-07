<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Mengisi notifikasi untuk semua user dengan role 'technician'.
     * Type yang dipakai HARUS sinkron dengan _routeForType() /
     * _styleOf() di NotifikasiPage (Flutter):
     * booking, survey, progress, report, payment, chat.
     */
    public function run(): void
    {
        $technicians = User::where('role', 'technician')->get();

        if ($technicians->isEmpty()) {
            $this->command?->warn(
                'Tidak ada user dengan role technician. Jalankan UserSeeder dulu.'
            );
            return;
        }

        // Ambil referensi booking kalau ada, biar reference_id realistis.
        // Kalau belum ada booking sama sekali, tetap jalan dengan reference_id null.
        $bookingIds = Booking::pluck('id');

        foreach ($technicians as $technician) {
            $notifications = $this->buildNotificationSet($technician->id, $bookingIds);

            foreach ($notifications as $notif) {
                DB::table('notifications')->insert($notif);
            }
        }

        $this->command?->info(
            'NotificationSeeder selesai: ' .
            (count($this->buildNotificationSet(0, $bookingIds)) * $technicians->count()) .
            ' notifikasi dibuat untuk ' . $technicians->count() . ' technician.'
        );
    }

    /**
     * @param  \Illuminate\Support\Collection<int, int>  $bookingIds
     * @return array<int, array<string, mixed>>
     */
    private function buildNotificationSet(int $userId, $bookingIds): array
    {
        $refId = fn () => $bookingIds->isNotEmpty()
            ? $bookingIds->random()
            : null;

        $now = Carbon::now();

        $rows = [
            // ── Booking ──────────────────────────────────────────────
            [
                'title' => 'Booking Baru Masuk',
                'message' => 'Ada permintaan booking baru yang menunggu konfirmasi Anda.',
                'type' => 'booking',
                'reference_id' => $refId(),
                'is_read' => false,
                'read_at' => null,
                'minutes_ago' => 2,
            ],
            [
                'title' => 'Booking Dibatalkan Customer',
                'message' => 'Customer membatalkan booking sebelum jadwal survei dimulai.',
                'type' => 'booking',
                'reference_id' => $refId(),
                'is_read' => true,
                'read_at' => $now->copy()->subHours(5),
                'minutes_ago' => 6 * 60,
            ],

            // ── Survey ───────────────────────────────────────────────
            [
                'title' => 'Jadwal Survei Hari Ini',
                'message' => 'Anda memiliki jadwal survei lokasi IPAL hari ini pukul 13.00.',
                'type' => 'survey',
                'reference_id' => $refId(),
                'is_read' => false,
                'read_at' => null,
                'minutes_ago' => 10,
            ],
            [
                'title' => 'Estimasi Biaya Perlu Dikirim',
                'message' => 'Hasil survei belum dilengkapi estimasi biaya. Segera lengkapi agar customer dapat menyetujui.',
                'type' => 'survey',
                'reference_id' => $refId(),
                'is_read' => false,
                'read_at' => null,
                'minutes_ago' => 20,
            ],

            // ── Progress / Report (maintenance) ─────────────────────
            [
                'title' => 'Maintenance Perlu Update Progres',
                'message' => 'Sudah lebih dari 1 hari sejak update progres terakhir. Tambahkan update terbaru.',
                'type' => 'progress',
                'reference_id' => $refId(),
                'is_read' => true,
                'read_at' => $now->copy()->subDay(),
                'minutes_ago' => 24 * 60,
            ],
            [
                'title' => 'Laporan Maintenance Disetujui',
                'message' => 'Laporan maintenance yang Anda kirim telah disetujui oleh admin.',
                'type' => 'report',
                'reference_id' => $refId(),
                'is_read' => false,
                'read_at' => null,
                'minutes_ago' => 45,
            ],

            // ── Payment ──────────────────────────────────────────────
            [
                'title' => 'Pembayaran Diterima',
                'message' => 'Pembayaran dari customer untuk pekerjaan maintenance telah dikonfirmasi.',
                'type' => 'payment',
                'reference_id' => $refId(),
                'is_read' => false,
                'read_at' => null,
                'minutes_ago' => 30,
            ],
            [
                'title' => 'Penarikan Dana Berhasil',
                'message' => 'Penarikan dana ke rekening Anda telah berhasil diproses.',
                'type' => 'payment',
                'reference_id' => $refId(),
                'is_read' => true,
                'read_at' => $now->copy()->subHours(2),
                'minutes_ago' => 3 * 60,
            ],

            // ── Chat ─────────────────────────────────────────────────
            [
                'title' => 'Pesan Baru dari Customer',
                'message' => 'Customer mengirim pesan terkait jadwal survei besok.',
                'type' => 'chat',
                'reference_id' => $refId(),
                'is_read' => false,
                'read_at' => null,
                'minutes_ago' => 5,
            ],
            [
                'title' => 'Pesan Baru dari Admin',
                'message' => 'Admin menanyakan status pengerjaan maintenance yang tertunda.',
                'type' => 'chat',
                'reference_id' => $refId(),
                'is_read' => true,
                'read_at' => $now->copy()->subHours(1),
                'minutes_ago' => 90,
            ],
        ];

        return array_map(function (array $row) use ($userId, $now) {
            $createdAt = $now->copy()->subMinutes($row['minutes_ago']);

            return [
                'user_id' => $userId,
                'title' => $row['title'],
                'message' => $row['message'],
                'type' => $row['type'],
                'reference_id' => $row['reference_id'],
                'is_read' => $row['is_read'],
                'read_at' => $row['read_at'],
                'created_at' => $createdAt,
                'updated_at' => $row['read_at'] ?? $createdAt,
            ];
        }, $rows);
    }
}

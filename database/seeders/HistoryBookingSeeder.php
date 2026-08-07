<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\BookingDetail;
use App\Models\MaintenanceLocation;
use App\Models\MaintenanceReport;
use App\Models\Review;
use App\Models\Service;
use App\Models\SurveyResult;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class HistoryBookingSeeder extends Seeder
{
    /**
     * Membuat data booking berstatus 'completed' lengkap dengan
     * detail layanan, survey_result / maintenance_report, dan
     * review — supaya HistoryPage (technician) punya data untuk
     * ditampilkan saat testing.
     */
    public function run(): void
    {
        $technician = User::firstOrCreate(
            ['email' => 'teknisi.history@ipal.test'],
            [
                'name' => 'Rendra Wijaya',
                'password' => Hash::make('password'),
                'role' => 'technician',
                'phone' => '0812-0000-1111',
                'is_active' => true,
            ]
        );

        $services = collect([
            ['name' => 'Survei Kondisi IPAL', 'base_price' => 250000],
            ['name' => 'Maintenance Pompa Sirkulasi', 'base_price' => 350000],
            ['name' => 'Perbaikan Panel Kontrol', 'base_price' => 400000],
            ['name' => 'Penggantian Blower', 'base_price' => 500000],
        ])->map(fn ($s) => Service::firstOrCreate(
            ['name' => $s['name']],
            ['base_price' => $s['base_price'], 'duration_estimation' => 120, 'is_active' => true]
        ));

        $customers = collect([
            ['name' => 'PT Tirta Bersih Mandiri', 'email' => 'customer1.history@ipal.test'],
            ['name' => 'CV Lingkungan Sehat', 'email' => 'customer2.history@ipal.test'],
            ['name' => 'Hotel Arunika', 'email' => 'customer3.history@ipal.test'],
            ['name' => 'RS Medika Utama', 'email' => 'customer4.history@ipal.test'],
        ])->map(fn ($c) => User::firstOrCreate(
            ['email' => $c['email']],
            [
                'name' => $c['name'],
                'password' => Hash::make('password'),
                'role' => 'customer',
                'is_active' => true,
            ]
        ));

        $jobs = [
            [
                'customer' => 0,
                'category' => 'Pemeliharaan',
                'location_name' => 'IPAL Sektor A',
                'address' => 'IPAL Sektor A, Jakarta Selatan',
                'services' => [1], // Maintenance Pompa Sirkulasi
                'complaint' => 'Pompa sirkulasi bunyi kasar dan aliran melemah.',
                'report' => 'Pemeriksaan dan penggantian komponen pompa sirkulasi IPAL.',
                'before' => 'Bearing aus, seal bocor ringan.',
                'after' => 'Pompa berjalan normal, tidak ada kebocoran.',
                'action' => 'Penggantian seal, bearing, dan kabel power.',
                'final_price' => 1250000,
                'days_ago' => 6,
                'rating' => 5,
                'review' => 'Pekerjaan rapi, teknisi datang tepat waktu, dan pompa kembali berjalan normal.',
            ],
            [
                'customer' => 1,
                'category' => 'Survei',
                'location_name' => 'Gudang Industri Cikarang',
                'address' => 'Gudang Industri Cikarang',
                'services' => [0], // Survei Kondisi IPAL
                'complaint' => 'Minta pengecekan rutin kondisi bak kontrol dan jalur pembuangan.',
                'final_price' => 450000,
                'days_ago' => 7,
                'rating' => 4,
                'review' => 'Penjelasan hasil survei mudah dipahami dan laporan cukup lengkap.',
                'is_survey_only' => true,
            ],
            [
                'customer' => 2,
                'category' => 'Perbaikan',
                'location_name' => 'Ruang Panel Basement',
                'address' => 'Ruang Panel Basement, Hotel Arunika',
                'services' => [2], // Perbaikan Panel Kontrol
                'complaint' => 'Panel kontrol otomatis sering trip sendiri.',
                'report' => 'Perbaikan panel kontrol otomatis dan pengecekan jalur kelistrikan.',
                'before' => 'Relay rusak, MCB sering trip.',
                'after' => 'Panel normal, tidak ada trip berulang.',
                'action' => 'Penggantian relay, MCB, dan lampu indikator.',
                'final_price' => 980000,
                'days_ago' => 9,
                'rating' => 5,
                'review' => 'Panel sudah normal kembali. Proses pengerjaan cepat dan hasilnya memuaskan.',
            ],
            [
                'customer' => 3,
                'category' => 'Pemeliharaan',
                'location_name' => 'Area IPAL Belakang Gedung B',
                'address' => 'Area IPAL Belakang Gedung B, RS Medika Utama',
                'services' => [3], // Penggantian Blower
                'complaint' => 'Blower bergetar kuat dan tekanan udara turun.',
                'report' => 'Pembersihan blower, penggantian filter, dan pengecekan tekanan udara.',
                'before' => 'Filter kotor, tekanan udara di bawah normal.',
                'after' => 'Blower lebih halus, tekanan udara kembali stabil.',
                'action' => 'Penggantian filter udara dan oli pelumas.',
                'final_price' => 1100000,
                'days_ago' => 12,
                'rating' => 4,
                'review' => 'Blower lebih halus setelah maintenance. Tim cukup komunikatif.',
            ],
        ];

        foreach ($jobs as $job) {
            $this->createCompletedBooking($job, $customers, $services, $technician);
        }

        $this->command?->info('HistoryBookingSeeder selesai: ' . count($jobs) . ' booking completed dibuat.');
    }

    private function createCompletedBooking(
        array $job,
        $customers,
        $services,
        User $technician
    ): void {
        $customer = $customers[$job['customer']];
        $now = Carbon::now();
        $completedAt = $now->copy()->subDays($job['days_ago']);

        $location = MaintenanceLocation::firstOrCreate(
            [
                'user_id' => $customer->id,
                'location_name' => $job['location_name'],
            ],
            [
                'address' => $job['address'],
                'ipal_type' => 'Biofilter',
                'capacity' => '5 m3/hari',
                'installation_type' => 'tanam',
            ]
        );

        $booking = Booking::create([
            'user_id' => $customer->id,
            'location_id' => $location->id,
            'booking_code' => 'JOB-' . $completedAt->format('Y') . '-' . Str::padLeft((string) (Booking::count() + 1), 3, '0'),
            'booking_date' => $completedAt->toDateString(),
            'booking_time' => $completedAt->format('H:i:s'),
            'status' => 'completed',
            'survey_status' => 'done',
            'payment_status' => 'paid',
            'complaint' => $job['complaint'],
            'estimated_total_price' => $job['final_price'],
            'final_total_price' => $job['final_price'],
            'survey_scheduled_at' => $completedAt->copy()->subDays(2),
            'approved_at' => $completedAt->copy()->subDay(),
            'started_at' => $completedAt->copy()->subHours(3),
            'completed_at' => $completedAt,
        ]);

        foreach ($job['services'] as $serviceIndex) {
            $service = $services[$serviceIndex];
            BookingDetail::create([
                'booking_id' => $booking->id,
                'service_id' => $service->id,
                'price' => $service->base_price,
                'qty' => 1,
                'subtotal' => $service->base_price,
            ]);
        }

        // Survei selalu ada (semua job pernah melalui tahap survei)
        SurveyResult::create([
            'booking_id' => $booking->id,
            'technician_id' => $technician->id,
            'inspection_result' => 'Kondisi lapangan sesuai keluhan customer.',
            'problem_summary' => $job['complaint'],
            'recommended_action' => $job['action'] ?? 'Survei rutin, tidak ada tindakan lanjutan.',
            'estimated_duration' => 120,
            'service_cost' => $job['final_price'] * 0.6,
            'sparepart_cost' => $job['final_price'] * 0.3,
            'other_cost' => $job['final_price'] * 0.1,
            'estimated_total_cost' => $job['final_price'],
            'status' => 'approved',
            'submitted_at' => $completedAt->copy()->subDays(2),
            'approved_at' => $completedAt->copy()->subDay(),
        ]);

        // Maintenance report hanya untuk job non-survei-murni
        if (empty($job['is_survey_only'])) {
            MaintenanceReport::create([
                'booking_id' => $booking->id,
                'technician_id' => $technician->id,
                'report' => $job['report'],
                'before_condition' => $job['before'],
                'after_condition' => $job['after'],
                'action_taken' => $job['action'],
                'recommendation' => 'Lakukan pengecekan rutin setiap 3 bulan.',
                'condition' => 'good',
                'work_duration' => 150,
                'reported_at' => $completedAt,
            ]);
        }

        Review::create([
            'booking_id' => $booking->id,
            'user_id' => $customer->id,
            'technician_id' => $technician->id,
            'rating' => $job['rating'],
            'review' => $job['review'],
        ]);
    }
}

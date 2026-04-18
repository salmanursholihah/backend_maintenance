<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\BookingDetail;
use App\Models\BookingProgress;
use App\Models\BookingTechnician;
use App\Models\MaintenanceHistory;
use App\Models\MaintenanceLocation;
use App\Models\MaintenanceReport;
use App\Models\Payment;
use App\Models\ReportPhoto;
use App\Models\Review;
use App\Models\Service;
use App\Models\SurveyResult;
use App\Models\SurveyResultItem;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $customer  = User::where('email', 'customer@ipal.test')->first();
        $teknisi1  = User::where('email', 'teknisi1@ipal.test')->first();
        $teknisi2  = User::where('email', 'teknisi2@ipal.test')->first();
        $services  = Service::all();
        $locations = MaintenanceLocation::where('user_id', $customer->id)->get();
 
        if (!$customer || !$teknisi1 || $locations->isEmpty()) return;
 
        // Ekstrak sebagai integer murni — TIDAK boleh pakai object di dalam closure
        $custId  = (int) $customer->id;
        $tech1Id = (int) $teknisi1->id;
        $tech2Id = $teknisi2 ? (int) $teknisi2->id : (int) $teknisi1->id;
 
        $loc1 = $locations->first();
        $loc2 = $locations->count() > 1 ? $locations->get(1) : $loc1;
 
        DB::transaction(function () use ($custId, $tech1Id, $tech2Id, $services, $loc1, $loc2) {
 
            // ════════════════════════════════════════════════════════
            // SKENARIO 1: Booking baru — menunggu assign teknisi
            // ════════════════════════════════════════════════════════
            $booking1 = Booking::create([
                'user_id'        => $custId,
                'location_id'    => $loc1->id,
                'booking_code'   => 'BK-DEMO0001',
                'booking_date'   => now()->addDays(2)->format('Y-m-d'),
                'booking_time'   => '09:00',
                'status'         => Booking::STATUS_WAITING_TECHNICIAN,
                'payment_status' => Booking::PAYMENT_UNPAID,
                'complaint'      => 'Filter IPAL tersumbat, air efluen berbau tidak sedap.',
            ]);
            $this->addDetail($booking1, $services, ['Pembersihan Filter IPAL', 'Inspeksi Pipa & Valve']);
 
            // ════════════════════════════════════════════════════════
            // SKENARIO 2: Sudah di-assign, menunggu survey
            // ════════════════════════════════════════════════════════
            $booking2 = Booking::create([
                'user_id'             => $custId,
                'location_id'         => $loc1->id,
                'booking_code'        => 'BK-DEMO0002',
                'booking_date'        => now()->format('Y-m-d'),
                'booking_time'        => '10:00',
                'status'              => Booking::STATUS_SURVEY_SCHEDULED,
                'payment_status'      => Booking::PAYMENT_UNPAID,
                'complaint'           => 'Pompa aerasi berbunyi aneh dan kinerja menurun drastis.',
                'survey_scheduled_at' => now()->addHours(2),
            ]);
            $this->addDetail($booking2, $services, ['Penggantian Pompa Aerasi']);
            BookingTechnician::create([
                'booking_id'    => $booking2->id,
                'technician_id' => $tech1Id,   // ✅ integer
                'is_lead'       => true,
                'status'        => BookingTechnician::STATUS_ACCEPTED,
                'assigned_at'   => now()->subHour(),
                'responded_at'  => now()->subMinutes(30),
            ]);
 
            // ════════════════════════════════════════════════════════
            // SKENARIO 3: Survey selesai — menunggu approval estimasi
            // ════════════════════════════════════════════════════════
            $estimasiTotal = 1650000;
            $booking3 = Booking::create([
                'user_id'               => $custId,
                'location_id'           => $loc2->id,
                'booking_code'          => 'BK-DEMO0003',
                'booking_date'          => now()->subDay()->format('Y-m-d'),
                'booking_time'          => '08:00',
                'status'                => Booking::STATUS_WAITING_ESTIMATION_APPROVAL,
                'payment_status'        => Booking::PAYMENT_UNPAID,
                'complaint'             => 'Biofilter tidak bekerja optimal, kadar BOD efluen tinggi.',
                'estimated_total_price' => $estimasiTotal,
                'survey_scheduled_at'   => now()->subDay(),
            ]);
            $this->addDetail($booking3, $services, ['Penggantian Media Biofilter', 'Desinfeksi Unit IPAL']);
            BookingTechnician::create([
                'booking_id'    => $booking3->id,
                'technician_id' => $tech1Id,   // ✅ integer
                'is_lead'       => true,
                'status'        => BookingTechnician::STATUS_WORKING,
                'assigned_at'   => now()->subDays(2),
                'responded_at'  => now()->subDays(2),
            ]);
            $survey3 = SurveyResult::create([
                'booking_id'           => $booking3->id,
                'technician_id'        => $tech1Id,   // ✅ integer
                'inspection_result'    => 'Media biofilter sudah jenuh dan mengandung lumpur berlebih. Diffuser membran 4 unit pecah sehingga aerasi tidak merata.',
                'problem_summary'      => 'Media biofilter perlu diganti 50%, 4 diffuser rusak, pompa aerasi masih baik.',
                'recommended_action'   => 'Ganti media bioball 100 liter, ganti 4 diffuser membran, dan desinfeksi menyeluruh.',
                'estimated_duration'   => 240,
                'service_cost'         => 700000,
                'sparepart_cost'       => 680000,
                'other_cost'           => 270000,
                'estimated_total_cost' => $estimasiTotal,
                'status'               => SurveyResult::STATUS_SUBMITTED,
                'submitted_at'         => now()->subHours(4),
            ]);
            SurveyResultItem::create([
                'survey_result_id' => $survey3->id,
                'type'             => 'material',
                'name'             => 'Media Bio-Ball',
                'qty'              => 100,
                'unit'             => 'liter',
                'price'            => 25000,
                'subtotal'         => 2500000,
            ]);
            SurveyResultItem::create([
                'survey_result_id' => $survey3->id,
                'type'             => 'sparepart',
                'name'             => 'Diffuser Membran',
                'qty'              => 4,
                'unit'             => 'pcs',
                'price'            => 85000,
                'subtotal'         => 340000,
            ]);
 
            // ════════════════════════════════════════════════════════
            // SKENARIO 4: Maintenance sedang berjalan
            // ════════════════════════════════════════════════════════
            $hargaOnProgress = 1200000;
            $booking4 = Booking::create([
                'user_id'               => $custId,
                'location_id'           => $loc1->id,
                'booking_code'          => 'BK-DEMO0004',
                'booking_date'          => now()->subDays(2)->format('Y-m-d'),
                'booking_time'          => '07:30',
                'status'                => Booking::STATUS_MAINTENANCE_ON_PROGRESS,
                'payment_status'        => Booking::PAYMENT_UNPAID,
                'complaint'             => 'Sensor pH tidak akurat, pembacaan selalu error.',
                'estimated_total_price' => $hargaOnProgress,
                'survey_scheduled_at'   => now()->subDays(3),
                'approved_at'           => now()->subDays(2),
                'started_at'            => now()->subHours(3),
            ]);
            $this->addDetail($booking4, $services, ['Kalibrasi & Perbaikan Sensor pH']);
            BookingTechnician::create([
                'booking_id'    => $booking4->id,
                'technician_id' => $tech2Id,   // ✅ integer
                'is_lead'       => true,
                'status'        => BookingTechnician::STATUS_WORKING,
                'assigned_at'   => now()->subDays(3),
                'responded_at'  => now()->subDays(3),
            ]);
            SurveyResult::create([
                'booking_id'           => $booking4->id,
                'technician_id'        => $tech2Id,   // ✅ integer
                'inspection_result'    => 'Elektroda sensor pH sudah aus dan perlu diganti.',
                'problem_summary'      => 'Elektroda aus, perlu kalibrasi ulang setelah penggantian.',
                'recommended_action'   => 'Ganti elektroda pH dan kalibrasi dengan buffer solution.',
                'estimated_duration'   => 90,
                'service_cost'         => 300000,
                'sparepart_cost'       => 350000,
                'other_cost'           => 50000,
                'estimated_total_cost' => $hargaOnProgress,
                'status'               => SurveyResult::STATUS_APPROVED,
                'submitted_at'         => now()->subDays(2),
                'approved_at'          => now()->subDays(2)->addHours(2),
            ]);
            BookingProgress::create([
                'booking_id'       => $booking4->id,
                'technician_id'    => $tech2Id,   // ✅ integer
                'title'            => 'Tiba di lokasi & pemeriksaan awal',
                'description'      => 'Tiba di lokasi pukul 07:30. Pemeriksaan sensor pH menunjukkan elektroda aus.',
                'progress_percent' => 30,
                'progress_at'      => now()->subHours(3),
            ]);
            BookingProgress::create([
                'booking_id'       => $booking4->id,
                'technician_id'    => $tech2Id,   // ✅ integer
                'title'            => 'Penggantian elektroda sensor pH',
                'description'      => 'Elektroda lama dilepas dan dipasang elektroda baru. Proses kalibrasi dengan buffer pH 4 dan pH 7 sedang berjalan.',
                'progress_percent' => 60,
                'progress_at'      => now()->subHours(1),
            ]);
 
            // ════════════════════════════════════════════════════════
            // SKENARIO 5: Selesai + payment + review
            // ════════════════════════════════════════════════════════
            $hargaSelesai = 1850000;
            $booking5 = Booking::create([
                'user_id'               => $custId,
                'location_id'           => $loc1->id,
                'booking_code'          => 'BK-DEMO0005',
                'booking_date'          => now()->subWeek()->format('Y-m-d'),
                'booking_time'          => '08:00',
                'status'                => Booking::STATUS_COMPLETED,
                'payment_status'        => Booking::PAYMENT_PAID,
                'complaint'             => 'Lumpur menumpuk di tangki sedimentasi, kinerja IPAL menurun.',
                'estimated_total_price' => $hargaSelesai,
                'final_total_price'     => $hargaSelesai,
                'survey_scheduled_at'   => now()->subDays(8),
                'approved_at'           => now()->subDays(7),
                'started_at'            => now()->subDays(6),
                'completed_at'          => now()->subDays(5),
            ]);
            $this->addDetail($booking5, $services, ['Pengurasan Lumpur (Sludge)', 'Pembersihan Filter IPAL']);
            BookingTechnician::create([
                'booking_id'    => $booking5->id,
                'technician_id' => $tech1Id,   // ✅ integer
                'is_lead'       => true,
                'status'        => BookingTechnician::STATUS_COMPLETED,
                'assigned_at'   => now()->subDays(8),
                'responded_at'  => now()->subDays(8),
            ]);
            SurveyResult::create([
                'booking_id'           => $booking5->id,
                'technician_id'        => $tech1Id,   // ✅ integer
                'inspection_result'    => 'Tangki sedimentasi 80% penuh lumpur. Filter biologis juga perlu dibersihkan.',
                'problem_summary'      => 'Akumulasi lumpur berlebih dan filter kotor.',
                'recommended_action'   => 'Pengurasan lumpur dan pembersihan filter secara bersamaan.',
                'estimated_duration'   => 360,
                'service_cost'         => 1000000,
                'sparepart_cost'       => 600000,
                'other_cost'           => 250000,
                'estimated_total_cost' => $hargaSelesai,
                'status'               => SurveyResult::STATUS_APPROVED,
                'submitted_at'         => now()->subDays(7),
                'approved_at'          => now()->subDays(7)->addHours(3),
            ]);
            BookingProgress::create([
                'booking_id'       => $booking5->id,
                'technician_id'    => $tech1Id,   // ✅ integer
                'title'            => 'Pengurasan lumpur tangki sedimentasi',
                'description'      => 'Pompa sedot lumpur terpasang, proses pengurasan berjalan lancar.',
                'progress_percent' => 40,
                'progress_at'      => now()->subDays(6)->addHours(2),
            ]);
            BookingProgress::create([
                'booking_id'       => $booking5->id,
                'technician_id'    => $tech1Id,   // ✅ integer
                'title'            => 'Pembersihan filter selesai',
                'description'      => 'Filter biologis dan fisik sudah dibersihkan. Sistem berjalan normal.',
                'progress_percent' => 100,
                'progress_at'      => now()->subDays(6)->addHours(5),
            ]);
            $report5 = MaintenanceReport::create([
                'booking_id'       => $booking5->id,
                'technician_id'    => $tech1Id,   // ✅ integer
                'report'           => 'Maintenance selesai dilaksanakan. Pengurasan lumpur berhasil, volume ±2m³. Filter dibersihkan. Sistem IPAL berjalan normal kembali.',
                'before_condition' => 'Tangki sedimentasi 80% penuh lumpur hitam. Filter tersumbat menyebabkan penurunan debit aliran 60%.',
                'after_condition'  => 'Tangki kosong dan bersih. Filter normal, debit aliran kembali ke spesifikasi.',
                'action_taken'     => 'Pengurasan lumpur dengan pompa sedot, pembersihan filter dengan air bertekanan, desinfeksi ringan.',
                'recommendation'   => 'Jadwalkan maintenance rutin setiap 60 hari. Pasang flow meter untuk monitoring.',
                'condition'        => MaintenanceReport::CONDITION_GOOD,
                'work_duration'    => 330,
                'reported_at'      => now()->subDays(5),
            ]);
            ReportPhoto::create([
                'report_id' => $report5->id,
                'photo'     => 'images/report_photos/dummy_before.jpg',
                'type'      => 'before',
                'caption'   => 'Kondisi tangki sebelum pengurasan',
            ]);
            ReportPhoto::create([
                'report_id' => $report5->id,
                'photo'     => 'images/report_photos/dummy_after.jpg',
                'type'      => 'after',
                'caption'   => 'Kondisi tangki setelah pengurasan',
            ]);
            Payment::create([
                'booking_id'     => $booking5->id,
                'order_id'       => 'IPAL-BK-DEMO0005-' . time(),
                'amount'         => $hargaSelesai,
                'status'         => Payment::STATUS_PAID,
                'payment_type'   => 'bank_transfer',
                'transaction_id' => 'TXN-' . strtoupper(uniqid()),
                'paid_at'        => now()->subDays(4),
                'expired_at'     => now()->subDays(4)->addHours(24),
            ]);
            Review::create([
                'booking_id'    => $booking5->id,
                'user_id'       => $custId,    // ✅ integer
                'technician_id' => $tech1Id,   // ✅ integer
                'rating'        => 5,
                'review'        => 'Teknisi sangat profesional, tepat waktu, dan teliti. IPAL kami kembali berfungsi dengan baik!',
            ]);
            MaintenanceHistory::create([
                'location_id'      => $loc1->id,
                'booking_id'       => $booking5->id,
                'maintenance_date' => now()->subDays(5)->format('Y-m-d'),
                'summary'          => 'Pengurasan lumpur dan pembersihan filter. Sistem kembali normal.',
                'condition_result' => MaintenanceReport::CONDITION_GOOD,
            ]);
            $loc1->update([
                'next_maintenance_date' => now()->addDays($loc1->maintenance_interval_days)->format('Y-m-d'),
            ]);
 
            // ════════════════════════════════════════════════════════
            // SKENARIO 6: Booking dibatalkan
            // ════════════════════════════════════════════════════════
            Booking::create([
                'user_id'        => $custId,
                'location_id'    => $loc2->id,
                'booking_code'   => 'BK-DEMO0006',
                'booking_date'   => now()->subDays(3)->format('Y-m-d'),
                'booking_time'   => '13:00',
                'status'         => Booking::STATUS_CANCELLED,
                'payment_status' => Booking::PAYMENT_UNPAID,
                'complaint'      => 'Perlu pengecekan sensor DO.',
                'cancel_reason'  => 'Dibatalkan oleh customer karena ada keperluan mendadak.',
                'cancelled_at'   => now()->subDays(3),
            ]);
        });
    }
 
    // ── Helper ────────────────────────────────────────────────────
    private function addDetail(Booking $booking, $services, array $serviceNames): void
    {
        foreach ($serviceNames as $name) {
            $service = $services->firstWhere('name', $name);
            if (!$service) continue;
 
            BookingDetail::create([
                'booking_id' => $booking->id,
                'service_id' => $service->id,
                'price'      => $service->base_price,
                'qty'        => 1,
                'subtotal'   => $service->base_price,
            ]);
        }
    }
}

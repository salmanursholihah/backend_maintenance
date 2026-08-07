<?php

namespace App\Http\Controllers\Api\Technician;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingTechnician;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\NotificationService;

class TechnicianBookingController extends Controller
{
    public function incoming(Request $request)
    {
        $bookings = Booking::with(['location', 'user', 'details.service'])
            ->where('status', 'waiting_technician')
            ->latest()
            ->get();

        return response()->json(['data' => $bookings]);
    }

    public function schedules(Request $request)
    {
        $bookings = Booking::with(['location', 'user'])
            ->whereHas('technicians', function ($q) use ($request) {
                $q->where('technician_id', $request->user()->id)
                  ->whereIn('status', ['accepted', 'working']);
            })
            ->whereIn('survey_status', ['accepted', 'scheduled', 'done'])
            ->latest()
            ->get();

        return response()->json(['data' => $bookings]);
    }

    public function active(Request $request)
    {
        $bookings = Booking::with(['location', 'user'])
            ->whereHas('technicians', function ($q) use ($request) {
                $q->where('technician_id', $request->user()->id);
            })
            ->whereIn('status', ['maintenance_pending', 'maintenance_on_progress'])
            ->latest()
            ->get();

        return response()->json(['data' => $bookings]);
    }

    public function history(Request $request)
    {
        $bookings = Booking::with(['location', 'user'])
            ->whereHas('technicians', function ($q) use ($request) {
                $q->where('technician_id', $request->user()->id);
            })
            ->whereIn('status', ['completed', 'cancelled', 'estimation_rejected'])
            ->latest()
            ->get();

        return response()->json(['data' => $bookings]);
    }

    public function show(Request $request, $id)
    {
        $booking = Booking::with([
                'location',
                'user',
                'details.service',
                'surveyResult.items',
                'progresses',
                'report.photos',
            ])
            ->findOrFail($id);

        return response()->json(['data' => $booking]);
    }

    /**
     * Teknisi menerima booking survei -> status pindah ke
     * survey_on_progress, booking_technicians jadi 'accepted', dan
     * customer dikirimi notifikasi.
     */
    // public function accept(Request $request, $id)
    // {
    //     $booking = Booking::findOrFail($id);

    //     DB::transaction(function () use ($booking, $request) {
    //         BookingTechnician::updateOrCreate(
    //             [
    //                 'booking_id' => $booking->id,
    //                 'technician_id' => $request->user()->id,
    //             ],
    //             [
    //                 'status' => 'accepted',
    //                 'assigned_at' => now(),
    //                 'responded_at' => now(),
    //             ]
    //         );

    //         $booking->update([
    //             'status' => 'survey_on_progress',
    //             'survey_status' => 'accepted',
    //         ]);
    //     });

    //     NotificationService::send(
    //         userId: $booking->user_id,
    //         title: 'Survei Diterima Teknisi',
    //         message: 'Teknisi telah menerima survei untuk booking ' . $booking->booking_code . '. Survei akan segera dilakukan.',
    //         type: 'survey',
    //         bookingId: $booking->id,
    //     );

    //     return response()->json([
    //         'message' => 'Survei berhasil diterima.',
    //         'data' => $booking->fresh(['location', 'user', 'details.service']),
    //     ]);
    // }


    public function accept(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        DB::transaction(function () use ($booking, $request) {
            BookingTechnician::updateOrCreate(
                [
                    'booking_id' => $booking->id,
                    'technician_id' => $request->user()->id,
                ],
                [
                    'status' => 'accepted',
                    'assigned_at' => now(),
                    'responded_at' => now(),
                ]
            );

            // DIUBAH: berhenti di survey_scheduled dulu, TIDAK langsung
            // survey_on_progress. Customer harus setuju biaya survei dulu.
            $booking->update([
                'status' => 'survey_scheduled',
                'survey_status' => 'accepted',
            ]);
        });

        NotificationService::send(
            userId: $booking->user_id,
            title: 'Survei Diterima Teknisi',
            message: 'Teknisi telah menerima survei untuk booking ' . $booking->booking_code . '. Mohon konfirmasi persetujuan biaya survei untuk melanjutkan.',
            type: 'survey_approval', // DIUBAH dari 'survey' -> tipe baru khusus, supaya Flutter bisa bedain notif ini dari notif survei lain dan munculkan tombol "Setuju"
            bookingId: $booking->id,
        );

        return response()->json([
            'message' => 'Survei berhasil diterima.',
            'data' => $booking->fresh(['location', 'user', 'details.service']),
        ]);
    }

    public function reject(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        $data = $request->validate([
            'note' => ['nullable', 'string'],
        ]);

        BookingTechnician::updateOrCreate(
            [
                'booking_id' => $booking->id,
                'technician_id' => $request->user()->id,
            ],
            [
                'status' => 'rejected',
                'note' => $data['note'] ?? null,
                'assigned_at' => now(),
                'responded_at' => now(),
            ]
        );

        NotificationService::send(
            userId: $booking->user_id,
            title: 'Booking Ditolak Teknisi',
            message: $data['note'] ?? ('Booking ' . $booking->booking_code . ' ditolak oleh teknisi.'),
            type: 'booking',
            bookingId: $booking->id,
        );

        return response()->json([
            'message' => 'Booking survei ditolak',
        ]);
    }

    public function scheduleSurvey(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        $data = $request->validate([
            'survey_scheduled_at' => ['required', 'date'],
        ]);

        $booking->update([
            'status' => 'survey_scheduled',
            'survey_status' => 'scheduled',
            'survey_scheduled_at' => $data['survey_scheduled_at'],
        ]);

        NotificationService::send(
            userId: $booking->user_id,
            title: 'Jadwal Survei Ditentukan',
            message: 'Survei untuk booking ' . $booking->booking_code . ' dijadwalkan pada ' . $data['survey_scheduled_at'] . '.',
            type: 'survey',
            bookingId: $booking->id,
        );

        return response()->json([
            'message' => 'Jadwal survei berhasil ditentukan',
            'data' => $booking,
        ]);
    }


    /**
     * Partner menandai survei sedang berlangsung (sudah di lokasi/mulai
     * kerja). booking.status tetap survey_on_progress, cuma survey_status
     * yang berubah -> menentukan tombol apa yang muncul di Detail Booking.
     */
    public function startSurvey(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        if ($booking->status !== 'survey_scheduled' || $booking->survey_status !== 'customer_approved') {
            return response()->json([
                'message' => 'Booking belum disetujui customer untuk memulai survei.',
            ], 422);
        }

        $booking->update([
            'status' => 'survey_on_progress',
            'survey_status' => 'in_progress',
        ]);

        NotificationService::send(
            userId: $booking->user_id,
            title: 'Survei Sedang Berlangsung',
            message: 'Teknisi sedang melakukan survei untuk booking ' . $booking->booking_code . '.',
            type: 'survey',
            bookingId: $booking->id,
        );

        return response()->json([
            'message' => 'Survei ditandai sedang berlangsung.',
            'data' => $booking->fresh(),
        ]);
    }


    public function startMaintenance(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        // NOTE: nilai 'paid' adalah ASUMSI berdasarkan default 'unpaid' di
        // CustomerBookingController::store(). Perlu diverifikasi ulang begitu
        // MidtransWebhookController dikirim — sesuaikan string ini kalau
        // webhook ternyata memakai nilai lain (mis. 'settlement').
        if ($booking->payment_status !== 'paid') {
            return response()->json([
                'message' => 'Maintenance belum bisa dimulai karena pembayaran belum diselesaikan customer.',
            ], 422);
        }

        $booking->update([
            'status' => 'maintenance_on_progress',
            'started_at' => now(),
        ]);

        BookingTechnician::where('booking_id', $booking->id)
            ->where('technician_id', $request->user()->id)
            ->update([
                'status' => 'working',
            ]);

        NotificationService::send(
            userId: $booking->user_id,
            title: 'Maintenance Dimulai',
            message: 'Teknisi mulai mengerjakan maintenance untuk booking ' . $booking->booking_code . '.',
            type: 'progress',
            bookingId: $booking->id,
        );

        return response()->json([
            'message' => 'Maintenance dimulai',
        ]);
    }
    public function complete(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        $booking->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        BookingTechnician::where('booking_id', $booking->id)
            ->where('technician_id', $request->user()->id)
            ->update([
                'status' => 'completed',
            ]);

        NotificationService::send(
            userId: $booking->user_id,
            title: 'Pekerjaan Selesai',
            message: 'Maintenance untuk booking ' . $booking->booking_code . ' telah selesai. Jangan lupa beri ulasan.',
            type: 'progress',
            bookingId: $booking->id,
        );

        return response()->json([
            'message' => 'Pekerjaan selesai',
        ]);
    }

    // app/Http/Controllers/Api/Technician/TechnicianBookingController.php
    public function submitEstimate(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        if ($booking->status !== 'survey_on_progress' || $booking->survey_status !== 'in_progress') {
            return response()->json([
                'message' => 'Booking tidak dalam status survei berjalan.',
            ], 422);
        }

        $validated = $request->validate([
            'estimated_duration' => 'nullable|integer',
            'service_cost' => 'required|numeric',
            'sparepart_cost' => 'nullable|numeric',
            'other_cost' => 'nullable|numeric',
            'notes' => 'nullable|string',
        ]);

        // simpan ke tabel survey_results (atau kolom di bookings, tergantung skema)
        $booking->surveyResult()->updateOrCreate([], [
            'estimated_duration' => $validated['estimated_duration'] ?? null,
            'service_cost' => $validated['service_cost'],
            'sparepart_cost' => $validated['sparepart_cost'] ?? 0,
            'other_cost' => $validated['other_cost'] ?? 0,
            'notes' => $validated['notes'] ?? null,
        ]);

        $booking->update(['status' => 'waiting_estimation_approval']);

        return response()->json(['message' => 'Estimasi berhasil dikirim ke customer.']);
    }
}

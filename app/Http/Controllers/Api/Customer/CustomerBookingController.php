<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingDetail;
use App\Models\MaintenanceHistory;
use App\Models\MaintenanceLocation;
use App\Models\MaintenanceReport;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Services\NotificationService;

class CustomerBookingController extends Controller
{
    public function services()
    {
        $services = Service::where('is_active', true)->latest()->get();

        return response()->json(['data' => $services]);
    }

    public function index(Request $request)
    {
        $bookings = Booking::with(['location', 'details.service'])
            ->where('user_id', $request->user()->id)
            ->latest()
            ->get();

        return response()->json(['data' => $bookings]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'location_id' => ['required', 'exists:maintenance_locations,id'],
            'booking_date' => ['required', 'date'],
            'booking_time' => ['required'],
            'complaint' => ['nullable', 'string'],
            'customer_note' => ['nullable', 'string'],
            'services' => ['required', 'array', 'min:1'],
            'services.*.service_id' => ['required', 'exists:services,id'],
            'services.*.qty' => ['required', 'integer', 'min:1'],
        ]);

        $location = MaintenanceLocation::where('id', $data['location_id'])
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        DB::beginTransaction();

        try {
            $booking = Booking::create([
                'user_id' => $request->user()->id,
                'location_id' => $location->id,
                'booking_code' => 'BK-' . now()->format('YmdHis') . '-' . Str::upper(Str::random(4)),
                'booking_date' => $data['booking_date'],
                'booking_time' => $data['booking_time'],
                'complaint' => $data['complaint'] ?? null,
                'customer_note' => $data['customer_note'] ?? null,
                'status' => 'waiting_technician',
                'survey_status' => 'pending',
                'payment_status' => 'unpaid',
            ]);

            $estimated = 0;

            foreach ($data['services'] as $item) {
                $service = Service::findOrFail($item['service_id']);
                $subtotal = $service->base_price * $item['qty'];
                $estimated += $subtotal;

                BookingDetail::create([
                    'booking_id' => $booking->id,
                    'service_id' => $service->id,
                    'price' => $service->base_price,
                    'qty' => $item['qty'],
                    'subtotal' => $subtotal,
                ]);
            }

            $booking->update([
                'estimated_total_price' => $estimated,
            ]);

            DB::commit();

            // Broadcast notifikasi booking baru ke semua technician aktif
            $technicianIds = User::where('role', 'technician')
                ->where('is_active', true)
                ->pluck('id');

            foreach ($technicianIds as $technicianId) {
                NotificationService::send(
                    userId: $technicianId,
                    title: 'Booking Survei Baru',
                    message: 'Ada booking baru ' . $booking->booking_code . ' menunggu teknisi untuk survei.',
                    type: 'booking',
                    bookingId: $booking->id,
                );
            }

            return response()->json([
                'message' => 'Booking survei berhasil dibuat',
                'data' => $booking->load(['location', 'details.service']),
            ], 201);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function show(Request $request, $id)
    {
        $booking = Booking::with([
            'location',
            'details.service',
            'technicians',
            'surveyResult.items',
            'progresses.technician',
            'report.photos',
            // 'review', // TODO: aktifkan setelah relasi review() ditambahkan ke model Booking
        ])
            ->where('user_id', $request->user()->id)
            ->findOrFail($id);

        return response()->json(['data' => $booking]);
    }

    public function cancel(Request $request, $id)
    {
        $booking = Booking::where('user_id', $request->user()->id)->findOrFail($id);

        $data = $request->validate([
            'cancel_reason' => ['required', 'string'],
        ]);

        if (in_array($booking->status, ['maintenance_on_progress', 'completed', 'cancelled'])) {
            return response()->json([
                'message' => 'Booking tidak dapat dibatalkan',
            ], 422);
        }

        $booking->update([
            'status' => 'cancelled',
            'cancel_reason' => $data['cancel_reason'],
            'cancelled_at' => now(),
        ]);

        // Beri tahu teknisi yang sudah di-assign, kalau ada.
        $technicianIds = $booking->bookingTechnicians()->pluck('technician_id');
        foreach ($technicianIds as $technicianId) {
            NotificationService::send(
                userId: $technicianId,
                title: 'Booking Dibatalkan Customer',
                message: 'Booking ' . $booking->booking_code . ' dibatalkan oleh customer. Alasan: ' . $data['cancel_reason'],
                type: 'booking',
                bookingId: $booking->id,
            );
        }

        return response()->json([
            'message' => 'Booking berhasil dibatalkan',
            'data' => $booking,
        ]);
    }

    public function progresses(Request $request, $id)
    {
        $booking = Booking::where('user_id', $request->user()->id)->findOrFail($id);

        $data = $booking->progresses()->with('technician')->latest()->get();

        return response()->json(['data' => $data]);
    }

    public function report(Request $request, $id)
    {
        $booking = Booking::where('user_id', $request->user()->id)->findOrFail($id);

        $report = MaintenanceReport::with('photos')
            ->where('booking_id', $booking->id)
            ->first();

        return response()->json(['data' => $report]);
    }

    public function history(Request $request, $id)
    {
        $booking = Booking::where('user_id', $request->user()->id)->findOrFail($id);

        $history = MaintenanceHistory::where('booking_id', $booking->id)->latest()->get();

        return response()->json(['data' => $history]);
    }


    /**
     * Customer menyetujui biaya survei -> booking lanjut ke survey_on_progress,
     * teknisi diberitahu untuk mulai survei.
     */
    // public function approveSurvey(Request $request, $id)
    // {
    //     $booking = Booking::where('user_id', $request->user()->id)->findOrFail($id);

    //     if ($booking->status !== 'survey_scheduled') {
    //         return response()->json([
    //             'message' => 'Booking tidak dalam status menunggu persetujuan survei.',
    //         ], 422);
    //     }

    //     $booking->update([
    //         'status' => 'survey_on_progress',
    //     ]);

    //     $technicianIds = $booking->bookingTechnicians()
    //         ->where('status', 'accepted')
    //         ->pluck('technician_id');

    //     foreach ($technicianIds as $technicianId) {
    //         NotificationService::send(
    //             userId: $technicianId,
    //             title: 'Biaya Survei Disetujui',
    //             message: 'Customer telah menyetujui biaya survei untuk booking ' . $booking->booking_code . '. Silakan lakukan survei dan kirim hasil estimasi.',
    //             type: 'survey',
    //             bookingId: $booking->id,
    //         );
    //     }

    //     return response()->json([
    //         'message' => 'Survei disetujui, teknisi akan segera melakukan survei.',
    //         'data' => $booking->fresh(),
    //     ]);
    // }


    public function approveSurvey(Request $request, $id)
    {
        $booking = Booking::where('user_id', $request->user()->id)->findOrFail($id);

        if ($booking->status !== 'survey_scheduled' || $booking->survey_status !== 'accepted') {
            return response()->json([
                'message' => 'Booking tidak dalam status menunggu persetujuan survei.',
            ], 422);
        }

        $booking->update([
            'survey_status' => 'customer_approved', // status TETAP survey_scheduled
        ]);

        $technicianIds = $booking->bookingTechnicians()
            ->where('status', 'accepted')
            ->pluck('technician_id');

        foreach ($technicianIds as $technicianId) {
            NotificationService::send(
                userId: $technicianId,
                title: 'Biaya Survei Disetujui',
                message: 'Customer telah menyetujui biaya survei untuk booking ' . $booking->booking_code . '. Silakan mulai survei.',
                type: 'survey',
                bookingId: $booking->id,
            );
        }

        return response()->json([
            'message' => 'Survei disetujui, silakan mulai survei.',
            'data' => $booking->fresh(),
        ]);
    }

    public function approveEstimation(Request $request, $id)
    {
        $booking = Booking::where('user_id', $request->user()->id)->findOrFail($id);

        if ($booking->status !== 'waiting_estimation_approval') {
            return response()->json([
                'message' => 'Booking tidak dalam status menunggu persetujuan estimasi.',
            ], 422);
        }

        $surveyResult = $booking->surveyResult;

        $booking->update([
            'status' => 'estimation_approved',
            'final_total_price' => $surveyResult?->estimated_total_cost ?? $booking->estimated_total_price,
            'approved_at' => now(),
        ]);

        if ($surveyResult) {
            $surveyResult->update(['approved_at' => now()]);
        }

        $technicianIds = $booking->bookingTechnicians()->where('status', 'accepted')->pluck('technician_id');
        foreach ($technicianIds as $technicianId) {
            NotificationService::send(
                userId: $technicianId,
                title: 'Estimasi Disetujui',
                message: 'Customer telah menyetujui estimasi biaya untuk booking ' . $booking->booking_code . '.',
                type: 'progress',
                bookingId: $booking->id,
            );
        }

        // DIUBAH: 'data' sekarang berisi info siap pakai untuk PaymentScreen,
        // bukan cuma object booking mentah.
        return response()->json([
            'message' => 'Estimasi disetujui.',
            'data' => [
                'booking_id' => $booking->id,
                'booking_code' => $booking->booking_code,
                'amount' => (float) $booking->final_total_price,
                'service_detail' => $surveyResult?->notes
                    ?? 'Estimasi hasil survei untuk booking ' . $booking->booking_code,
            ],
        ]);
    }
    public function rejectEstimation(Request $request, $id)
    {
        $booking = Booking::where('user_id', $request->user()->id)->findOrFail($id);

        $data = $request->validate([
            'reject_reason' => ['nullable', 'string'],
        ]);

        if ($booking->status !== 'waiting_estimation_approval') {
            return response()->json([
                'message' => 'Booking tidak dalam status menunggu persetujuan estimasi.',
            ], 422);
        }

        $booking->update(['status' => 'estimation_rejected']);
        $booking->surveyResult?->update(['rejected_at' => now()]);

        $technicianIds = $booking->bookingTechnicians()->where('status', 'accepted')->pluck('technician_id');
        foreach ($technicianIds as $technicianId) {
            NotificationService::send(
                userId: $technicianId,
                title: 'Estimasi Ditolak',
                message: 'Customer menolak estimasi untuk booking ' . $booking->booking_code . '. Alasan: ' . ($data['reject_reason'] ?? '-'),
                type: 'progress',
                bookingId: $booking->id,
            );
        }

        return response()->json(['message' => 'Estimasi ditolak.']);
    }
}

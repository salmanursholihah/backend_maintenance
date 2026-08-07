<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use App\Services\NotificationService;

class SurveyApprovalController extends Controller
{
    public function approve(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        // Pastikan yang approve memang pemilik booking
        if ($booking->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Tidak diizinkan.'], 403);
        }

        if ($booking->status !== 'survey_scheduled' || $booking->survey_status !== 'accepted') {
            return response()->json([
                'message' => 'Booking tidak dalam status menunggu persetujuan survei.',
            ], 422);
        }

        $booking->update([
            'survey_status' => 'customer_approved',
        ]);

        // Notif ke teknisi yang di-assign supaya tahu bisa mulai survei
        $technicianId = $booking->technicians()
            ->wherePivot('status', 'accepted')
            ->value('technician_id');

        if ($technicianId) {
            NotificationService::send(
                userId: $technicianId,
                title: 'Biaya Survei Disetujui',
                message: 'Customer menyetujui biaya survei untuk booking ' . $booking->booking_code . '. Silakan mulai survei.',
                type: 'survey',
                bookingId: $booking->id,
            );
        }

        return response()->json([
            'message' => 'Biaya survei disetujui.',
            'data' => $booking->fresh(),
        ]);
    }

    public function reject(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        if ($booking->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Tidak diizinkan.'], 403);
        }

        if ($booking->status !== 'survey_scheduled' || $booking->survey_status !== 'accepted') {
            return response()->json([
                'message' => 'Booking tidak dalam status menunggu persetujuan survei.',
            ], 422);
        }

        $booking->update([
            'status' => 'survey_rejected',
            'survey_status' => 'customer_rejected',
        ]);

        $technicianId = $booking->technicians()
            ->wherePivot('status', 'accepted')
            ->value('technician_id');

        if ($technicianId) {
            NotificationService::send(
                userId: $technicianId,
                title: 'Biaya Survei Ditolak',
                message: 'Customer menolak biaya survei untuk booking ' . $booking->booking_code . '.',
                type: 'survey',
                bookingId: $booking->id,
            );
        }

        return response()->json(['message' => 'Biaya survei ditolak.']);
    }
}

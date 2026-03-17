<?php

namespace App\Http\Controllers\Api\Technician;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingTechnician;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

            $booking->update([
                'survey_status' => 'accepted',
            ]);
        });

        return response()->json([
            'message' => 'Booking survei diterima',
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

        return response()->json([
            'message' => 'Jadwal survei berhasil ditentukan',
            'data' => $booking,
        ]);
    }

    public function startMaintenance(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        $booking->update([
            'status' => 'maintenance_on_progress',
            'started_at' => now(),
        ]);

        BookingTechnician::where('booking_id', $booking->id)
            ->where('technician_id', $request->user()->id)
            ->update([
                'status' => 'working',
            ]);

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

        return response()->json([
            'message' => 'Pekerjaan selesai',
        ]);
    }}

<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingDetail;
use App\Models\MaintenanceHistory;
use App\Models\MaintenanceLocation;
use App\Models\MaintenanceReport;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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
                'technicians.technician',
                'surveyResult.items',
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
    }

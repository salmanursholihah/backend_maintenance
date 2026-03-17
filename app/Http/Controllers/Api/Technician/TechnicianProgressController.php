<?php

namespace App\Http\Controllers\Api\Technician;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingProgress;
use Illuminate\Http\Request;

class TechnicianProgressController extends Controller
{
public function index(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        $progresses = BookingProgress::where('booking_id', $booking->id)
            ->with('technician')
            ->latest()
            ->get();

        return response()->json(['data' => $progresses]);
    }

    public function store(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'progress_percent' => ['required', 'integer', 'min:0', 'max:100'],
            'photo' => ['nullable', 'string'],
            'progress_at' => ['nullable', 'date'],
        ]);

        $progress = BookingProgress::create([
            'booking_id' => $booking->id,
            'technician_id' => $request->user()->id,
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'progress_percent' => $data['progress_percent'],
            'photo' => $data['photo'] ?? null,
            'progress_at' => $data['progress_at'] ?? now(),
        ]);

        $booking->update([
            'status' => 'maintenance_on_progress',
        ]);

        return response()->json([
            'message' => 'Progress berhasil ditambahkan',
            'data' => $progress,
        ], 201);
    }
}

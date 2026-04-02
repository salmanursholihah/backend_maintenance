<?php

namespace App\Http\Controllers\Web\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingTechnician;
use App\Models\User;
use Illuminate\Http\Request;

class BookingManagementController extends Controller
{
public function index()
    {
        $bookings = Booking::with(['user', 'location'])->latest()->paginate(20);
        return view('pages.bookings.index', compact('bookings'));
    }

    public function show($id)
    {
        $booking = Booking::with([
            'user',
            'location',
            'details.service',
            'surveyResult.items',
            'report.photos'
        ])->findOrFail($id);

        $technicians = User::where('role', 'technician')
            ->where('is_active', true)
            ->get();

        return view('pages.bookings.show', compact('booking', 'technicians'));
    }

    public function assignTechnician(Request $request, $id)
    {
        $request->validate([
            'technician_id' => 'required|exists:users,id'
        ]);

        BookingTechnician::updateOrCreate(
            [
                'booking_id' => $id,
                'technician_id' => $request->technician_id
            ],
            [
                'status' => 'accepted',
                'assigned_at' => now()
            ]
        );

        return back()->with('success', 'Teknisi berhasil di-assign');
    }
}

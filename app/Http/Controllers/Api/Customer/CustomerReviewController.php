<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingTechnician;
use App\Models\Review;
use Illuminate\Http\Request;

class CustomerReviewController extends Controller
{
public function store(Request $request, $id)
    {
        $booking = Booking::where('user_id', $request->user()->id)->findOrFail($id);

        $data = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'review' => ['nullable', 'string'],
        ]);

        $assigned = BookingTechnician::where('booking_id', $booking->id)
            ->whereIn('status', ['accepted', 'working', 'completed'])
            ->latest()
            ->firstOrFail();

        $review = Review::updateOrCreate(
            ['booking_id' => $booking->id],
            [
                'user_id' => $request->user()->id,
                'technician_id' => $assigned->technician_id,
                'rating' => $data['rating'],
                'review' => $data['review'] ?? null,
            ]
        );

        return response()->json([
            'message' => 'Review berhasil dikirim',
            'data' => $review,
        ]);
    }

    public function show(Request $request, $id)
    {
        $booking = Booking::where('user_id', $request->user()->id)->findOrFail($id);

        $review = Review::where('booking_id', $booking->id)->first();

        return response()->json(['data' => $review]);
    }
    }

<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\SurveyResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerEstimateController extends Controller
{
  public function show(Request $request, $id)
    {
        $booking = Booking::where('user_id', $request->user()->id)->findOrFail($id);

        $survey = SurveyResult::with('items')
            ->where('booking_id', $booking->id)
            ->first();

        return response()->json([
            'data' => [
                'booking' => $booking,
                'survey_result' => $survey,
            ],
        ]);
    }

    public function approve(Request $request, $id)
    {
        $booking = Booking::where('user_id', $request->user()->id)->findOrFail($id);

        $survey = SurveyResult::where('booking_id', $booking->id)
            ->where('status', 'submitted')
            ->firstOrFail();

        DB::transaction(function () use ($booking, $survey) {
            $survey->update([
                'status' => 'approved',
                'approved_at' => now(),
            ]);

            $booking->update([
                'status' => 'estimation_approved',
                'approved_at' => now(),
                'estimated_total_price' => $survey->estimated_total_cost,
                'final_total_price' => $survey->estimated_total_cost,
            ]);
        });

        return response()->json([
            'message' => 'Estimasi disetujui',
        ]);
    }

    public function reject(Request $request, $id)
    {
        $booking = Booking::where('user_id', $request->user()->id)->findOrFail($id);

        $survey = SurveyResult::where('booking_id', $booking->id)
            ->where('status', 'submitted')
            ->firstOrFail();

        DB::transaction(function () use ($booking, $survey) {
            $survey->update([
                'status' => 'rejected',
                'rejected_at' => now(),
            ]);

            $booking->update([
                'status' => 'estimation_rejected',
            ]);
        });

        return response()->json([
            'message' => 'Estimasi ditolak',
        ]);
    }

    public function postpone(Request $request, $id)
    {
        $booking = Booking::where('user_id', $request->user()->id)->findOrFail($id);

        return response()->json([
            'message' => 'Estimasi ditunda',
            'data' => $booking,
        ]);
    }
    }

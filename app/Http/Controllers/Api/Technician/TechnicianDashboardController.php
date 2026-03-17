<?php

namespace App\Http\Controllers\Api\Technician;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingTechnician;
use App\Models\Notification;
use App\Models\Payment;
use Illuminate\Http\Request;

class TechnicianDashboardController extends Controller
{
public function index(Request $request)
    {
        $user = $request->user();

        $incomingBookings = Booking::where('status', 'waiting_technician')->count();

        $acceptedSurveyCount = BookingTechnician::where('technician_id', $user->id)
            ->whereIn('status', ['accepted', 'working'])
            ->count();

        $activeOrders = Booking::whereHas('technicians', function ($query) use ($user) {
                $query->where('technician_id', $user->id)
                    ->whereIn('status', ['accepted', 'working']);
            })
            ->whereIn('status', [
                'survey_scheduled',
                'survey_on_progress',
                'waiting_estimation_approval',
                'estimation_approved',
                'maintenance_pending',
                'maintenance_on_progress',
            ])
            ->count();

        $completedOrders = Booking::whereHas('technicians', function ($query) use ($user) {
                $query->where('technician_id', $user->id)
                    ->where('status', 'completed');
            })
            ->where('status', 'completed')
            ->count();

        $unreadNotifications = Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->count();

        $thisMonthIncome = Payment::where('status', 'paid')
            ->whereHas('booking.technicians', function ($query) use ($user) {
                $query->where('technician_id', $user->id)
                    ->whereIn('status', ['working', 'completed']);
            })
            ->whereMonth('paid_at', now()->month)
            ->whereYear('paid_at', now()->year)
            ->sum('amount');

        $recentOrders = Booking::with(['location', 'user'])
            ->whereHas('technicians', function ($query) use ($user) {
                $query->where('technician_id', $user->id);
            })
            ->latest()
            ->take(5)
            ->get();

        return response()->json([
            'message' => 'Dashboard teknisi berhasil diambil',
            'data' => [
                'summary' => [
                    'incoming_bookings' => $incomingBookings,
                    'accepted_surveys' => $acceptedSurveyCount,
                    'active_orders' => $activeOrders,
                    'completed_orders' => $completedOrders,
                    'unread_notifications' => $unreadNotifications,
                    'this_month_income' => (float) $thisMonthIncome,
                ],
                'recent_orders' => $recentOrders,
            ],
        ]);
    }}

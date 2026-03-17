<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\MaintenanceLocation;
use App\Models\Notification;
use App\Models\Payment;
use Illuminate\Http\Request;

class CustomerDashboardController extends Controller
{
 public function index(Request $request)
    {
        $user = $request->user();

        $totalLocations = MaintenanceLocation::where('user_id', $user->id)->count();

        $totalBookings = Booking::where('user_id', $user->id)->count();

        $activeBookings = Booking::where('user_id', $user->id)
            ->whereIn('status', [
                'waiting_technician',
                'survey_scheduled',
                'survey_on_progress',
                'waiting_estimation_approval',
                'estimation_approved',
                'maintenance_pending',
                'maintenance_on_progress',
            ])
            ->count();

        $completedBookings = Booking::where('user_id', $user->id)
            ->where('status', 'completed')
            ->count();

        $pendingPayments = Payment::whereHas('booking', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->where('status', 'pending')
            ->count();

        $unreadNotifications = Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->count();

        $recentBookings = Booking::with(['location'])
            ->where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        return response()->json([
            'message' => 'Dashboard customer berhasil diambil',
            'data' => [
                'summary' => [
                    'total_locations' => $totalLocations,
                    'total_bookings' => $totalBookings,
                    'active_bookings' => $activeBookings,
                    'completed_bookings' => $completedBookings,
                    'pending_payments' => $pendingPayments,
                    'unread_notifications' => $unreadNotifications,
                ],
                'recent_bookings' => $recentBookings,
            ],
        ]);
    }}

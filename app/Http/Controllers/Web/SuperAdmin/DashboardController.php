<?php

namespace App\Http\Controllers\Web\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $data = [
            'total_customers' => User::where('role', 'customer')->count(),
            'total_technicians' => User::where('role', 'technician')->count(),
            'total_bookings' => Booking::count(),
            'pending_payments' => Payment::where('status', 'pending')->count(),
        ];

        return view('pages.dashboard', compact('data'));
    }
}

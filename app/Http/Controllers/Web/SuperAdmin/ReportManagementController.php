<?php

namespace App\Http\Controllers\Web\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\User;

class ReportManagementController extends Controller
{

    public function index()
    {
        $totalBookings = Booking::count();

        $totalCustomers = User::where('role', 'customer')->count();

        $totalTechnicians = User::where('role', 'technician')->count();

        $totalRevenue = Payment::where('status', 'paid')->sum('amount');

        return view('pages.reports.index', compact(
            'totalBookings',
            'totalCustomers',
            'totalTechnicians',
            'totalRevenue'
        ));
    }

}



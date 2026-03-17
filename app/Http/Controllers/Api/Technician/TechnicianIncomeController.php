<?php

namespace App\Http\Controllers\Api\Technician;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class TechnicianIncomeController extends Controller
{
public function summary(Request $request)
    {
        $user = $request->user();

        $baseQuery = Payment::query()
            ->where('status', 'paid')
            ->whereHas('booking.technicians', function ($query) use ($user) {
                $query->where('technician_id', $user->id)
                    ->whereIn('status', ['working', 'completed']);
            });

        $totalIncome = (clone $baseQuery)->sum('amount');

        $thisMonthIncome = (clone $baseQuery)
            ->whereMonth('paid_at', now()->month)
            ->whereYear('paid_at', now()->year)
            ->sum('amount');

        $todayIncome = (clone $baseQuery)
            ->whereDate('paid_at', now()->toDateString())
            ->sum('amount');

        $paidOrders = (clone $baseQuery)->count();

        $recentPayments = (clone $baseQuery)
            ->with(['booking.user', 'booking.location'])
            ->latest('paid_at')
            ->take(10)
            ->get();

        return response()->json([
            'message' => 'Summary income teknisi berhasil diambil',
            'data' => [
                'summary' => [
                    'total_income' => (float) $totalIncome,
                    'this_month_income' => (float) $thisMonthIncome,
                    'today_income' => (float) $todayIncome,
                    'paid_orders' => $paidOrders,
                ],
                'recent_payments' => $recentPayments,
            ],
        ]);
    }}

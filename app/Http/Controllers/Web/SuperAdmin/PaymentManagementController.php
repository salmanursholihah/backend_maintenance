<?php

namespace App\Http\Controllers\Web\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentManagementController extends Controller
{
public function index()
    {
        $payments = Payment::with('booking.user')->latest()->paginate(20);

        return view('pages.payments.index', compact('payments'));
    }

    public function approve($id)
    {
        $payment = Payment::findOrFail($id);

        $payment->update([
            'status' => 'paid',
            'paid_at' => now()
        ]);

        return back()->with('success', 'Payment approved');
    }
}



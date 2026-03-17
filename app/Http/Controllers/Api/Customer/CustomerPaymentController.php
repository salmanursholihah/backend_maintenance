<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\Request;

class CustomerPaymentController extends Controller
{

    public function index(Request $request, $id)
    {
        $booking = Booking::where('user_id', $request->user()->id)->findOrFail($id);

        $payments = Payment::where('booking_id', $booking->id)->latest()->get();

        return response()->json(['data' => $payments]);
    }

    public function store(Request $request, $id)
    {
        $booking = Booking::where('user_id', $request->user()->id)->findOrFail($id);

        $data = $request->validate([
            'amount' => ['required', 'numeric', 'min:0'],
            'payment_method' => ['nullable', 'string', 'max:255'],
            'transaction_id' => ['nullable', 'string', 'max:255'],
            'payment_proof' => ['nullable', 'string'],
        ]);

        $payment = Payment::create([
            'booking_id' => $booking->id,
            'amount' => $data['amount'],
            'payment_method' => $data['payment_method'] ?? null,
            'transaction_id' => $data['transaction_id'] ?? null,
            'payment_proof' => $data['payment_proof'] ?? null,
            'status' => 'pending',
        ]);

        return response()->json([
            'message' => 'Pembayaran berhasil dikirim',
            'data' => $payment,
        ], 201);
    }

    public function show(Request $request, $paymentId)
    {
        $payment = Payment::whereHas('booking', function ($q) use ($request) {
            $q->where('user_id', $request->user()->id);
        })
            ->findOrFail($paymentId);

        return response()->json(['data' => $payment]);
    }
}

<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;

class CustomerPaymentController extends Controller
{

    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

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

    // public function show(Request $request, $paymentId)
    // {
    //     $payment = Payment::whereHas('booking', function ($q) use ($request) {
    //         $q->where('user_id', $request->user()->id);
    //     })
    //         ->findOrFail($paymentId);

    //     return response()->json(['data' => $payment]);
    // }

    public function show(Request $request, $bookingId)
    {
        $booking = Booking::where('user_id', $request->user()->id)
            ->findOrFail($bookingId);

        $payment = Payment::where('booking_id', $booking->id)
            ->latest()
            ->first();

        return response()->json(['data' => $payment]);
    }

    public function showByPaymentId(Request $request, $paymentId)
    {
        $payment = Payment::whereHas('booking', function ($q) use ($request) {
            $q->where('user_id', $request->user()->id);
        })->findOrFail($paymentId);

        return response()->json(['data' => $payment]);
    }
    // /**
    //  * Buat/lanjutkan transaksi Snap untuk sebuah booking.
    //  * Dipanggil dari PaymentScreen di Flutter saat customer klik "Bayar".
    //  */
    // public function createTransaction(Request $request, $bookingId)
    // {
    //     $booking = Booking::where('user_id', $request->user()->id)
    //         ->findOrFail($bookingId);

    //     if (!in_array($booking->status, ['estimation_approved'])) {
    //         return response()->json([
    //             'message' => 'Booking belum siap untuk dibayar.',
    //         ], 422);
    //     }

    //     // Reuse payment pending yang sama kalau sudah ada & belum expired,
    //     // supaya tidak generate order_id baru tiap kali customer buka ulang
    //     // halaman payment (mencegah transaksi duplikat di Midtrans).
    //     $payment = Payment::where('booking_id', $booking->id)
    //         ->where('status', 'pending')
    //         ->latest()
    //         ->first();

    //     if (!$payment) {
    //         $payment = Payment::create([
    //             'booking_id' => $booking->id,
    //             'amount' => $booking->estimated_total_price,
    //             'status' => 'pending',
    //         ]);
    //     }

    //     $orderId = 'PAY-' . $payment->id . '-' . now()->format('YmdHis');

    //     $params = [
    //         'transaction_details' => [
    //             'order_id' => $orderId,
    //             'gross_amount' => (int) $booking->estimated_total_price,
    //         ],
    //         'customer_details' => [
    //             'first_name' => $request->user()->name,
    //             'email' => $request->user()->email,
    //             'phone' => $request->user()->phone,
    //         ],
    //         'item_details' => [
    //             [
    //                 'id' => 'booking-' . $booking->id,
    //                 'price' => (int) $booking->estimated_total_price,
    //                 'quantity' => 1,
    //                 'name' => 'Maintenance IPAL - ' . $booking->booking_code,
    //             ],
    //         ],
    //     ];

    //     try {
    //         $snapToken = Snap::getSnapToken($params);
    //     } catch (\Throwable $e) {
    //         return response()->json([
    //             'message' => 'Gagal membuat transaksi pembayaran: ' . $e->getMessage(),
    //         ], 500);
    //     }

    //     $payment->update([
    //         'transaction_id' => $orderId,
    //     ]);

    //     return response()->json([
    //         'message' => 'Transaksi berhasil dibuat',
    //         'data' => [
    //             'snap_token' => $snapToken,
    //             'order_id' => $orderId,
    //             'amount' => $booking->estimated_total_price,
    //         ],
    //     ]);
    // }

    public function createTransaction(Request $request, $bookingId)
    {
        $booking = Booking::where('user_id', $request->user()->id)
            ->findOrFail($bookingId);

        if (!in_array($booking->status, ['estimation_approved'])) {
            return response()->json([
                'message' => 'Booking belum siap untuk dibayar.',
            ], 422);
        }

        if ($booking->final_total_price === null) {
            return response()->json([
                'message' => 'Total pembayaran belum tersedia untuk booking ini.',
            ], 422);
        }
        $payment = Payment::where('booking_id', $booking->id)
            ->where('status', 'pending')
            ->latest()
            ->first();

        if (!$payment) {
            $payment = Payment::create([
                'booking_id' => $booking->id,
                'amount' => $booking->final_total_price,
                'status' => 'pending',
            ]);
        }

        $orderId = 'PAY-' . $payment->id . '-' . now()->format('YmdHis');

        // CustomerPaymentController::createTransaction()
        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int) $booking->final_total_price,
            ],
            'customer_details' => [
                'first_name' => $request->user()->name,
                'email' => $request->user()->email,
                'phone' => $request->user()->phone,
            ],
            'item_details' => [
                [
                    'id' => 'booking-' . $booking->id,
                    'price' => (int) $booking->final_total_price,
                    'quantity' => 1,
                    'name' => 'Maintenance IPAL - ' . $booking->booking_code,
                ],
            ],
            'callbacks' => [
                'finish' => 'ipalmaintenance://payment-finish',
            ],
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Gagal membuat transaksi pembayaran: ' . $e->getMessage(),
            ], 500);
        }

        $payment->update(['transaction_id' => $orderId]);

        return response()->json([
            'message' => 'Transaksi berhasil dibuat',
            'data' => [
                'snap_token' => $snapToken,
                'order_id' => $orderId,
                'amount' => $booking->final_total_price,
            ],
        ]);
    }
}

<?php

namespace App\Http\Controllers\Api\Webhook;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Notification;

class MidtransWebhookController extends Controller
{
    public function handle(Request $request)
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');

        try {
            $notification = new Notification();
        } catch (\Throwable $e) {
            Log::warning('Midtrans webhook: payload tidak valid - ' . $e->getMessage());
            return response()->json(['message' => 'Invalid payload'], 400);
        }

        $orderId = $notification->order_id;
        $transactionStatus = $notification->transaction_status;
        $fraudStatus = $notification->fraud_status ?? null;

        $payment = Payment::where('transaction_id', $orderId)->first();

        if (!$payment) {
            Log::warning("Midtrans webhook: payment dengan order_id {$orderId} tidak ditemukan.");
            return response()->json(['message' => 'Payment not found'], 404);
        }

        $booking = Booking::find($payment->booking_id);

        // Logika status Midtrans mengikuti dokumentasi resmi:
        // https://docs.midtrans.com/docs/https-notification-webhooks
        if ($transactionStatus === 'capture') {
            if ($fraudStatus === 'challenge') {
                $this->markPending($payment);
            } elseif ($fraudStatus === 'accept') {
                $this->markPaid($payment, $booking, $notification);
            }
        } elseif ($transactionStatus === 'settlement') {
            $this->markPaid($payment, $booking, $notification);
        } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
            $this->markFailed($payment, $booking);
        } elseif ($transactionStatus === 'pending') {
            $this->markPending($payment);
        }

        return response()->json(['message' => 'OK']);
    }

    private function markPaid(Payment $payment, ?Booking $booking, Notification $notification): void
    {
        $payment->update([
            'status' => 'paid',
            'payment_method' => $notification->payment_type ?? $payment->payment_method,
            'paid_at' => now(),
        ]);

        if ($booking) {
            $booking->update([
                'payment_status' => 'paid',
                'final_total_price' => $payment->amount,
                'status' => 'maintenance_pending',
            ]);

            NotificationService::send(
                userId: $booking->user_id,
                title: 'Pembayaran Berhasil',
                message: 'Pembayaran booking ' . $booking->booking_code . ' sebesar Rp ' . number_format($payment->amount, 0, ',', '.') . ' telah dikonfirmasi.',
                type: 'payment_success',
                bookingId: $booking->id,
            );

            // Beri tahu juga teknisi yang di-assign bahwa pembayaran sudah
            // masuk dan maintenance bisa mulai dijadwalkan.
            $technicianIds = $booking->bookingTechnicians()->pluck('technician_id');
            foreach ($technicianIds as $technicianId) {
                NotificationService::send(
                    userId: $technicianId,
                    title: 'Pembayaran Diterima',
                    message: 'Customer telah membayar booking ' . $booking->booking_code . '. Silakan mulai jadwalkan maintenance.',
                    type: 'payment_success',
                    bookingId: $booking->id,
                );
            }
        }
    }

    private function markFailed(Payment $payment, ?Booking $booking): void
    {
        $payment->update([
            'status' => 'failed',
        ]);

        if ($booking) {
            $booking->update([
                'payment_status' => 'failed',
            ]);

            NotificationService::send(
                userId: $booking->user_id,
                title: 'Pembayaran Gagal',
                message: 'Pembayaran booking ' . $booking->booking_code . ' gagal diproses. Silakan coba kembali.',
                type: 'payment_failed',
                bookingId: $booking->id,
            );
        }
    }

    private function markPending(Payment $payment): void
    {
        $payment->update([
            'status' => 'pending',
        ]);
    }
}



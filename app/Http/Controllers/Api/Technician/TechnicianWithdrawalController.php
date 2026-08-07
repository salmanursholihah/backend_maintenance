<?php

namespace App\Http\Controllers\Api\Technician;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\TechnicianBankAccount;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TechnicianWithdrawalController extends Controller
{
    private function calculateTotalIncome($technicianId): float
    {
        return (float) Payment::query()
            ->where('status', 'paid')
            ->whereHas('booking.technicians', function ($query) use ($technicianId) {
                $query->where('technician_id', $technicianId)
                    ->whereIn('status', ['working', 'completed']);
            })
            ->sum('amount');
    }

    private function calculateLockedWithdrawals($technicianId): float
    {
        return (float) Withdrawal::where('technician_id', $technicianId)
            ->whereIn('status', ['pending', 'processing', 'completed'])
            ->sum('amount');
    }

    // GET /api/technician/withdrawals/balance
    public function balance(Request $request)
    {
        $user = $request->user();
        $totalIncome = $this->calculateTotalIncome($user->id);
        $locked = $this->calculateLockedWithdrawals($user->id);
        $available = max($totalIncome - $locked, 0);

        return response()->json([
            'message' => 'Berhasil mengambil saldo.',
            'data' => [
                'total_income' => $totalIncome,
                'total_withdrawn' => $locked,
                'available_balance' => $available,
            ],
        ]);
    }

    // GET /api/technician/withdrawals/history
    // Gabungan uang masuk (payment job selesai) + uang keluar (penarikan),
    // diurutkan tanggal terbaru dulu.
    public function history(Request $request)
    {
        $user = $request->user();

        $incomes = Payment::query()
            ->where('status', 'paid')
            ->whereHas('booking.technicians', function ($query) use ($user) {
                $query->where('technician_id', $user->id)
                    ->whereIn('status', ['working', 'completed']);
            })
            ->with('booking.details.service')
            ->latest('paid_at')
            ->get()
            ->map(function ($payment) {
                $serviceNames = $payment->booking?->details
                    ?->pluck('service.name')
                    ->filter()
                    ->join(', ') ?: 'Payment Pekerjaan';

                return [
                    'title' => $serviceNames,
                    'date' => optional($payment->paid_at)->toDateString(),
                    'amount' => (float) $payment->amount,
                    'is_income' => true,
                ];
            });

        $withdrawals = Withdrawal::where('technician_id', $user->id)
            ->with('bankAccount')
            ->latest()
            ->get()
            ->map(function ($withdrawal) {
                return [
                    'title' => 'Penarikan ke ' . ($withdrawal->bankAccount->provider ?? '-'),
                    'date' => $withdrawal->created_at->toDateString(),
                    'amount' => (float) $withdrawal->amount,
                    'is_income' => false,
                    'status' => $withdrawal->status,
                ];
            });

        $merged = $incomes->concat($withdrawals)
            ->sortByDesc('date')
            ->values();

        return response()->json([
            'message' => 'Berhasil mengambil riwayat.',
            'data' => $merged,
        ]);
    }

    // POST /api/technician/withdrawals
    public function store(Request $request)
    {
        $validated = $request->validate([
            'bank_account_id' => 'required|exists:technician_bank_accounts,id',
            'amount' => 'required|numeric|min:10000',
        ]);

        $user = $request->user();

        $account = TechnicianBankAccount::where('id', $validated['bank_account_id'])
            ->where('technician_id', $user->id)
            ->first();

        if (!$account) {
            return response()->json([
                'message' => 'Rekening tidak ditemukan atau bukan milik Anda.',
            ], 422);
        }

        return DB::transaction(function () use ($validated, $user, $account) {
            $totalIncome = $this->calculateTotalIncome($user->id);
            $locked = $this->calculateLockedWithdrawals($user->id);
            $available = max($totalIncome - $locked, 0);

            if ($validated['amount'] > $available) {
                abort(422, 'Nominal penarikan melebihi saldo tersedia.');
            }

            $adminFee = 2500;
            $received = $validated['amount'] - $adminFee;

            $withdrawal = Withdrawal::create([
                'technician_id' => $user->id,
                'bank_account_id' => $account->id,
                'amount' => $validated['amount'],
                'admin_fee' => $adminFee,
                'received_amount' => $received,
                'status' => 'pending',
            ]);

            return response()->json([
                'message' => 'Permintaan penarikan berhasil diajukan.',
                'data' => $withdrawal,
            ], 201);
        });
    }
}




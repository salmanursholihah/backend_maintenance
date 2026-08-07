<?php

namespace App\Http\Controllers\Api\Technician;

use App\Http\Controllers\Controller;
use App\Models\TechnicianBankAccount;
use Illuminate\Http\Request;

class TechnicianBankAccountController extends Controller
{
    // GET /api/technician/bank-accounts
    public function index(Request $request)
    {
        $accounts = TechnicianBankAccount::where('technician_id', $request->user()->id)
            ->orderByDesc('is_default')
            ->latest()
            ->get();

        return response()->json([
            'message' => 'Berhasil mengambil daftar rekening.',
            'data' => $accounts,
        ]);
    }

    // POST /api/technician/bank-accounts
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:bank,e_wallet',
            'provider' => 'required|string|max:50',
            'account_number' => 'required|string|max:50',
            'account_name' => 'required|string|max:100',
        ]);

        $user = $request->user();

        // Rekening pertama otomatis jadi default.
        $isFirst = !TechnicianBankAccount::where('technician_id', $user->id)->exists();

        $account = TechnicianBankAccount::create([
            ...$validated,
            'technician_id' => $user->id,
            'is_default' => $isFirst,
        ]);

        return response()->json([
            'message' => 'Rekening berhasil ditambahkan.',
            'data' => $account,
        ], 201);
    }

    // DELETE /api/technician/bank-accounts/{id}
    public function destroy(Request $request, $id)
    {
        $account = TechnicianBankAccount::where('technician_id', $request->user()->id)
            ->where('id', $id)
            ->firstOrFail();

        $account->delete();

        return response()->json([
            'message' => 'Rekening berhasil dihapus.',
        ]);
    }
}

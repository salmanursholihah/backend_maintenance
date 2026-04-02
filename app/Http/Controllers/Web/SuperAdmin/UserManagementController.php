<?php

namespace App\Http\Controllers\Web\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserManagementController extends Controller
{
public function customers()
    {
        $customers = User::where('role', 'customer')->latest()->paginate(20);
        return view('pages.users.customers', compact('customers'));
    }

    public function technicians()
    {
        $technicians = User::where('role', 'technician')->latest()->paginate(20);
        return view('pages.users.technicians', compact('technicians'));
    }

    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);

        $user->update([
            'is_active' => !$user->is_active
        ]);

        return back()->with('success', 'Status user berhasil diubah');
    }
}

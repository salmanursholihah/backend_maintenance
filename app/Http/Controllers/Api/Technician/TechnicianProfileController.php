<?php

namespace App\Http\Controllers\Api\Technician;

use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage; // BARU

class TechnicianProfileController extends Controller
{
    public function show(Request $request)
    {
        return response()->json([
            'message' => 'Profile teknisi berhasil diambil',
            'data' => $this->withPhotoUrl($request->user()), // diubah
        ]);
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'phone' => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'string'],
            'photo' => ['nullable', 'string'],
            'password' => ['nullable', 'string', 'min:6', 'confirmed'],
        ]);

        $payload = [
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'address' => $data['address'] ?? null,
            'photo' => $data['photo'] ?? $user->photo,
        ];

        if (!empty($data['password'])) {
            $payload['password'] = Hash::make($data['password']);
        }

        $user->update($payload);

        return response()->json([
            'message' => 'Profile teknisi berhasil diupdate',
            'data' => $this->withPhotoUrl($user->fresh()), // diubah
        ]);
    }

    // BARU
    public function uploadPhoto(Request $request)
    {
        $request->validate([
            'photo' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        $user = $request->user();

        // hapus foto lama biar storage nggak numpuk
        if ($user->photo && Storage::disk('public')->exists($user->photo)) {
            Storage::disk('public')->delete($user->photo);
        }

        $path = $request->file('photo')->store('profile-photos', 'public');

        $user->update(['photo' => $path]);

        return response()->json([
            'message' => 'Foto profil berhasil diperbarui',
            'data' => $this->withPhotoUrl($user->fresh()),
        ]);
    }

    // BARU — satu sumber kebenaran buat transform path -> URL
    private function withPhotoUrl($user)
    {
        $data = $user->toArray();
        $data['photo'] = $user->photo
            ? Storage::disk('public')->url($user->photo)
            : null;
        return $data;
    }
}




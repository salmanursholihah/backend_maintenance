<?php

namespace App\Http\Controllers\Api\Technician;

use App\Http\Controllers\Controller;
use App\Models\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TechnicianProfileController extends Controller
{
public function show(Request $request)
    {
        return response()->json([
            'message' => 'Profile teknisi berhasil diambil',
            'data' => $request->user(),
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
            'data' => $user->fresh(),
        ]);
    }}

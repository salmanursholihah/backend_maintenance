<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Models\MaintenanceLocation;
use Illuminate\Http\Request;

class CustomerLocationController extends Controller
{  public function index(Request $request)
    {
        $locations = MaintenanceLocation::where('user_id', $request->user()->id)
            ->latest()
            ->get();

        return response()->json(['data' => $locations]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'location_name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string'],
            'latitude' => ['nullable', 'string'],
            'longitude' => ['nullable', 'string'],
            'ipal_type' => ['nullable', 'string', 'max:255'],
            'capacity' => ['nullable', 'string', 'max:255'],
            'installation_type' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $location = MaintenanceLocation::create([
            ...$data,
            'user_id' => $request->user()->id,
        ]);

        return response()->json([
            'message' => 'Lokasi berhasil ditambahkan',
            'data' => $location,
        ], 201);
    }

    public function show(Request $request, $id)
    {
        $location = MaintenanceLocation::where('user_id', $request->user()->id)
            ->findOrFail($id);

        return response()->json(['data' => $location]);
    }

    public function update(Request $request, $id)
    {
        $location = MaintenanceLocation::where('user_id', $request->user()->id)
            ->findOrFail($id);

        $data = $request->validate([
            'location_name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string'],
            'latitude' => ['nullable', 'string'],
            'longitude' => ['nullable', 'string'],
            'ipal_type' => ['nullable', 'string', 'max:255'],
            'capacity' => ['nullable', 'string', 'max:255'],
            'installation_type' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $location->update($data);

        return response()->json([
            'message' => 'Lokasi berhasil diupdate',
            'data' => $location,
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $location = MaintenanceLocation::where('user_id', $request->user()->id)
            ->findOrFail($id);

        $location->delete();

        return response()->json([
            'message' => 'Lokasi berhasil dihapus',
        ]);
    }
}


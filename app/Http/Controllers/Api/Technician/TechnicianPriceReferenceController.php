<?php

namespace App\Http\Controllers\Api\Technician;

use App\Http\Controllers\Controller;
use App\Models\TechnicianPriceReference;
use Illuminate\Http\Request;

class TechnicianPriceReferenceController extends Controller
{
 public function index(Request $request)
    {
        $data = TechnicianPriceReference::where('technician_id', $request->user()->id)
            ->latest()
            ->get();

        return response()->json(['data' => $data]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'component_name' => ['required', 'string', 'max:255'],
            'damage_level' => ['nullable', 'string', 'max:255'],
            'work_type' => ['nullable', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $item = TechnicianPriceReference::create([
            ...$data,
            'technician_id' => $request->user()->id,
            'is_active' => $data['is_active'] ?? true,
        ]);

        return response()->json([
            'message' => 'Patokan harga berhasil ditambahkan',
            'data' => $item,
        ], 201);
    }

    public function show(Request $request, $id)
    {
        $item = TechnicianPriceReference::where('technician_id', $request->user()->id)
            ->findOrFail($id);

        return response()->json(['data' => $item]);
    }

    public function update(Request $request, $id)
    {
        $item = TechnicianPriceReference::where('technician_id', $request->user()->id)
            ->findOrFail($id);

        $data = $request->validate([
            'component_name' => ['required', 'string', 'max:255'],
            'damage_level' => ['nullable', 'string', 'max:255'],
            'work_type' => ['nullable', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $item->update($data);

        return response()->json([
            'message' => 'Patokan harga berhasil diupdate',
            'data' => $item,
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $item = TechnicianPriceReference::where('technician_id', $request->user()->id)
            ->findOrFail($id);

        $item->delete();

        return response()->json([
            'message' => 'Patokan harga berhasil dihapus',
        ]);
    }}

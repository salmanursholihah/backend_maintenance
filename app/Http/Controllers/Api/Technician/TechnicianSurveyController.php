<?php

namespace App\Http\Controllers\Api\Technician;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\SurveyResult;
use App\Models\SurveyResultItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TechnicianSurveyController extends Controller
{
public function show(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        $survey = SurveyResult::with('items')
            ->where('booking_id', $booking->id)
            ->where('technician_id', $request->user()->id)
            ->first();

        return response()->json([
            'data' => $survey,
        ]);
    }

    public function storeOrUpdate(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        $data = $request->validate([
            'inspection_result' => ['nullable', 'string'],
            'problem_summary' => ['nullable', 'string'],
            'recommended_action' => ['nullable', 'string'],
            'estimated_duration' => ['nullable', 'integer', 'min:0'],
            'service_cost' => ['nullable', 'numeric', 'min:0'],
            'sparepart_cost' => ['nullable', 'numeric', 'min:0'],
            'other_cost' => ['nullable', 'numeric', 'min:0'],
            'items' => ['nullable', 'array'],
            'items.*.type' => ['required_with:items', 'in:tool,material,sparepart,component'],
            'items.*.name' => ['required_with:items', 'string', 'max:255'],
            'items.*.qty' => ['nullable', 'integer', 'min:1'],
            'items.*.unit' => ['nullable', 'string', 'max:100'],
            'items.*.price' => ['nullable', 'numeric', 'min:0'],
            'items.*.description' => ['nullable', 'string'],
        ]);

        DB::beginTransaction();

        try {
            $total = ($data['service_cost'] ?? 0) + ($data['sparepart_cost'] ?? 0) + ($data['other_cost'] ?? 0);

            $survey = SurveyResult::updateOrCreate(
                [
                    'booking_id' => $booking->id,
                    'technician_id' => $request->user()->id,
                ],
                [
                    'inspection_result' => $data['inspection_result'] ?? null,
                    'problem_summary' => $data['problem_summary'] ?? null,
                    'recommended_action' => $data['recommended_action'] ?? null,
                    'estimated_duration' => $data['estimated_duration'] ?? null,
                    'service_cost' => $data['service_cost'] ?? 0,
                    'sparepart_cost' => $data['sparepart_cost'] ?? 0,
                    'other_cost' => $data['other_cost'] ?? 0,
                    'estimated_total_cost' => $total,
                    'status' => 'draft',
                ]
            );

            SurveyResultItem::where('survey_result_id', $survey->id)->delete();

            foreach (($data['items'] ?? []) as $item) {
                $qty = $item['qty'] ?? 1;
                $price = $item['price'] ?? 0;

                SurveyResultItem::create([
                    'survey_result_id' => $survey->id,
                    'type' => $item['type'],
                    'name' => $item['name'],
                    'qty' => $qty,
                    'unit' => $item['unit'] ?? null,
                    'price' => $price,
                    'subtotal' => $qty * $price,
                    'description' => $item['description'] ?? null,
                ]);
            }

            $booking->update([
                'status' => 'waiting_estimation_approval',
                'estimated_total_price' => $total,
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Hasil survei berhasil disimpan',
                'data' => $survey->load('items'),
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function submit(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        $survey = SurveyResult::where('booking_id', $booking->id)
            ->where('technician_id', $request->user()->id)
            ->firstOrFail();

        $survey->update([
            'status' => 'submitted',
            'submitted_at' => now(),
        ]);

        $booking->update([
            'status' => 'waiting_estimation_approval',
            'survey_status' => 'done',
        ]);

        return response()->json([
            'message' => 'Estimasi berhasil dikirim ke customer',
        ]);
    }}

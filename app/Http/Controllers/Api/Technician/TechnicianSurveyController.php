<?php

namespace App\Http\Controllers\Api\Technician;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\SurveyResult;
use App\Models\SurveyResultItem;
use Illuminate\Http\Request;
use App\Services\NotificationService;
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


     public function store(Request $request, $bookingId)
    {
        $booking = Booking::findOrFail($bookingId);

        if ($booking->status !== 'survey_on_progress') {
            return response()->json([
                'message' => 'Booking tidak dalam status survei berjalan.',
            ], 422);
        }

        $data = $request->validate([
            'estimated_duration' => ['nullable', 'integer', 'min:0'],
            'service_cost'       => ['required', 'numeric', 'min:0'],
            'sparepart_cost'     => ['nullable', 'numeric', 'min:0'],
            'other_cost'         => ['nullable', 'numeric', 'min:0'],
            'notes'              => ['nullable', 'string'],
            'items'              => ['nullable', 'array'],
            'items.*.name'       => ['required_with:items', 'string'],
            'items.*.qty'        => ['required_with:items', 'integer', 'min:1'],
            'items.*.price'      => ['required_with:items', 'numeric', 'min:0'],
        ]);

        $serviceCost = $data['service_cost'];
        $sparepartCost = $data['sparepart_cost'] ?? 0;
        $otherCost = $data['other_cost'] ?? 0;
        $total = $serviceCost + $sparepartCost + $otherCost;

        $surveyResult = DB::transaction(function () use ($booking, $request, $data, $serviceCost, $sparepartCost, $otherCost, $total) {
            $surveyResult = SurveyResult::updateOrCreate(
                ['booking_id' => $booking->id],
                [
                    'technician_id'        => $request->user()->id,
                    'estimated_duration'   => $data['estimated_duration'] ?? null,
                    'service_cost'         => $serviceCost,
                    'sparepart_cost'       => $sparepartCost,
                    'other_cost'           => $otherCost,
                    'estimated_total_cost' => $total,
                    'notes'                => $data['notes'] ?? null,
                    'submitted_at'         => now(),
                ]
            );

            $surveyResult->items()->delete();
            foreach (($data['items'] ?? []) as $item) {
                $surveyResult->items()->create([
                    'name'     => $item['name'],
                    'qty'      => $item['qty'],
                    'price'    => $item['price'],
                    'subtotal' => $item['qty'] * $item['price'],
                ]);
            }

            $booking->update([
                'status'        => 'waiting_estimation_approval',
                'survey_status' => 'done',
            ]);

            return $surveyResult;
        });

     NotificationService::send(
    userId: $booking->user_id,
    title: 'Estimasi Biaya Survei Tersedia',   // DIUBAH: selaras dengan submit() lama
    message: 'Teknisi telah mengirim estimasi biaya untuk booking ' . $booking->booking_code . ' sebesar Rp' . number_format($total, 0, ',', '.') . '. Silakan tinjau dan setujui.',
    type: 'survey_estimate',   // DIUBAH dari 'estimation_approval'
    bookingId: $booking->id,
);

        return response()->json([
            'message' => 'Hasil survei berhasil dikirim.',
            'data' => $surveyResult->load('items'),
        ]);
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

        NotificationService::send(
            userId: $booking->user_id,
            title: 'Estimasi Biaya Survei Tersedia',
            message: 'Teknisi telah mengirim estimasi biaya untuk booking ' . $booking->booking_code . '. Silakan tinjau dan setujui.',
            type: 'survey_estimate',
            bookingId: $booking->id,
        );

        return response()->json([
            'message' => 'Estimasi berhasil dikirim ke customer',
        ]);
    }
}



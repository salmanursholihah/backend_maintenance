<?php

namespace App\Http\Controllers\Api\Technician;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\MaintenanceReport;
use App\Models\ReportPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TechnicianReportController extends Controller
{
public function show(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        $report = MaintenanceReport::with('photos')
            ->where('booking_id', $booking->id)
            ->where('technician_id', $request->user()->id)
            ->first();

        return response()->json(['data' => $report]);
    }

    public function storeOrUpdate(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        $data = $request->validate([
            'report' => ['required', 'string'],
            'before_condition' => ['nullable', 'string'],
            'after_condition' => ['nullable', 'string'],
            'action_taken' => ['nullable', 'string'],
            'recommendation' => ['nullable', 'string'],
            'condition' => ['required', 'in:good,need_attention,critical'],
            'work_duration' => ['nullable', 'integer', 'min:0'],
            'reported_at' => ['nullable', 'date'],
            'photos' => ['nullable', 'array'],
            'photos.*.photo' => ['required_with:photos', 'string'],
            'photos.*.type' => ['required_with:photos', 'in:before,after,documentation'],
        ]);

        DB::beginTransaction();

        try {
            $report = MaintenanceReport::updateOrCreate(
                [
                    'booking_id' => $booking->id,
                    'technician_id' => $request->user()->id,
                ],
                [
                    'report' => $data['report'],
                    'before_condition' => $data['before_condition'] ?? null,
                    'after_condition' => $data['after_condition'] ?? null,
                    'action_taken' => $data['action_taken'] ?? null,
                    'recommendation' => $data['recommendation'] ?? null,
                    'condition' => $data['condition'],
                    'work_duration' => $data['work_duration'] ?? null,
                    'reported_at' => $data['reported_at'] ?? now(),
                ]
            );

            ReportPhoto::where('report_id', $report->id)->delete();

            foreach (($data['photos'] ?? []) as $photo) {
                ReportPhoto::create([
                    'report_id' => $report->id,
                    'photo' => $photo['photo'],
                    'type' => $photo['type'],
                ]);
            }

            DB::commit();

            return response()->json([
                'message' => 'Laporan maintenance berhasil disimpan',
                'data' => $report->load('photos'),
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
    }

@extends('layouts.app')

@section('title', 'Detail Booking')

@section('breadcrumb')
<a href="{{ route('bookings.index') }}" class="btn btn-sm btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
@endsection

@section('content')
<div class="row">
    <div class="col-12 col-md-7">
        <div class="card">
            <div class="card-header">
                <h4>{{ $booking->booking_code ?? '-' }}</h4>
                <div class="card-header-action">
                    <span class="badge badge-{{ $booking->status === 'completed' ? 'success' : ($booking->status === 'cancelled' ? 'danger' : 'warning') }}">
                        {{ ucwords(str_replace('_', ' ', $booking->status)) }}
                    </span>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-borderless mb-0">
                    <tr>
                        <th width="180">Customer</th>
                        <td>: {{ $booking->user->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>No. HP</th>
                        <td>: {{ $booking->user->phone ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Alamat</th>
                        <td>: {{ $booking->location->address ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal / Jam</th>
                        <td>: {{ $booking->booking_date }} {{ $booking->booking_time }}</td>
                    </tr>
                    <tr>
                        <th>Keluhan</th>
                        <td>: {{ $booking->complaint ?? '-' }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h4>Layanan Dipesan</h4>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped mb-0">
                        <thead>
                            <tr>
                                <th>Layanan</th>
                                <th>Qty</th>
                                <th>Harga</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($booking->details as $detail)
                                <tr>
                                    <td>{{ $detail->service->name ?? '-' }}</td>
                                    <td>{{ $detail->qty }}</td>
                                    <td>Rp {{ number_format($detail->price, 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center text-muted py-3">Belum ada layanan.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        @if ($booking->surveyResult)
        <div class="card">
            <div class="card-header">
                <h4>Hasil Survei / Estimasi Biaya</h4>
            </div>
            <div class="card-body">
                <table class="table table-borderless mb-3">
                    <tr><th width="180">Biaya Jasa</th><td>: Rp {{ number_format($booking->surveyResult->service_cost, 0, ',', '.') }}</td></tr>
                    <tr><th>Biaya Sparepart</th><td>: Rp {{ number_format($booking->surveyResult->sparepart_cost, 0, ',', '.') }}</td></tr>
                    <tr><th>Biaya Lain</th><td>: Rp {{ number_format($booking->surveyResult->other_cost, 0, ',', '.') }}</td></tr>
                    <tr><th>Total Estimasi</th><td>: <strong>Rp {{ number_format($booking->surveyResult->estimated_total_cost, 0, ',', '.') }}</strong></td></tr>
                </table>

                @if ($booking->surveyResult->items && $booking->surveyResult->items->count())
                <table class="table table-sm table-striped mb-0">
                    <thead><tr><th>Komponen</th><th>Qty</th><th>Harga</th></tr></thead>
                    <tbody>
                        @foreach ($booking->surveyResult->items as $item)
                        <tr>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->qty }}</td>
                            <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
            </div>
        </div>
        @endif

        @if ($booking->report)
        <div class="card">
            <div class="card-header">
                <h4>Laporan Pekerjaan</h4>
            </div>
            <div class="card-body">
                <p>{{ $booking->report->report }}</p>
                @if ($booking->report->photos && $booking->report->photos->count())
                <div class="row">
                    @foreach ($booking->report->photos as $photo)
                    <div class="col-4 col-md-3 mb-2">
                        <img src="{{ asset('storage/' . $photo->photo) }}" class="img-fluid rounded" alt="foto laporan">
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
        @endif
    </div>

    <div class="col-12 col-md-5">
        <div class="card">
            <div class="card-header">
                <h4>Assign Teknisi</h4>
            </div>
            <div class="card-body">
                {{-- CATATAN: assignTechnician() di controller cuma update pivot
                     BookingTechnician, TIDAK mengubah status booking secara
                     otomatis. Kalau perlu status ikut berubah, tambahkan itu
                     di controller. --}}
                <form method="POST" action="{{ route('bookings.assign-technician', $booking->id) }}">
                    @csrf
                    <div class="form-group">
                        <label>Pilih Teknisi</label>
                        <select name="technician_id" class="form-control select2" required>
                            <option value="">-- Pilih Teknisi --</option>
                            @foreach ($technicians as $technician)
                                <option value="{{ $technician->id }}"
                                    {{ optional($booking->bookingTechnician)->technician_id == $technician->id ? 'selected' : '' }}>
                                    {{ $technician->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="fas fa-user-check"></i> Assign Teknisi
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

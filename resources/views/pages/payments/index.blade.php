@extends('layouts.app')

@section('title', 'Pembayaran')

@section('content')
<div class="card">
    <div class="card-header">
        <h4>Daftar Pembayaran</h4>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Kode Booking</th>
                        <th>Customer</th>
                        <th>Jumlah</th>
                        <th>Metode</th>
                        <th>Status</th>
                        <th>Tanggal Bayar</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($payments as $payment)
                        <tr>
                            <td>{{ $loop->iteration + ($payments->currentPage() - 1) * $payments->perPage() }}</td>
                            <td>{{ $payment->booking->booking_code ?? '-' }}</td>
                            <td>{{ $payment->booking->user->name ?? '-' }}</td>
                            <td>Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                            <td>{{ $payment->payment_method ?? '-' }}</td>
                            <td>
                                <span class="badge badge-{{ $payment->status === 'paid' ? 'success' : ($payment->status === 'failed' ? 'danger' : 'warning') }}">
                                    {{ ucfirst($payment->status) }}
                                </span>
                            </td>
                            <td>{{ $payment->paid_at ? \Carbon\Carbon::parse($payment->paid_at)->format('d M Y H:i') : '-' }}</td>
                            <td class="text-right">
                                @if ($payment->status !== 'paid')
                                <form method="POST" action="{{ route('payments.approve', $payment->id) }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success"
                                            onclick="return confirm('Approve pembayaran ini?')">
                                        <i class="fas fa-check"></i> Approve
                                    </button>
                                </form>
                                @else
                                    <span class="text-success"><i class="fas fa-check-circle"></i> Lunas</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center text-muted py-4">Belum ada data pembayaran.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer">
        {{ $payments->links() }}
    </div>
</div>
@endsection





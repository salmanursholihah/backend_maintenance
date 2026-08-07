@extends('layouts.app')

@section('title', 'Notifikasi')

@section('content')
<div class="card">
    <div class="card-header">
        <h4>Log Notifikasi Sistem</h4>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Judul</th>
                        <th>Pesan</th>
                        <th>Tipe</th>
                        <th>Status</th>
                        <th>Dikirim</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($notifications as $notification)
                        <tr>
                            <td>{{ $loop->iteration + ($notifications->currentPage() - 1) * $notifications->perPage() }}</td>
                            <td>{{ $notification->title }}</td>
                            <td>{{ \Illuminate\Support\Str::limit($notification->message, 60) }}</td>
                            <td><span class="badge badge-secondary">{{ $notification->type ?? '-' }}</span></td>
                            <td>
                                <span class="badge badge-{{ $notification->is_read ? 'success' : 'warning' }}">
                                    {{ $notification->is_read ? 'Sudah dibaca' : 'Belum dibaca' }}
                                </span>
                            </td>
                            <td>{{ $notification->created_at->format('d M Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted py-4">Belum ada notifikasi.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer">
        {{ $notifications->links() }}
    </div>
</div>
@endsection

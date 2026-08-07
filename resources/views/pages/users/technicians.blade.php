@extends('layouts.app')

@section('title', 'Teknisi')

@section('content')
<div class="card">
    <div class="card-header">
        <h4>Daftar Teknisi</h4>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>No. HP</th>
                        <th>Status</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($technicians as $technician)
                        <tr>
                            <td>{{ $loop->iteration + ($technicians->currentPage() - 1) * $technicians->perPage() }}</td>
                            <td>{{ $technician->name }}</td>
                            <td>{{ $technician->email }}</td>
                            <td>{{ $technician->phone ?? '-' }}</td>
                            <td>
                                <span class="badge badge-{{ $technician->is_active ? 'success' : 'secondary' }}">
                                    {{ $technician->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="text-right">
                                <form method="POST" action="{{ route('users.toggle-status', $technician->id) }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-{{ $technician->is_active ? 'danger' : 'success' }}"
                                            onclick="return confirm('Yakin ingin mengubah status teknisi ini?')">
                                        <i class="fas fa-power-off"></i> {{ $technician->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted py-4">Belum ada teknisi.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer">
        {{ $technicians->links() }}
    </div>
</div>
@endsection

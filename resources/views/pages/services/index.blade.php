@extends('layouts.app')

@section('title', 'Layanan')

@section('breadcrumb')
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalAddService">
    <i class="fas fa-plus"></i> Tambah Layanan
</button>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h4>Daftar Layanan</h4>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Layanan</th>
                        <th>Deskripsi</th>
                        <th>Harga Dasar</th>
                        <th>Status</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($services as $service)
                        <tr>
                            <td>{{ $loop->iteration + ($services->currentPage() - 1) * $services->perPage() }}</td>
                            <td>{{ $service->name }}</td>
                            <td>{{ \Illuminate\Support\Str::limit($service->description, 60) ?? '-' }}</td>
                            <td>Rp {{ number_format($service->base_price, 0, ',', '.') }}</td>
                            <td>
                                <span class="badge badge-{{ $service->is_active ? 'success' : 'secondary' }}">
                                    {{ $service->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="text-right">
                                <button type="button" class="btn btn-sm btn-warning"
                                        data-toggle="modal" data-target="#modalEditService{{ $service->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form method="POST" action="{{ route('services.destroy', $service->id) }}" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger"
                                            onclick="return confirm('Hapus layanan ini?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>

                        <!-- Modal Edit -->
                        <div class="modal fade" id="modalEditService{{ $service->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form method="POST" action="{{ route('services.update', $service->id) }}">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Layanan</h5>
                                            <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label>Nama Layanan</label>
                                                <input type="text" name="name" class="form-control" value="{{ $service->name }}" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Deskripsi</label>
                                                <textarea name="description" class="form-control" rows="3">{{ $service->description }}</textarea>
                                            </div>
                                            <div class="form-group">
                                                <label>Harga Dasar</label>
                                                <input type="number" name="base_price" class="form-control" value="{{ $service->base_price }}" required min="0">
                                            </div>
                                            <div class="form-group">
                                                <label>Estimasi Durasi (menit)</label>
                                                <input type="number" name="duration_estimation" class="form-control" value="{{ $service->duration_estimation }}" min="0">
                                            </div>
                                            <div class="form-group">
                                                <div class="custom-checkbox custom-control">
                                                    <input type="checkbox" name="is_active" class="custom-control-input"
                                                           id="isActive{{ $service->id }}" value="1" {{ $service->is_active ? 'checked' : '' }}>
                                                    <label for="isActive{{ $service->id }}" class="custom-control-label">Aktif</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-primary">Simpan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted py-4">Belum ada layanan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer">
        {{ $services->links() }}
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="modalAddService" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('services.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Layanan</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Layanan</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Deskripsi</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Harga Dasar</label>
                        <input type="number" name="base_price" class="form-control" required min="0">
                    </div>
                    <div class="form-group">
                        <label>Estimasi Durasi (menit)</label>
                        <input type="number" name="duration_estimation" class="form-control" min="0">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

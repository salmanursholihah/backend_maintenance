@extends('layouts.app')

@section('main')

<section class="section">
    <div class="section-header">
        <h1>Services</h1>
    </div>

    <div class="card">
        <div class="card-header">
            <h4>Daftar Layanan</h4>
        </div>

        <div class="card-body">

            <table class="table table-bordered table-striped">

                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Service</th>
                        <th>Harga</th>
                        <th>Deskripsi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($services as $index => $service)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $service->name }}</td>
                        <td>Rp {{ number_format($service->price,0,',','.') }}</td>
                        <td>{{ $service->description }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center">Data service kosong</td>
                    </tr>
                    @endforelse
                </tbody>

            </table>

        </div>
    </div>
</section>

@endsection

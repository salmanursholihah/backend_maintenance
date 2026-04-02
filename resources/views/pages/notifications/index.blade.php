@extends('layouts.app')

@section('main')

<section class="section">
    <div class="section-header">
        <h1>Notifications</h1>
    </div>

    <div class="card">
        <div class="card-header">
            <h4>Daftar Notifikasi</h4>
        </div>

        <div class="card-body">

            <ul class="list-group">

                @forelse($notifications as $notification)
                    <li class="list-group-item">
                        {{ $notification->message }}
                        <br>
                        <small class="text-muted">
                            {{ $notification->created_at->format('d M Y H:i') }}
                        </small>
                    </li>
                @empty
                    <li class="list-group-item text-center">
                        Tidak ada notifikasi
                    </li>
                @endforelse

            </ul>

        </div>
    </div>
</section>

@endsection

@extends('layouts.app')

@section('main')

<section class="section">
    <div class="section-header">
        <h1>Booking Detail</h1>
    </div>

    <div class="card">
        <div class="card-body">

            <p>Kode : {{ $booking->booking_code }}</p>
            <p>Customer : {{ $booking->user->name }}</p>
            <p>Status : {{ $booking->status }}</p>
            <p>Alamat : {{ $booking->address }}</p>

        </div>
    </div>
</section>

@endsection

@extends('layouts.app')

@section('main')
    <section class="section">
        <div class="section-header">
            <h1>Bookings</h1>
        </div>

        <div class="card">
            <div class="card-body">

                <table class="table table-bordered">

                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Customer</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($bookings as $booking)
                            <tr>
                                <td>{{ $booking->booking_code }}</td>
                                <td>{{ $booking->user->name }}</td>
                                <td>{{ $booking->status }}</td>
                                <td>
                                    <a href="{{ route('pages.bookings.show', $booking->id) }}" class="btn btn-primary btn-sm">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>

                </table>

            </div>
        </div>
    </section>
@endsection



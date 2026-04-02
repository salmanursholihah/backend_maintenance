@extends('layouts.app')

@section('main')

<section class="section">
    <div class="section-header">
        <h1>Reports</h1>
    </div>

    <div class="card">
        <div class="card-header">
            <h4>Laporan Sistem</h4>
        </div>

        <div class="card-body">

            <table class="table table-bordered table-striped">

                <tr>
                    <th>Total Booking</th>
                    <td>{{ $totalBookings }}</td>
                </tr>

                <tr>
                    <th>Total Customer</th>
                    <td>{{ $totalCustomers }}</td>
                </tr>

                <tr>
                    <th>Total Teknisi</th>
                    <td>{{ $totalTechnicians }}</td>
                </tr>

                <tr>
                    <th>Total Revenue</th>
                    <td>Rp {{ number_format($totalRevenue,0,',','.') }}</td>
                </tr>

            </table>

        </div>
    </div>
</section>

@endsection

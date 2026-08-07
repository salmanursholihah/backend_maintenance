@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="row">
    <div class="col-12 col-sm-6 col-lg-3">
        <div class="card card-statistic-1">
            <div class="card-icon bg-primary"><i class="fas fa-users"></i></div>
            <div class="card-wrap">
                <div class="card-header"><h4>Total Customer</h4></div>
                <div class="card-body">{{ number_format($data['total_customers']) }}</div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-lg-3">
        <div class="card card-statistic-1">
            <div class="card-icon bg-success"><i class="fas fa-user-cog"></i></div>
            <div class="card-wrap">
                <div class="card-header"><h4>Total Teknisi</h4></div>
                <div class="card-body">{{ number_format($data['total_technicians']) }}</div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-lg-3">
        <div class="card card-statistic-1">
            <div class="card-icon bg-warning"><i class="fas fa-clipboard-list"></i></div>
            <div class="card-wrap">
                <div class="card-header"><h4>Total Booking</h4></div>
                <div class="card-body">{{ number_format($data['total_bookings']) }}</div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-lg-3">
        <div class="card card-statistic-1">
            <div class="card-icon bg-danger"><i class="fas fa-hourglass-half"></i></div>
            <div class="card-wrap">
                <div class="card-header"><h4>Payment Pending</h4></div>
                <div class="card-body">{{ number_format($data['pending_payments']) }}</div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4>Ringkasan Sistem</h4>
            </div>
            <div class="card-body">
                <p class="text-muted mb-0">
                    Selamat datang di Panel Super Admin IPAL Maintenance. Gunakan menu di sebelah kiri
                    untuk mengelola booking, layanan, pembayaran, customer, dan teknisi.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

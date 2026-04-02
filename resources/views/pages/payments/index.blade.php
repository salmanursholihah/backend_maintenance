@extends('layouts.app')

@section('main')

<section class="section">
    <div class="section-header">
        <h1>Payments</h1>
    </div>

    <div class="card">
        <div class="card-body">

            <table class="table table-bordered">
                @foreach($payments as $payment)
                <tr>
                    <td>{{ $payment->booking->booking_code }}</td>
                    <td>{{ $payment->amount }}</td>
                    <td>{{ $payment->status }}</td>
                </tr>
                @endforeach
            </table>

        </div>
    </div>
</section>

@endsection

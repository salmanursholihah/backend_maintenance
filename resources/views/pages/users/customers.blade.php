@extends('layouts.app')

@section('main')

<section class="section">
    <div class="section-header">
        <h1>Customers</h1>
    </div>

    <div class="card">
        <div class="card-body">

            <table class="table table-bordered">

                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>No HP</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($customers as $customer)
                    <tr>
                        <td>{{ $customer->name }}</td>
                        <td>{{ $customer->email }}</td>
                        <td>{{ $customer->phone }}</td>
                    </tr>
                    @endforeach
                </tbody>

            </table>

        </div>
    </div>
</section>

@endsection

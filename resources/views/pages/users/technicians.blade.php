{{-- @extends('layouts.app')

@section('main')
    <section class="section">
        <div class="section-header">
            <h1>Technicians</h1>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4>Data Technicians</h4>
                </div>

                <div class="card-body">

                    <div class="table-responsive">
                        <table class="table table-striped table-md">

                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Skill</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($technicians as $technician)
                                    <tr>
                                        <td>{{ $technician->name }}</td>
                                        <td>{{ $technician->email }}</td>
                                        <td>{{ $technician->skill }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center">
                                            Data technician kosong
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>

                        </table>
                    </div>

                </div>
            </div>
        </div>
    </section>
@endsection --}}



@extends('layouts.app')

@section('main')
    <section class="section">
        <div class="section-header">
            <h1>Technicians</h1>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4>Data Technicians</h4>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">

                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Skill</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($technicians as $technician)
                                    <tr>
                                        <td>{{ $technician->name }}</td>
                                        <td>{{ $technician->email }}</td>
                                        <td>{{ $technician->skill }}</td>
                                    </tr>
                                @endforeach
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

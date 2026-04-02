@extends('layouts.auth')

@section('main')

<div class="card card-primary">
    <div class="card-header">
        <h4>Register Super Admin</h4>
    </div>

    <div class="card-body">

        <form method="POST" action="{{ route('register.submit') }}">
            @csrf

            <div class="form-group">
                <label>Nama</label>
                <input type="text" name="name" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Konfirmasi Password</label>
                <input type="password" name="password_confirmation" class="form-control" required>
            </div>

            <button class="btn btn-primary btn-block">
                Register
            </button>

        </form>

    </div>
</div>

@endsection

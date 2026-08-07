@extends('layouts.auth')

@section('title', 'Register Super Admin')

@section('content')
<form method="POST" action="{{ route('register.submit') }}">
    @csrf
    <div class="form-group">
        <label for="name">Nama</label>
        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror"
               name="name" value="{{ old('name') }}" required autofocus placeholder="Nama lengkap">
        @error('name')
            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
        @enderror
    </div>

    <div class="form-group">
        <label for="email">Email</label>
        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
               name="email" value="{{ old('email') }}" required placeholder="admin@ipal.com">
        @error('email')
            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
        @enderror
    </div>

    <div class="form-group">
        <label for="password">Password</label>
        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
               name="password" required placeholder="Minimal 6 karakter">
        @error('password')
            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
        @enderror
    </div>

    <div class="form-group">
        <label for="password_confirmation">Konfirmasi Password</label>
        <input id="password_confirmation" type="password" class="form-control"
               name="password_confirmation" required placeholder="Ulangi password">
    </div>

    <div class="form-group">
        <button type="submit" class="btn btn-primary btn-lg btn-block">Daftar</button>
    </div>

    <div class="text-center">
        Sudah punya akun? <a href="{{ route('login') }}">Login di sini</a>
    </div>
</form>
@endsection

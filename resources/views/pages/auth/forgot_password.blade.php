@extends('layouts.auth')

@section('title', 'Lupa Password')

@section('content')
<p class="text-muted mb-4">Masukkan email akun Anda, kami akan mengirimkan link untuk reset password.</p>

<form method="POST" action="{{ route('forgot-password.submit') }}">
    @csrf
    <div class="form-group">
        <label for="email">Email</label>
        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
               name="email" value="{{ old('email') }}" required autofocus placeholder="admin@ipal.com">
        @error('email')
            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
        @enderror
    </div>

    <div class="form-group">
        <button type="submit" class="btn btn-primary btn-lg btn-block">Kirim Link Reset</button>
    </div>

    <div class="text-center">
        <a href="{{ route('login') }}">Kembali ke Login</a>
    </div>
</form>
@endsection

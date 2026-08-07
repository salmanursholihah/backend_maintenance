@extends('layouts.auth')

@section('title', 'Login')

@section('content')
<form method="POST" action="{{ route('login.submit') }}">
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
        <label for="password">Password</label>
        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
               name="password" required placeholder="Masukkan password">
        @error('password')
            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
        @enderror
    </div>

    <div class="form-group">
        <div class="custom-checkbox custom-control">
            <input type="checkbox" name="remember" class="custom-control-input" id="remember"
                   {{ old('remember') ? 'checked' : '' }}>
            <label for="remember" class="custom-control-label">Ingat saya</label>
        </div>
    </div>

    <div class="form-group">
        <button type="submit" class="btn btn-primary btn-lg btn-block">Login</button>
    </div>

    <div class="text-center">
        <a class="text-small" href="{{ route('forgot.password') }}">Lupa password?</a>
    </div>
</form>
@endsection

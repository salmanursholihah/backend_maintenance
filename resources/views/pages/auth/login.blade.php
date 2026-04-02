@extends('layouts.auth')

@section('title', 'Login Super Admin')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('assets/library/bootstrap-social/bootstrap-social.css') }}">
@endpush

@section('main')
    <div class="card card-primary">
        <div class="card-header">
            <h4>Login Super Admin</h4>
        </div>

        <div class="card-body">

            {{-- Session Error --}}
            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Validation Errors --}}
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login.submit') }}" class="needs-validation" novalidate>
                @csrf

                <div class="form-group">
                    <label for="email">Email</label>
                    <input id="email"
                           type="email"
                           class="form-control @error('email') is-invalid @enderror"
                           name="email"
                           value="{{ old('email') }}"
                           tabindex="1"
                           required
                           autofocus>

                    @error('email')
                        <div class="invalid-feedback d-block">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group">
                    <div class="d-block">
                        <label for="password" class="control-label">Password</label>
                        <div class="float-right">
                            <a href="{{ route('forgot.password') }}" class="text-small">
                                Forgot Password?
                            </a>
                        </div>
                    </div>

                    <input id="password"
                           type="password"
                           class="form-control @error('password') is-invalid @enderror"
                           name="password"
                           tabindex="2"
                           required>

                    @error('password')
                        <div class="invalid-feedback d-block">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox"
                               name="remember"
                               class="custom-control-input"
                               id="remember-me">

                        <label class="custom-control-label" for="remember-me">
                            Remember Me
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <button type="submit"
                            class="btn btn-primary btn-lg btn-block"
                            tabindex="4">
                        Login
                    </button>
                </div>
            </form>

        </div>
    </div>
@endsection

@push('scripts')
@endpush

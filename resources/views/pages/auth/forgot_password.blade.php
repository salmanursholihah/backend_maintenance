```php id="bnedx7"
@extends('layouts.auth')

@section('content')

<div class="card card-primary">
    <div class="card-header">
        <h4>Forgot Password</h4>
    </div>

    <div class="card-body">

        <form method="POST" action="{{ route('forgot.password.submit') }}">
            @csrf

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>

            <button class="btn btn-primary btn-block">
                Kirim Reset Link
            </button>

        </form>

    </div>
</div>

@endsection
```

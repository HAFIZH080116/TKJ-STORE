@extends('layouts.auth')

@section('title', 'Login')

@section('content')
<div class="container min-vh-100 d-flex align-items-center justify-content-center">
    <div class="col-md-5">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <div class="text-center mb-4">
                    <h4 class="fw-bold mb-1">{{ config('app.name') }}</h4>
                    <p class="text-muted small mb-0">Masuk dengan email dan password</p>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger py-2">
                        @foreach ($errors->all() as $error)
                            <div class="small">{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" id="email"
                               class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email') }}" required autofocus>
                    </div>
                    <div class="mb-4">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" name="password" id="password"
                               class="form-control @error('password') is-invalid @enderror" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 mb-3">
                        <i class="bi bi-box-arrow-in-right me-1"></i> Login
                    </button>
                    <div class="text-center">
                        <span class="text-muted small">Belum punya akun?</span>
                        <a href="{{ route('register') }}" class="small text-decoration-none fw-semibold">Daftar di sini</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

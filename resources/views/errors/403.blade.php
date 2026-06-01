<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 Forbidden</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    @include('partials.theme-script')
</head>
<body class="bg-body-tertiary d-flex align-items-center min-vh-100">
    <div class="container text-center">
        <h1 class="display-1 fw-bold text-danger">403</h1>
        <h2 class="h4 mb-3">Akses Ditolak</h2>
        <p class="text-muted mb-4">{{ $exception->getMessage() ?: 'Anda tidak memiliki hak akses ke halaman ini.' }}</p>
        @auth
            <a href="{{ url()->previous() }}" class="btn btn-outline-secondary me-2">Kembali</a>
            @php
                $home = match (auth()->user()->role) {
                    'admin' => route('admin.dashboard'),
                    'developer' => route('developer.dashboard'),
                    'user' => route('user.home'),
                    default => route('login'),
                };
            @endphp
            <a href="{{ $home }}" class="btn btn-primary">Ke Dashboard Saya</a>
        @else
            <a href="{{ route('login') }}" class="btn btn-primary">Login</a>
        @endauth
    </div>
</body>
</html>

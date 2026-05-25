<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') — Admin | {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root { --sidebar-width: 260px; --sidebar-bg: #1e293b; }
        body { min-height: 100vh; background: #f1f5f9; }
        .sidebar {
            width: var(--sidebar-width);
            min-height: 100vh;
            background: var(--sidebar-bg);
            position: fixed;
            top: 0; left: 0;
            z-index: 1030;
        }
        .sidebar .nav-link {
            color: #cbd5e1;
            border-radius: .5rem;
            margin: .15rem .75rem;
            padding: .6rem 1rem;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            background: #334155;
            color: #fff;
        }
        .main-content { margin-left: var(--sidebar-width); }
        @media (max-width: 991.98px) {
            .sidebar { transform: translateX(-100%); transition: transform .3s; }
            .sidebar.show { transform: translateX(0); }
            .main-content { margin-left: 0; }
        }
        .stat-card { border: none; border-radius: .75rem; }
    </style>
    @stack('styles')
</head>
<body>
    @include('partials.sidebar-admin')

    <div class="main-content">
        @include('partials.navbar-dashboard', ['roleLabel' => 'Administrator'])

        <main class="p-4">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @yield('content')
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('sidebarToggle')?.addEventListener('click', function () {
            document.getElementById('sidebar').classList.toggle('show');
        });
    </script>
    @stack('scripts')
</body>
</html>

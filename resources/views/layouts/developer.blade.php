<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') — Developer | {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    @include('partials.theme-script')
    <style>
        :root { --sidebar-width: 260px; --sidebar-bg: #0f172a; }
        body { min-height: 100vh; }
        html[data-bs-theme="light"] body { background: #f8fafc; }
        .sidebar {
            width: var(--sidebar-width);
            min-height: 100vh;
            background: var(--sidebar-bg);
            position: fixed;
            top: 0; left: 0;
            z-index: 1030;
        }
        .sidebar .nav-link {
            color: #94a3b8;
            border-radius: .5rem;
            margin: .15rem .75rem;
            padding: .6rem 1rem;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            background: #1e293b;
            color: #38bdf8;
        }
        .main-content { margin-left: var(--sidebar-width); }
        @media (max-width: 991.98px) {
            .sidebar { transform: translateX(-100%); transition: transform .3s; }
            .sidebar.show { transform: translateX(0); }
            .main-content { margin-left: 0; }
        }
    </style>
    @stack('styles')
</head>
<body>
    @include('partials.sidebar-developer')

    <div class="main-content">
        @include('partials.navbar-dashboard', ['roleLabel' => 'Developer'])

        <main class="p-4">
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

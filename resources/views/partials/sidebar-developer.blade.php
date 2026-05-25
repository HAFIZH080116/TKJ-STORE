<aside class="sidebar d-flex flex-column" id="sidebar">
    <div class="p-3 border-bottom border-secondary">
        <a href="{{ route('developer.dashboard') }}" class="text-info text-decoration-none fw-bold">
            <i class="bi bi-code-slash me-2"></i>{{ config('app.name') }}
        </a>
        <div class="text-secondary small mt-1">Panel Developer</div>
    </div>
    <nav class="nav flex-column py-3 flex-grow-1">
        <a href="{{ route('developer.dashboard') }}"
           class="nav-link {{ request()->routeIs('developer.dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2 me-2"></i> Dashboard
        </a>
        <a href="{{ route('developer.dashboard') }}#monitoring"
           class="nav-link">
            <i class="bi bi-activity me-2"></i> Monitoring Sistem
        </a>
        <a href="#" class="nav-link disabled" tabindex="-1">
            <i class="bi bi-tags me-2"></i> Kelola Versi
        </a>
        <a href="#" class="nav-link disabled" tabindex="-1">
            <i class="bi bi-bug me-2"></i> Bug Report
        </a>
        <a href="#" class="nav-link disabled" tabindex="-1">
            <i class="bi bi-journal-text me-2"></i> Log Aktivitas
        </a>
        <a href="#" class="nav-link disabled" tabindex="-1">
            <i class="bi bi-person me-2"></i> Profil
        </a>
    </nav>
    <div class="p-3 border-top border-secondary">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-outline-info btn-sm w-100">
                <i class="bi bi-box-arrow-right me-1"></i> Logout
            </button>
        </form>
    </div>
</aside>

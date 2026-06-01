<aside class="sidebar d-flex flex-column" id="sidebar">
    <div class="p-3 border-bottom border-secondary">
        <a href="{{ route('developer.dashboard') }}" class="text-info text-decoration-none fw-bold d-flex align-items-center">
            <img src="{{ asset('favicon.svg') }}" alt="Logo" width="30" height="30" class="d-inline-block align-top me-2">
            <span>{{ config('app.name') }}</span>
        </a>
        <div class="text-secondary small mt-1">Panel Developer</div>
    </div>
    <nav class="nav flex-column py-3 flex-grow-1">
        <a href="{{ route('developer.dashboard') }}"
           class="nav-link {{ request()->routeIs('developer.dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2 me-2"></i> Dashboard
        </a>
        <a href="{{ route('developer.monitoring') }}"
           class="nav-link {{ request()->routeIs('developer.monitoring') ? 'active' : '' }}">
            <i class="bi bi-activity me-2"></i> Monitoring Sistem
        </a>
        <a href="{{ route('developer.versi.index') }}"
           class="nav-link {{ request()->routeIs('developer.versi.*') ? 'active' : '' }}">
            <i class="bi bi-tags me-2"></i> Kelola Versi
        </a>
        <a href="{{ route('developer.bug.index') }}"
           class="nav-link {{ request()->routeIs('developer.bug.*') ? 'active' : '' }}">
            <i class="bi bi-bug me-2"></i> Bug Report
        </a>
        <a href="{{ route('developer.users.index') }}"
           class="nav-link {{ request()->routeIs('developer.users.*') ? 'active' : '' }}">
            <i class="bi bi-people me-2"></i> Kelola Pengguna
        </a>
        <a href="{{ route('developer.log.index') }}"
           class="nav-link {{ request()->routeIs('developer.log.index') ? 'active' : '' }}">
            <i class="bi bi-journal-text me-2"></i> Log Aktivitas
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

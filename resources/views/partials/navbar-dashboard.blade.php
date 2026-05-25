<nav class="navbar navbar-expand-lg bg-white border-bottom shadow-sm sticky-top">
    <div class="container-fluid px-4">
        <button class="btn btn-outline-secondary d-lg-none" type="button" id="sidebarToggle">
            <i class="bi bi-list"></i>
        </button>
        <span class="navbar-text ms-2 fw-semibold">@yield('page-title', 'Dashboard')</span>
        <div class="ms-auto d-flex align-items-center gap-3">
            <span class="badge text-bg-primary">{{ $roleLabel }}</span>
            <div class="text-end d-none d-sm-block">
                <div class="fw-semibold small">{{ auth()->user()->name }}</div>
                <div class="text-muted" style="font-size:.75rem">{{ auth()->user()->email }}</div>
            </div>
        </div>
    </div>
</nav>

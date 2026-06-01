<aside class="sidebar d-flex flex-column" id="sidebar">
    <div class="p-3 border-bottom border-secondary">
        <a href="{{ route('admin.dashboard') }}" class="text-white text-decoration-none fw-bold">
            <i class="bi bi-shop me-2"></i>{{ config('app.name') }}
        </a>
        <div class="text-secondary small mt-1">Panel Admin</div>
    </div>
    <nav class="nav flex-column py-3 flex-grow-1">
        <a href="{{ route('admin.dashboard') }}"
           class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2 me-2"></i> Dashboard
        </a>
        <a href="{{ route('admin.produk.index') }}"
           class="nav-link {{ request()->routeIs('admin.produk.*') ? 'active' : '' }}">
            <i class="bi bi-box-seam me-2"></i> Kelola Produk
        </a>

        <a href="{{ route('admin.pesanan.index') }}"
           class="nav-link {{ request()->routeIs('admin.pesanan.*') ? 'active' : '' }} d-flex justify-content-between align-items-center">
            <span><i class="bi bi-receipt me-2"></i> Kelola Pesanan</span>
            @if (($pendingOrdersCount ?? 0) > 0)
                <span class="badge rounded-pill bg-danger border border-light animate-pulse px-2 py-1" style="font-size: 0.75rem;">
                    {{ $pendingOrdersCount }}
                </span>
            @endif
        </a>
        <a href="{{ route('admin.laporan.index') }}"
           class="nav-link {{ request()->routeIs('admin.laporan.*') ? 'active' : '' }}">
            <i class="bi bi-bar-chart-line me-2"></i> Laporan
        </a>
    </nav>
    <div class="p-3 border-top border-secondary">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-outline-light btn-sm w-100">
                <i class="bi bi-box-arrow-right me-1"></i> Logout
            </button>
        </form>
    </div>
</aside>

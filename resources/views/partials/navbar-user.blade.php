<nav class="navbar navbar-expand-lg bg-body-tertiary shadow-sm sticky-top">
    <div class="container">
        <a class="navbar-brand text-primary d-flex align-items-center" href="{{ route('user.home') }}">
            <img src="{{ asset('favicon.svg') }}" alt="Logo" width="30" height="30" class="d-inline-block align-top me-2">
            <span class="fw-bold">{{ config('app.name') }}</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#userNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="userNavbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('user.home') ? 'active fw-semibold' : '' }}"
                       href="{{ route('user.home') }}">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('user.produk.*') ? 'active fw-semibold' : '' }}"
                       href="{{ route('user.produk.index') }}">Produk</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('user.keranjang.*') ? 'active fw-semibold' : '' }}"
                       href="{{ route('user.keranjang.index') }}">
                        Keranjang
                        @if (($cartQty ?? 0) > 0)
                            <span class="badge text-bg-danger">{{ $cartQty }}</span>
                        @endif
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('user.pesanan.*') ? 'active fw-semibold' : '' }}"
                       href="{{ route('user.pesanan.index') }}">Riwayat Pesanan</a>
                </li>

            </ul>
            <button class="btn btn-sm rounded-circle border-0 d-flex align-items-center justify-content-center p-2 me-3 bg-body-secondary" id="themeToggle" type="button" title="Ganti Tema">
                <i class="bi bi-moon-stars-fill text-secondary"></i>
            </button>
            <form method="POST" action="{{ route('logout') }}" class="d-flex">
                @csrf
                <button type="submit" class="btn btn-outline-danger btn-sm">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </button>
            </form>
        </div>
    </div>
</nav>

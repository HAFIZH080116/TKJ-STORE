@extends('layouts.user')

@section('title', 'Produk')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
    <h4 class="fw-bold mb-0"><i class="bi bi-grid me-2"></i>Katalog Produk</h4>
    <form method="GET" action="{{ route('user.produk.index') }}" class="d-flex gap-2" style="max-width:320px">
        <input type="text" name="q" class="form-control form-control-sm" placeholder="Cari produk..."
               value="{{ request('q') }}">
        <button type="submit" class="btn btn-sm btn-primary"><i class="bi bi-search"></i></button>
        @if (request('q'))
            <a href="{{ route('user.produk.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
        @endif
    </form>
</div>

<div class="mb-4">
    <div class="nav nav-pills gap-2">
        <a href="{{ route('user.produk.index') }}" class="nav-link {{ !request('cat') ? 'active' : 'bg-body text-secondary border' }} btn-sm rounded-pill px-3">Semua</a>
        <a href="{{ route('user.produk.index', ['cat' => 'laptop']) }}" class="nav-link {{ request('cat') === 'laptop' ? 'active' : 'bg-body text-secondary border' }} btn-sm rounded-pill px-3">Laptop</a>
        <a href="{{ route('user.produk.index', ['cat' => 'periferal']) }}" class="nav-link {{ request('cat') === 'periferal' ? 'active' : 'bg-body text-secondary border' }} btn-sm rounded-pill px-3">Periferal</a>
        <a href="{{ route('user.produk.index', ['cat' => 'komponen']) }}" class="nav-link {{ request('cat') === 'komponen' ? 'active' : 'bg-body text-secondary border' }} btn-sm rounded-pill px-3">Komponen</a>
        <a href="{{ route('user.produk.index', ['cat' => 'monitor']) }}" class="nav-link {{ request('cat') === 'monitor' ? 'active' : 'bg-body text-secondary border' }} btn-sm rounded-pill px-3">Monitor</a>
    </div>
</div>

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row g-3">
    @forelse ($produks as $produk)
        <div class="col-6 col-md-4 col-lg-3">
            <div class="card product-card shadow-sm h-100">
                @if ($produk->gambar)
                    <img src="{{ asset('storage/'.$produk->gambar) }}" class="card-img-top" alt="{{ $produk->nama_produk }}"
                         style="height:160px;object-fit:cover">
                @else
                    <div class="bg-body-secondary d-flex align-items-center justify-content-center" style="height:160px">
                        <i class="bi bi-image text-muted fs-1"></i>
                    </div>
                @endif
                <div class="card-body d-flex flex-column">
                    <h6 class="card-title fw-semibold">{{ $produk->nama_produk }}</h6>
                    <p class="text-primary fw-bold mb-1">Rp {{ number_format($produk->harga_satuan, 0, ',', '.') }}</p>
                    <small class="text-muted mb-3">Stok: {{ $produk->stok }}</small>
                    <a href="{{ route('user.produk.show', $produk) }}" class="btn btn-outline-primary btn-sm mt-auto">
                        <i class="bi bi-eye me-1"></i> Detail
                    </a>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-light border text-center text-muted">Produk tidak ditemukan.</div>
        </div>
    @endforelse
</div>

<div class="mt-4">
    {{ $produks->links() }}
</div>
@endsection

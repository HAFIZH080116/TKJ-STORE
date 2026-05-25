@extends('layouts.user')

@section('title', $produk->nama_produk)

@section('content')
<div class="row g-4">
    <div class="col-md-5">
        <div class="card border-0 shadow-sm">
            @if ($produk->gambar)
                <img src="{{ asset('storage/'.$produk->gambar) }}" class="card-img-top rounded-top"
                     alt="{{ $produk->nama_produk }}" style="max-height:360px;object-fit:cover">
            @else
                <div class="bg-light d-flex align-items-center justify-content-center py-5">
                    <i class="bi bi-image text-muted display-3"></i>
                </div>
            @endif
        </div>
    </div>
    <div class="col-md-7">
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb small">
                <li class="breadcrumb-item"><a href="{{ route('user.produk.index') }}">Produk</a></li>
                <li class="breadcrumb-item active">{{ $produk->nama_produk }}</li>
            </ol>
        </nav>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <h3 class="fw-bold">{{ $produk->nama_produk }}</h3>
        <h4 class="text-primary">Rp {{ number_format($produk->harga_satuan, 0, ',', '.') }}</h4>
        <p class="mb-2">
            Stok:
            <span class="badge text-bg-{{ $produk->stok > 0 ? 'success' : 'danger' }}">{{ $produk->stok }}</span>
        </p>
        <p class="text-muted">{{ $produk->deskripsi ?: 'Tidak ada deskripsi.' }}</p>

        @if ($produk->stok > 0)
            <form method="POST" action="{{ route('user.keranjang.tambah', $produk) }}" class="row g-2 align-items-end mt-4">
                @csrf
                <div class="col-auto">
                    <label for="jumlah" class="form-label">Jumlah</label>
                    <input type="number" name="jumlah" id="jumlah" class="form-control" value="1" min="1" max="{{ $produk->stok }}" required>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-cart-plus me-1"></i> Tambah ke Keranjang
                    </button>
                </div>
            </form>
        @else
            <div class="alert alert-warning mt-3">Produk sedang habis.</div>
        @endif

        <div class="mt-3">
            <a href="{{ route('user.produk.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
            <a href="{{ route('user.keranjang.index') }}" class="btn btn-outline-primary btn-sm">
                <i class="bi bi-cart3 me-1"></i> Lihat Keranjang
            </a>
        </div>
    </div>
</div>
@endsection

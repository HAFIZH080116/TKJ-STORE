@extends('layouts.admin')

@section('title', 'Detail Produk')
@section('page-title', 'Detail Produk')

@section('content')
<div class="row g-4">
    <div class="col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-body text-center">
                @if ($produk->gambar)
                    <img src="{{ asset('storage/'.$produk->gambar) }}" alt="{{ $produk->nama_produk }}"
                         class="img-fluid rounded mb-3" style="max-height:280px;object-fit:cover">
                @else
                    <div class="bg-light rounded py-5 mb-3">
                        <i class="bi bi-image text-muted display-4"></i>
                    </div>
                @endif
                <h5 class="fw-bold">{{ $produk->nama_produk }}</h5>
                <p class="text-muted small mb-0">ID: {{ $produk->id_produk }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th width="180">Harga Satuan</th>
                        <td>Rp {{ number_format($produk->harga_satuan, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <th>Stok</th>
                        <td>
                            <span class="badge text-bg-{{ $produk->stok > 0 ? 'success' : 'danger' }}">
                                {{ $produk->stok }} unit
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Deskripsi</th>
                        <td>{{ $produk->deskripsi ?: '—' }}</td>
                    </tr>
                </table>
                <div class="d-flex gap-2 mt-3">
                    <a href="{{ route('admin.produk.edit', $produk) }}" class="btn btn-warning text-white">
                        <i class="bi bi-pencil me-1"></i> Edit
                    </a>
                    <a href="{{ route('admin.produk.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

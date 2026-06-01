@extends('layouts.user')

@section('title', 'Keranjang')

@section('content')
<h4 class="fw-bold mb-4"><i class="bi bi-cart3 me-2"></i>Keranjang Belanja</h4>

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

@if (count($items) === 0)
    <div class="card border-0 shadow-sm text-center py-5">
        <div class="card-body">
            <i class="bi bi-cart-x display-4 text-muted"></i>
            <p class="text-muted mt-3 mb-3">Keranjang Anda masih kosong.</p>
            <a href="{{ route('user.produk.index') }}" class="btn btn-primary">Belanja Sekarang</a>
        </div>
    </div>
@else
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Produk</th>
                                <th width="120">Harga</th>
                                <th width="140">Jumlah</th>
                                <th width="120">Subtotal</th>
                                <th width="60"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($items as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            @if ($item['gambar'])
                                                <img src="{{ asset('storage/'.$item['gambar']) }}" width="48" height="48"
                                                     class="rounded object-fit-cover" alt="{{ $item['nama_produk'] }}">
                                            @else
                                                <div class="bg-body-secondary rounded d-flex align-items-center justify-content-center" style="width:48px;height:48px">
                                                    <i class="bi bi-image text-muted"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <div class="fw-semibold">{{ $item['nama_produk'] }}</div>
                                                <small class="text-muted">Stok: {{ $item['stok'] }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>Rp {{ number_format($item['harga_satuan'], 0, ',', '.') }}</td>
                                    <td>
                                        <form method="POST" action="{{ route('user.keranjang.update', $item['id_produk']) }}" class="d-flex gap-1">
                                            @csrf
                                            @method('PUT')
                                            <input type="number" name="jumlah" class="form-control form-control-sm"
                                                   value="{{ $item['jumlah'] }}" min="1" max="{{ $item['stok'] }}" required>
                                            <button type="submit" class="btn btn-sm btn-outline-primary" title="Update">
                                                <i class="bi bi-arrow-repeat"></i>
                                            </button>
                                        </form>
                                    </td>
                                    <td class="fw-semibold">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</td>
                                    <td>
                                        <form method="POST" action="{{ route('user.keranjang.hapus', $item['id_produk']) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Ringkasan</h5>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Total Item</span>
                        <span>{{ array_sum(array_column($items, 'jumlah')) }} pcs</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Total Harga</span>
                        <span class="fs-5 fw-bold text-primary">Rp {{ number_format($totalHarga, 0, ',', '.') }}</span>
                    </div>
                    <a href="{{ route('user.checkout.index') }}" class="btn btn-primary w-100 mb-2">
                        <i class="bi bi-credit-card me-1"></i> Checkout
                    </a>
                    <a href="{{ route('user.produk.index') }}" class="btn btn-outline-secondary w-100">
                        <i class="bi bi-arrow-left me-1"></i> Lanjut Belanja
                    </a>
                </div>
            </div>
        </div>
    </div>
@endif
@endsection

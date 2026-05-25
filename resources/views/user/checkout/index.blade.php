@extends('layouts.user')

@section('title', 'Checkout')

@section('content')
<h4 class="fw-bold mb-4"><i class="bi bi-credit-card me-2"></i>Checkout</h4>

<div class="row g-4">
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-semibold">Ringkasan Pesanan</div>
            <ul class="list-group list-group-flush">
                @foreach ($items as $item)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <span class="fw-semibold">{{ $item['nama_produk'] }}</span>
                            <small class="text-muted d-block">{{ $item['jumlah'] }} x Rp {{ number_format($item['harga_satuan'], 0, ',', '.') }}</small>
                        </div>
                        <span>Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h5 class="fw-bold mb-3">Pembayaran</h5>
                <div class="d-flex justify-content-between mb-3">
                    <span class="text-muted">Total Bayar</span>
                    <span class="fs-4 fw-bold text-primary">Rp {{ number_format($totalHarga, 0, ',', '.') }}</span>
                </div>
                <form method="POST" action="{{ route('user.checkout.proses') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="metode_pembayaran" class="form-label">Metode Pembayaran <span class="text-danger">*</span></label>
                        <select name="metode_pembayaran" id="metode_pembayaran"
                                class="form-select @error('metode_pembayaran') is-invalid @enderror" required>
                            <option value="">-- Pilih --</option>
                            @foreach (['Transfer Bank', 'COD', 'E-Wallet', 'Tunai'] as $metode)
                                <option value="{{ $metode }}" @selected(old('metode_pembayaran') === $metode)>{{ $metode }}</option>
                            @endforeach
                        </select>
                        @error('metode_pembayaran')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary w-100 mb-2">
                        <i class="bi bi-check-circle me-1"></i> Proses Checkout
                    </button>
                    <a href="{{ route('user.keranjang.index') }}" class="btn btn-outline-secondary w-100">Kembali ke Keranjang</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

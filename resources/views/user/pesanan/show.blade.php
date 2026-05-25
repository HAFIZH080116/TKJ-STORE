@extends('layouts.user')

@section('title', 'Detail Pesanan #'.$pesanan->id_transaksi)

@section('content')
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('user.pesanan.index') }}">Riwayat Pesanan</a></li>
        <li class="breadcrumb-item active">#{{ $pesanan->id_transaksi }}</li>
    </ol>
</nav>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-3">
                <small class="text-muted">ID Transaksi</small>
                <div class="fw-bold">#{{ $pesanan->id_transaksi }}</div>
            </div>
            <div class="col-md-3">
                <small class="text-muted">Tanggal</small>
                <div>{{ $pesanan->tanggal_transaksi?->format('d M Y H:i') }}</div>
            </div>
            <div class="col-md-3">
                <small class="text-muted">Metode Pembayaran</small>
                <div>{{ $pesanan->metode_pembayaran }}</div>
            </div>
            <div class="col-md-3">
                <small class="text-muted">Status</small>
                <div>
                    @php
                        $badge = match ($pesanan->status) {
                            'selesai' => 'success',
                            'diproses' => 'warning',
                            default => 'secondary',
                        };
                    @endphp
                    <span class="badge text-bg-{{ $badge }}">{{ $pesanan->status }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white fw-semibold">Detail Produk</div>
    <div class="table-responsive">
        <table class="table mb-0">
            <thead class="table-light">
                <tr>
                    <th>Produk</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pesanan->detailTransaksi as $detail)
                    <tr>
                        <td>{{ $detail->produk?->nama_produk ?? 'Produk #'.$detail->id_produk }}</td>
                        <td>Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                        <td>{{ $detail->jumlah }}</td>
                        <td>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3" class="text-end">Total Pembayaran</th>
                    <th>Rp {{ number_format($pesanan->total_pembayaran, 0, ',', '.') }}</th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<a href="{{ route('user.pesanan.index') }}" class="btn btn-outline-secondary mt-3">
    <i class="bi bi-arrow-left me-1"></i> Kembali
</a>
@endsection

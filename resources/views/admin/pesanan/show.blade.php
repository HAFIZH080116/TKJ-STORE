@extends('layouts.admin')

@section('title', 'Detail Pesanan #'.$pesanan->id_transaksi)
@section('page-title', 'Detail Pesanan #'.$pesanan->id_transaksi)

@section('content')
<div class="row g-4 mb-4">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white fw-semibold">Informasi Pesanan</div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <small class="text-muted">Pelanggan</small>
                        <div class="fw-semibold">{{ $pesanan->user?->name }}</div>
                        <small>{{ $pesanan->user?->email }} | {{ $pesanan->user?->no_hp }}</small>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted">Tanggal Transaksi</small>
                        <div>{{ $pesanan->tanggal_transaksi?->format('d M Y H:i') }}</div>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted">Metode Pembayaran</small>
                        <div>{{ $pesanan->metode_pembayaran }}</div>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted">Total Pembayaran</small>
                        <div class="fs-5 fw-bold text-primary">Rp {{ number_format($pesanan->total_pembayaran, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white fw-semibold">Ubah Status</div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.pesanan.update-status', $pesanan) }}">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="status" class="form-label">Status Pesanan</label>
                        <select name="status" id="status" class="form-select" required>
                            @foreach (['pending', 'diproses', 'selesai'] as $s)
                                <option value="{{ $s }}" @selected($pesanan->status === $s)>{{ $s }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-save me-1"></i> Simpan Status
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white fw-semibold">Detail Produk</div>
    <div class="table-responsive">
        <table class="table mb-0">
            <thead class="table-light">
                <tr>
                    <th>Produk</th>
                    <th>Harga Satuan</th>
                    <th>Jumlah</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pesanan->detailTransaksi as $detail)
                    <tr>
                        <td>{{ $detail->produk?->nama_produk ?? 'ID '.$detail->id_produk }}</td>
                        <td>Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                        <td>{{ $detail->jumlah }}</td>
                        <td>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<a href="{{ route('admin.pesanan.index') }}" class="btn btn-outline-secondary mt-3">
    <i class="bi bi-arrow-left me-1"></i> Kembali
</a>
@endsection

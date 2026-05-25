@extends('layouts.admin')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard Admin')

@section('content')
<div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted small mb-1">Total Produk</p>
                        <h3 class="mb-0 fw-bold">{{ number_format($totalProduk) }}</h3>
                    </div>
                    <span class="badge text-bg-primary rounded-pill p-2"><i class="bi bi-box-seam fs-5"></i></span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted small mb-1">Total User</p>
                        <h3 class="mb-0 fw-bold">{{ number_format($totalUser) }}</h3>
                    </div>
                    <span class="badge text-bg-success rounded-pill p-2"><i class="bi bi-people fs-5"></i></span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted small mb-1">Total Pesanan</p>
                        <h3 class="mb-0 fw-bold">{{ number_format($totalPesanan) }}</h3>
                    </div>
                    <span class="badge text-bg-warning rounded-pill p-2"><i class="bi bi-receipt fs-5"></i></span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted small mb-1">Total Pendapatan</p>
                        <h3 class="mb-0 fw-bold">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h3>
                        <small class="text-muted">Status selesai</small>
                    </div>
                    <span class="badge text-bg-danger rounded-pill p-2"><i class="bi bi-currency-dollar fs-5"></i></span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white">
        <h5 class="mb-0"><i class="bi bi-clock-history me-2"></i>Pesanan Terbaru</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Tanggal</th>
                        <th>Total</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pesananTerbaru as $pesanan)
                        <tr>
                            <td>#{{ $pesanan->id_transaksi }}</td>
                            <td>{{ $pesanan->user?->name ?? '-' }}</td>
                            <td>{{ $pesanan->tanggal_transaksi?->format('d/m/Y H:i') }}</td>
                            <td>Rp {{ number_format($pesanan->total_pembayaran, 0, ',', '.') }}</td>
                            <td>
                                @php
                                    $badge = match ($pesanan->status) {
                                        'selesai' => 'success',
                                        'diproses' => 'warning',
                                        default => 'secondary',
                                    };
                                @endphp
                                <span class="badge text-bg-{{ $badge }}">{{ $pesanan->status }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">Belum ada pesanan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@extends('layouts.user')

@section('title', 'Home')

@section('content')
<!-- Notifications Area -->
@if (!empty($notifications))
    <div class="mb-4">
        @foreach ($notifications as $notif)
            @php
                $alertClass = $notif['status'] === 'selesai' ? 'success' : 'info';
                $alertIcon = $notif['status'] === 'selesai' ? 'bi-check-circle-fill text-success' : 'bi-info-circle-fill text-info';
            @endphp
            <div class="alert alert-{{ $alertClass }} bg-opacity-10 border border-{{ $alertClass }} shadow-sm d-flex justify-content-between align-items-center p-3 mb-2" role="alert" style="border-radius: 0.75rem;">
                <div class="d-flex align-items-center gap-3">
                    <span class="fs-4"><i class="bi {{ $alertIcon }}"></i></span>
                    <div>
                        <strong class="text-capitalize text-body">{{ $notif['status'] }}</strong>
                        <span class="d-block small text-muted">{{ $notif['message'] }}</span>
                    </div>
                </div>
                <form method="POST" action="{{ route('user.notifikasi.baca', $notif['key']) }}" class="mb-0">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-link text-decoration-none p-2 text-secondary border-0" title="Tandai Dibaca">
                        <i class="bi bi-x-lg fs-6"></i>
                    </button>
                </form>
            </div>
        @endforeach
    </div>
@endif

<div class="row g-4 mb-4">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Halo, {{ auth()->user()->name }}!</h5>
                <p class="text-muted mb-0">Selamat datang di {{ config('app.name') }}.</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <p class="text-muted small mb-1">Status Pesanan Terakhir</p>
                @if ($pesananTerakhir)
                    <h6 class="fw-bold mb-1">#{{ $pesananTerakhir->id_transaksi }}</h6>
                    @php
                        $badge = match ($pesananTerakhir->status) {
                            'selesai' => 'success',
                            'diproses' => 'warning',
                            default => 'secondary',
                        };
                    @endphp
                    <span class="badge text-bg-{{ $badge }}">{{ $pesananTerakhir->status }}</span>
                    <p class="small text-muted mt-2 mb-0">
                        {{ $pesananTerakhir->tanggal_transaksi?->format('d M Y H:i') }}
                    </p>
                @else
                    <p class="text-muted mb-0">Belum ada pesanan.</p>
                @endif
            </div>
        </div>
    </div>
</div>

<h5 class="fw-bold mb-3"><i class="bi bi-stars me-2"></i>Produk Terbaru</h5>
<div class="row g-3 mb-5">
    @forelse ($produkTerbaru as $produk)
        <div class="col-6 col-md-4 col-lg-2">
            <a href="{{ route('user.produk.show', $produk) }}" class="text-decoration-none text-reset">
                <div class="card product-card shadow-sm h-100">
                    <div class="card-body text-center p-3">
                        @if ($produk->gambar)
                            <img src="{{ asset('storage/'.$produk->gambar) }}" alt="{{ $produk->nama_produk }}"
                                 class="img-fluid rounded mb-2" style="height:80px;object-fit:cover">
                        @else
                            <div class="bg-body-secondary rounded d-flex align-items-center justify-content-center mb-2"
                                 style="height:80px">
                                <i class="bi bi-image text-muted fs-3"></i>
                            </div>
                        @endif
                        <h6 class="small fw-semibold mb-1 text-truncate" title="{{ $produk->nama_produk }}">
                            {{ $produk->nama_produk }}
                        </h6>
                        <p class="text-primary small fw-bold mb-0">
                            Rp {{ number_format($produk->harga_satuan, 0, ',', '.') }}
                        </p>
                        <small class="text-muted">Stok: {{ $produk->stok }}</small>
                    </div>
                </div>
            </a>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-light border text-center text-muted">Belum ada produk.</div>
        </div>
    @endforelse
</div>

<h5 class="fw-bold mb-3"><i class="bi bi-clock-history me-2"></i>Riwayat Pesanan</h5>
<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Tanggal</th>
                    <th>Total</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($riwayatPesanan as $pesanan)
                    <tr>
                        <td>#{{ $pesanan->id_transaksi }}</td>
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
                        <td colspan="4" class="text-center text-muted py-4">Belum ada riwayat pesanan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

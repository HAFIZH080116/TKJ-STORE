@extends('layouts.admin')

@section('title', 'Laporan Penjualan')
@section('page-title', 'Laporan & Rekap Penjualan')

@push('styles')
<link href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css" rel="stylesheet">
@endpush

@section('content')
<div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.laporan.index') }}" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label for="dari" class="form-label">Dari Tanggal</label>
                <input type="date" name="dari" id="dari" class="form-control" value="{{ $dari }}">
            </div>
            <div class="col-md-4">
                <label for="sampai" class="form-label">Sampai Tanggal</label>
                <input type="date" name="sampai" id="sampai" class="form-control" value="{{ $sampai }}">
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-funnel me-1"></i> Filter
                </button>
                <a href="{{ route('admin.laporan.index') }}" class="btn btn-outline-secondary">Reset</a>
            </div>
        </form>
        @if ($dari || $sampai)
            <p class="small text-muted mt-2 mb-0">
                Periode:
                {{ $dari ? \Carbon\Carbon::parse($dari)->format('d/m/Y') : 'Awal' }}
                —
                {{ $sampai ? \Carbon\Carbon::parse($sampai)->format('d/m/Y') : 'Akhir' }}
            </p>
        @else
            <p class="small text-muted mt-2 mb-0">Menampilkan seluruh data transaksi.</p>
        @endif
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card shadow-sm h-100">
            <div class="card-body">
                <p class="text-muted small mb-1">Total Pesanan</p>
                <h3 class="fw-bold mb-0">{{ number_format($totalPesanan) }}</h3>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card shadow-sm h-100 border-success border-opacity-25">
            <div class="card-body">
                <p class="text-muted small mb-1">Pesanan Selesai</p>
                <h3 class="fw-bold text-success mb-0">{{ number_format($pesananSelesai) }}</h3>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card shadow-sm h-100 border-primary border-opacity-25">
            <div class="card-body">
                <p class="text-muted small mb-1">Total Pendapatan</p>
                <h3 class="fw-bold text-primary mb-0">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h3>
                <small class="text-muted">Status selesai</small>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card shadow-sm h-100">
            <div class="card-body">
                <p class="text-muted small mb-1">Rata-rata / Transaksi</p>
                <h3 class="fw-bold mb-0">Rp {{ number_format($rataRataTransaksi, 0, ',', '.') }}</h3>
                <small class="text-muted">Hanya selesai</small>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white fw-semibold">Statistik Status</div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item d-flex justify-content-between">
                    <span><span class="badge text-bg-secondary">pending</span></span>
                    <strong>{{ $pesananPending }}</strong>
                </li>
                <li class="list-group-item d-flex justify-content-between">
                    <span><span class="badge text-bg-warning">diproses</span></span>
                    <strong>{{ $pesananDiproses }}</strong>
                </li>
                <li class="list-group-item d-flex justify-content-between">
                    <span><span class="badge text-bg-success">selesai</span></span>
                    <strong>{{ $pesananSelesai }}</strong>
                </li>
            </ul>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white fw-semibold">Rekap Nominal per Status</div>
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Status</th>
                            <th>Jumlah Pesanan</th>
                            <th>Total Nominal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($rekapPerStatus as $row)
                            <tr>
                                <td><span class="badge text-bg-secondary">{{ $row->status }}</span></td>
                                <td>{{ $row->jumlah }}</td>
                                <td>Rp {{ number_format($row->nominal, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted">Tidak ada data.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white fw-semibold">
        <i class="bi bi-trophy me-2"></i>Produk Terlaris (Top 10)
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Produk</th>
                    <th>Terjual (pcs)</th>
                    <th>Pendapatan</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($produkTerlaris as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->produk?->nama_produk ?? 'ID '.$item->id_produk }}</td>
                        <td>{{ number_format($item->total_terjual) }}</td>
                        <td>Rp {{ number_format($item->total_pendapatan, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">Belum ada penjualan selesai.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white fw-semibold">
        <i class="bi bi-table me-2"></i>Rekap Transaksi
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover w-100" id="tableRekap">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Tanggal</th>
                        <th>User</th>
                        <th>Total</th>
                        <th>Metode</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($rekapTransaksi as $trx)
                        <tr>
                            <td>#{{ $trx->id_transaksi }}</td>
                            <td>{{ $trx->tanggal_transaksi?->format('d/m/Y H:i') }}</td>
                            <td>{{ $trx->user?->name }}</td>
                            <td>Rp {{ number_format($trx->total_pembayaran, 0, ',', '.') }}</td>
                            <td>{{ $trx->metode_pembayaran }}</td>
                            <td>
                                @php
                                    $badge = match ($trx->status) {
                                        'selesai' => 'success',
                                        'diproses' => 'warning',
                                        default => 'secondary',
                                    };
                                @endphp
                                <span class="badge text-bg-{{ $badge }}">{{ $trx->status }}</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
<script>
    @if ($rekapTransaksi->count() > 0)
    $('#tableRekap').DataTable({
        order: [[0, 'desc']],
        pageLength: 10,
        language: { url: '//cdn.datatables.net/plug-ins/1.13.8/i18n/id.json' }
    });
    @endif
</script>
@endpush

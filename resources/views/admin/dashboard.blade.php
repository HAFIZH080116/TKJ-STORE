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

<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-body-tertiary">
        <h5 class="mb-0 fw-bold"><i class="bi bi-graph-up-arrow me-2 text-primary"></i>Grafik Pendapatan (7 Hari Terakhir)</h5>
    </div>
    <div class="card-body">
        <div style="height: 240px; position: relative;">
            <canvas id="salesChart"></canvas>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-body-tertiary">
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

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('salesChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($salesLabels) !!},
                datasets: [{
                    label: 'Pendapatan',
                    data: {!! json_encode($salesData) !!},
                    borderColor: '#0d6efd',
                    backgroundColor: 'rgba(13, 110, 253, 0.05)',
                    tension: 0.35,
                    fill: true,
                    borderWidth: 3,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    pointBackgroundColor: '#0d6efd',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return ' Pendapatan: Rp ' + context.raw.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        min: 0,
                        suggestedMax: 10000000,
                        grid: {
                            color: 'rgba(128, 128, 128, 0.15)'
                        },
                        ticks: {
                            stepSize: 2000000,
                            callback: function(value) {
                                if (value >= 1000000) {
                                    return 'Rp ' + (value / 1000000) + ' Jt';
                                }
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });
    });
</script>
@endpush

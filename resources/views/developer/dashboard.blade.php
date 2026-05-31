@extends('layouts.developer')

@section('title', 'Dashboard Developer')
@section('page-title', 'Dashboard Developer')

@section('content')
<!-- Welcome Alert -->
<div class="alert alert-info border-0 shadow-sm mb-4 d-flex align-items-center gap-3">
    <span class="fs-3"><i class="bi bi-shield-check"></i></span>
    <div>
        <h6 class="alert-heading fw-bold mb-1">Selamat datang kembali, {{ auth()->user()->name }}!</h6>
        <p class="small mb-0">Semua sistem termonitoring dengan baik. Di bawah ini adalah ringkasan performa dan log aktivitas terkini.</p>
    </div>
</div>

<!-- Stats Cards -->
<div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted small mb-1">Versi Sistem</p>
                        <h4 class="fw-bold mb-0 text-primary">{{ $appVersion }}</h4>
                        <small class="text-muted">{{ $versionCount }} rilis tercatat</small>
                    </div>
                    <span class="badge text-bg-primary rounded-pill p-3"><i class="bi bi-tags fs-4"></i></span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted small mb-1">Bug Report Aktif</p>
                        <h4 class="fw-bold mb-0 text-danger">{{ $openBugs }}</h4>
                        <small class="text-muted">{{ $resolvedBugs }} terselesaikan</small>
                    </div>
                    <span class="badge text-bg-danger rounded-pill p-3"><i class="bi bi-bug fs-4"></i></span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted small mb-1">Total Log Aktivitas</p>
                        <h4 class="fw-bold mb-0 text-success">{{ $logCount }}</h4>
                        <small class="text-muted">Audit trail sistem</small>
                    </div>
                    <span class="badge text-bg-success rounded-pill p-3"><i class="bi bi-journal-text fs-4"></i></span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted small mb-1">Server Environment</p>
                        <h4 class="fw-bold mb-0 text-warning text-uppercase">{{ $environment }}</h4>
                        <small class="text-muted">Debug: {{ $debugMode }}</small>
                    </div>
                    <span class="badge text-bg-warning rounded-pill p-3"><i class="bi bi-hdd-network fs-4"></i></span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- System Health -->
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="mb-0 fw-bold"><i class="bi bi-activity me-2 text-info"></i>Kesehatan Sistem</h5>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between px-0 py-3 border-light">
                        <span class="text-muted">Database Connection</span>
                        <span class="badge text-bg-{{ str_contains($dbStatus, 'Terhubung') ? 'success' : 'danger' }} px-3 py-2">
                            {{ str_contains($dbStatus, 'Terhubung') ? 'Active' : 'Disconnected' }}
                        </span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between px-0 py-3 border-light">
                        <span class="text-muted">Database Engine</span>
                        <span class="fw-semibold text-secondary">MySQL / MariaDB</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between px-0 py-3 border-light">
                        <span class="text-muted">PHP Version</span>
                        <code>{{ $phpVersion }}</code>
                    </li>
                    <li class="list-group-item d-flex justify-content-between px-0 py-3 border-light">
                        <span class="text-muted">Laravel Framework</span>
                        <code>v{{ $laravelVersion }}</code>
                    </li>
                </ul>
                <div class="text-center mt-3">
                    <a href="{{ route('developer.monitoring') }}" class="btn btn-sm btn-outline-info w-100">
                        <i class="bi bi-cpu me-1"></i> Detail Monitoring Sistem
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Logs -->
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold"><i class="bi bi-clock-history me-2 text-primary"></i>Log Aktivitas Terbaru</h5>
                <a href="{{ route('developer.log.index') }}" class="btn btn-sm btn-link text-decoration-none">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light text-muted small">
                            <tr>
                                <th class="ps-4">Pengguna</th>
                                <th>Aktivitas</th>
                                <th class="pe-4">Waktu</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($recentLogs as $log)
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold small">{{ $log->user?->name ?? 'System' }}</div>
                                        <span class="badge text-bg-secondary px-2 py-1 font-monospace" style="font-size: 0.65rem;">
                                            {{ $log->user?->role ?? 'system' }}
                                        </span>
                                    </td>
                                    <td class="small">{{ $log->aktivitas }}</td>
                                    <td class="pe-4 text-muted small" style="white-space: nowrap;">
                                        {{ $log->tanggal_log->format('d M Y, H:i') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-5 text-muted">Belum ada log aktivitas tercatat.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.developer')

@section('title', 'Dashboard Developer')
@section('page-title', 'Dashboard Developer')

@section('content')
<div class="row g-4 mb-4" id="monitoring">
    <div class="col-md-6 col-xl-3">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <p class="text-muted small mb-1">Versi Sistem</p>
                <h4 class="fw-bold mb-0">{{ $appVersion }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <p class="text-muted small mb-1">PHP</p>
                <h4 class="fw-bold mb-0">{{ $phpVersion }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <p class="text-muted small mb-1">Laravel</p>
                <h4 class="fw-bold mb-0">{{ $laravelVersion }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <p class="text-muted small mb-1">Environment</p>
                <h4 class="fw-bold mb-0 text-capitalize">{{ $environment }}</h4>
                <small class="text-muted">Debug: {{ $debugMode }}</small>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-activity me-2 text-info"></i>Monitoring Sistem</h5>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between px-0">
                        <span>Database</span>
                        <span class="badge text-bg-{{ str_contains($dbStatus, 'Terhubung') ? 'success' : 'danger' }}">
                            {{ $dbStatus }}
                        </span>
                    </li>
                    @if ($dbError)
                        <li class="list-group-item px-0">
                            <small class="text-danger">{{ $dbError }}</small>
                        </li>
                    @endif
                    <li class="list-group-item d-flex justify-content-between px-0">
                        <span>Session driver</span>
                        <code>{{ config('session.driver') }}</code>
                    </li>
                    <li class="list-group-item d-flex justify-content-between px-0">
                        <span>Cache store</span>
                        <code>{{ config('cache.default') }}</code>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-bug me-2 text-warning"></i>Bug Report & Log</h5>
            </div>
            <div class="card-body">
                <p class="text-muted mb-3">Modul bug report dan log aktivitas akan diimplementasi pada pengembangan berikutnya.</p>
                <div class="d-flex gap-2 flex-wrap">
                    <span class="badge text-bg-secondary"><i class="bi bi-bug me-1"></i> Bug Report: 0</span>
                    <span class="badge text-bg-secondary"><i class="bi bi-journal-text me-1"></i> Log: 0</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.developer')

@section('title', 'Monitoring Sistem')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0"><i class="bi bi-activity me-2 text-info"></i>Monitoring Performa & Sistem</h4>
    <a href="{{ route('developer.dashboard') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
    </a>
</div>

<div class="row g-4">
    <!-- Server Hardware & OS -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-0 py-3">
                <h6 class="mb-0 fw-bold text-secondary"><i class="bi bi-cpu me-2"></i>Informasi Server & Lingkungan</h6>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0 align-middle">
                    <tbody>
                        <tr>
                            <td class="ps-4 text-muted py-3">Operating System</td>
                            <td class="pe-4 fw-semibold text-end">{{ $monitoringData['os'] }}</td>
                        </tr>
                        <tr>
                            <td class="ps-4 text-muted py-3">Server Web Software</td>
                            <td class="pe-4 fw-semibold text-end text-truncate" style="max-width: 250px;">{{ $monitoringData['server_software'] }}</td>
                        </tr>
                        <tr>
                            <td class="ps-4 text-muted py-3">Default Timezone</td>
                            <td class="pe-4 fw-semibold text-end">{{ $monitoringData['timezone'] }}</td>
                        </tr>
                        <tr>
                            <td class="ps-4 text-muted py-3">Loaded Extensions</td>
                            <td class="pe-4 fw-semibold text-end"><span class="badge bg-secondary">{{ $monitoringData['loaded_extensions'] }} ekstensi</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3">
                <h6 class="mb-0 fw-bold text-secondary"><i class="bi bi-hdd-network me-2"></i>Konfigurasi Basis Data</h6>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0 align-middle">
                    <tbody>
                        <tr>
                            <td class="ps-4 text-muted py-3">Driver Connection</td>
                            <td class="pe-4 fw-semibold text-end"><code>{{ $monitoringData['db_connection'] }}</code></td>
                        </tr>
                        <tr>
                            <td class="ps-4 text-muted py-3">Database Host / Port</td>
                            <td class="pe-4 fw-semibold text-end">{{ $monitoringData['db_host'] }} : {{ $monitoringData['db_port'] }}</td>
                        </tr>
                        <tr>
                            <td class="ps-4 text-muted py-3">Schema Name</td>
                            <td class="pe-4 fw-semibold text-end"><code>{{ $monitoringData['db_database'] }}</code></td>
                        </tr>
                        <tr>
                            <td class="ps-4 text-muted py-3">Database Status</td>
                            <td class="pe-4 text-end">
                                <span class="badge text-bg-success px-3 py-2"><i class="bi bi-check-circle-fill me-1"></i> {{ $monitoringData['db_status'] }}</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Memory & PHP Config -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 py-3">
                <h6 class="mb-0 fw-bold text-secondary"><i class="bi bi-sliders me-2"></i>Alokasi Memori & Konfigurasi PHP (php.ini)</h6>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0 align-middle">
                    <tbody>
                        <tr>
                            <td class="ps-4 text-muted py-3">Memory Limit</td>
                            <td class="pe-4 fw-semibold text-end"><code>{{ $monitoringData['memory_limit'] }}</code></td>
                        </tr>
                        <tr>
                            <td class="ps-4 text-muted py-3">Current PHP Memory Usage</td>
                            <td class="pe-4 text-end">
                                <div class="fw-bold text-primary">{{ $monitoringData['memory_usage'] }}</div>
                                <div class="progress mt-1" style="height: 5px;">
                                    <div class="progress-bar bg-primary" role="progressbar" style="width: 15%"></div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="ps-4 text-muted py-3">Peak PHP Memory Usage</td>
                            <td class="pe-4 text-end">
                                <div class="fw-bold text-info">{{ $monitoringData['memory_peak'] }}</div>
                            </td>
                        </tr>
                        <tr>
                            <td class="ps-4 text-muted py-3">Post Max Size (POST Limit)</td>
                            <td class="pe-4 fw-semibold text-end"><code>{{ $monitoringData['post_max_size'] }}</code></td>
                        </tr>
                        <tr>
                            <td class="ps-4 text-muted py-3">Upload Max Filesize (File Limit)</td>
                            <td class="pe-4 fw-semibold text-end"><code>{{ $monitoringData['upload_max_filesize'] }}</code></td>
                        </tr>
                        <tr>
                            <td class="ps-4 text-muted py-3">Max Execution Time</td>
                            <td class="pe-4 fw-semibold text-end">{{ $monitoringData['max_execution_time'] }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

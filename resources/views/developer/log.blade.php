@extends('layouts.developer')

@section('title', 'Log Aktivitas')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0"><i class="bi bi-journal-text me-2 text-info"></i>Audit Trail & Log Aktivitas Sistem</h4>
    <a href="{{ route('developer.dashboard') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
    </a>
</div>

<!-- Logs List -->
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-muted small">
                    <tr>
                        <th class="ps-4">Log ID</th>
                        <th>Nama Pengguna</th>
                        <th>Hak Akses (Role)</th>
                        <th>Pesan Aktivitas</th>
                        <th class="pe-4">Tanggal & Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($logs as $log)
                        <tr>
                            <td class="ps-4 font-monospace text-muted small">
                                #{{ $log->id_log }}
                            </td>
                            <td class="small fw-bold text-body py-3">
                                {{ $log->user?->name ?? 'System' }}
                            </td>
                            <td>
                                @php
                                    $badge = match ($log->user?->role) {
                                        'admin' => 'danger',
                                        'developer' => 'info text-dark',
                                        'user' => 'success',
                                        default => 'secondary',
                                    };
                                @endphp
                                <span class="badge text-bg-{{ $badge }} px-3 py-1 text-capitalize font-monospace" style="font-size: 0.7rem;">
                                    {{ $log->user?->role ?? 'system' }}
                                </span>
                            </td>
                            <td class="small text-secondary py-3">
                                {{ $log->aktivitas }}
                            </td>
                            <td class="pe-4 small text-muted font-monospace" style="white-space: nowrap;">
                                {{ $log->tanggal_log->format('d/m/Y - H:i:s') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">Belum ada log aktivitas sistem.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-4">
    {{ $logs->links() }}
</div>
@endsection

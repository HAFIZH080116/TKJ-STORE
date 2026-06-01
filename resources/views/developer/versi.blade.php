@extends('layouts.developer')

@section('title', 'Kelola Versi')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0"><i class="bi bi-tags me-2 text-info"></i>Riwayat & Manajemen Versi Sistem</h4>
    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addVersionModal">
        <i class="bi bi-plus-circle me-1"></i> Tambah Rilis Baru
    </button>
</div>

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4">
        <ul class="mb-0 small">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Versions List -->
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-muted small">
                    <tr>
                        <th class="ps-4">Versi</th>
                        <th>Tanggal Rilis</th>
                        <th>Deskripsi Pembaruan / Changelog</th>
                        <th class="text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($versions as $v)
                        <tr>
                            <td class="ps-4">
                                <span class="badge text-bg-info px-3 py-2 fw-bold font-monospace">
                                    {{ $v->versi }}
                                </span>
                            </td>
                            <td class="small fw-semibold text-secondary">
                                {{ $v->tanggal_rilis->format('d M Y') }}
                            </td>
                            <td class="small text-muted py-3" style="max-width: 500px;">
                                {!! nl2br(e($v->deskripsi)) !!}
                            </td>
                            <td class="text-end pe-4">
                                <form method="POST" action="{{ route('developer.versi.destroy', $v->id_version) }}"
                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus catatan rilis ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">Belum ada riwayat rilis terdaftar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-4">
    {{ $versions->links() }}
</div>

<!-- Add Version Modal -->
<div class="modal fade" id="addVersionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-dark text-white border-0 py-3">
                <h5 class="modal-title fw-bold"><i class="bi bi-plus-circle me-2"></i>Tambah Rilis Versi Baru</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('developer.versi.store') }}">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label for="versi" class="form-label fw-semibold text-secondary">Nomor Versi (e.g. v1.1.0)</label>
                        <input type="text" name="versi" id="versi" class="form-control" placeholder="v1.1.0" required>
                    </div>
                    <div class="mb-3">
                        <label for="tanggal_rilis" class="form-label fw-semibold text-secondary">Tanggal Rilis</label>
                        <input type="date" name="tanggal_rilis" id="tanggal_rilis" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="deskripsi" class="form-label fw-semibold text-secondary">Deskripsi & Changelog</label>
                        <textarea name="deskripsi" id="deskripsi" rows="5" class="form-control" placeholder="Tuliskan detail pembaruan sistem..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 p-3 bg-body-secondary">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm px-4">Simpan Rilis</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

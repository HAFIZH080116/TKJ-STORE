@extends('layouts.developer')

@section('title', 'Bug Report')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0"><i class="bi bi-bug me-2 text-warning"></i>Bug Report & Troubleshooting</h4>
    <button class="btn btn-sm btn-warning text-white fw-semibold" data-bs-toggle="modal" data-bs-target="#addBugModal">
        <i class="bi bi-exclamation-triangle me-1"></i> Laporkan Masalah Baru
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

<!-- Bug Reports List -->
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-muted small">
                    <tr>
                        <th class="ps-4">Status</th>
                        <th>Judul Masalah</th>
                        <th>Rincian & Deskripsi Bug</th>
                        <th>Tanggal Dilaporkan</th>
                        <th class="text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($bugs as $bug)
                        <tr>
                            <td class="ps-4">
                                <span class="badge text-bg-{{ $bug->status === 'resolved' ? 'success' : 'danger' }} px-3 py-2">
                                    {{ $bug->status === 'resolved' ? 'Resolved' : 'Open' }}
                                </span>
                            </td>
                            <td class="small fw-bold text-dark py-3" style="max-width: 200px;">
                                {{ $bug->judul }}
                            </td>
                            <td class="small text-muted py-3" style="max-width: 400px;">
                                {!! nl2br(e($bug->deskripsi)) !!}
                            </td>
                            <td class="small text-secondary" style="white-space: nowrap;">
                                {{ $bug->tanggal_dilaporkan->format('d M Y, H:i') }}
                            </td>
                            <td class="text-end pe-4" style="white-space: nowrap;">
                                <div class="d-flex gap-2 justify-content-end">
                                    @if ($bug->status === 'open')
                                        <form method="POST" action="{{ route('developer.bug.resolve', $bug->id_bug) }}">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-sm btn-success text-white py-1 px-2" title="Tandai Selesai">
                                                <i class="bi bi-check-circle"></i> Selesai
                                            </button>
                                        </form>
                                    @endif
                                    <form method="POST" action="{{ route('developer.bug.destroy', $bug->id_bug) }}"
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus laporan bug ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger py-1 px-2" title="Hapus Laporan">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">Hebat! Belum ada laporan bug tercatat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-4">
    {{ $bugs->links() }}
</div>

<!-- Add Bug Modal -->
<div class="modal fade" id="addBugModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-dark text-white border-0 py-3">
                <h5 class="modal-title fw-bold text-warning"><i class="bi bi-exclamation-triangle me-2"></i>Laporkan Bug Sistem Baru</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('developer.bug.store') }}">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label for="judul" class="form-label fw-semibold text-secondary">Judul Laporan</label>
                        <input type="text" name="judul" id="judul" class="form-control" placeholder="Contoh: Gagal upload gambar produk size 1.5MB" required>
                    </div>
                    <div class="mb-3">
                        <label for="deskripsi" class="form-label fw-semibold text-secondary">Deskripsi Masalah / Rincian Bug</label>
                        <textarea name="deskripsi" id="deskripsi" rows="5" class="form-control" placeholder="Jelaskan secara rinci detail error, langkah reproduksi, atau pesan kegagalan sistem..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 p-3 bg-light">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning text-white fw-semibold btn-sm px-4">Kirim Laporan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

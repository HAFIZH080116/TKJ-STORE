@extends('layouts.admin')

@section('title', 'Kelola Produk')
@section('page-title', 'Kelola Produk')

@push('styles')
<link href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css" rel="stylesheet">
@endpush

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
    <form method="GET" action="{{ route('admin.produk.index') }}" class="d-flex gap-2 flex-grow-1" style="max-width:420px">
        <input type="text" name="q" class="form-control" placeholder="Cari nama produk..."
               value="{{ request('q') }}">
        <button type="submit" class="btn btn-outline-primary"><i class="bi bi-search"></i></button>
        @if (request('q'))
            <a href="{{ route('admin.produk.index') }}" class="btn btn-outline-secondary">Reset</a>
        @endif
    </form>
    <a href="{{ route('admin.produk.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Tambah Produk
    </a>
</div>

@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card shadow-sm border-0">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle w-100" id="tableProduk">
                <thead class="table-light">
                    <tr>
                        <th width="60">#</th>
                        <th width="80">Gambar</th>
                        <th>Nama Produk</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th width="180" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($produks as $produk)
                        <tr>
                            <td>{{ $produk->id_produk }}</td>
                            <td>
                                @if ($produk->gambar)
                                    <img src="{{ asset('storage/'.$produk->gambar) }}" alt="{{ $produk->nama_produk }}"
                                         class="rounded" width="48" height="48" style="object-fit:cover">
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>{{ $produk->nama_produk }}</td>
                            <td>Rp {{ number_format($produk->harga_satuan, 0, ',', '.') }}</td>
                            <td>
                                <span class="badge text-bg-{{ $produk->stok > 0 ? 'success' : 'danger' }}">
                                    {{ $produk->stok }}
                                </span>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.produk.show', $produk) }}" class="btn btn-sm btn-info text-white" title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('admin.produk.edit', $produk) }}" class="btn btn-sm btn-warning text-white" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.produk.destroy', $produk) }}" method="POST" class="d-inline form-hapus">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Belum ada data produk.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $produks->links() }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    @if ($produks->count() > 0)
    $('#tableProduk').DataTable({
        paging: false,
        searching: false,
        info: false,
        order: [],
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.8/i18n/id.json'
        }
    });
    @endif

    document.querySelectorAll('.form-hapus').forEach(function (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            Swal.fire({
                title: 'Hapus produk?',
                text: 'Data yang dihapus tidak dapat dikembalikan.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal'
            }).then(function (result) {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

    @if (session('success'))
    Swal.fire({
        icon: 'success',
        title: 'Berhasil',
        text: @json(session('success')),
        timer: 2500,
        showConfirmButton: false
    });
    @endif
</script>
@endpush

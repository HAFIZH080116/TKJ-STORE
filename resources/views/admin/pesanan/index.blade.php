@extends('layouts.admin')

@section('title', 'Kelola Pesanan')
@section('page-title', 'Kelola Pesanan')

@section('content')
<div class="d-flex flex-wrap gap-2 mb-4">
    <form method="GET" class="d-flex gap-2 flex-grow-1" style="max-width:480px">
        <input type="text" name="q" class="form-control" placeholder="Cari nama/email user..." value="{{ request('q') }}">
        <select name="status" class="form-select" style="max-width:160px">
            <option value="">Semua status</option>
            @foreach (['pending', 'diproses', 'selesai'] as $s)
                <option value="{{ $s }}" @selected(request('status') === $s)>{{ $s }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i></button>
        @if (request()->hasAny(['q', 'status']))
            <a href="{{ route('admin.pesanan.index') }}" class="btn btn-outline-secondary">Reset</a>
        @endif
    </form>
</div>

<div class="card shadow-sm border-0">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>Tanggal</th>
                    <th>Total</th>
                    <th>Metode</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pesanan as $item)
                    <tr>
                        <td>#{{ $item->id_transaksi }}</td>
                        <td>
                            <div class="fw-semibold">{{ $item->user?->name }}</div>
                            <small class="text-muted">{{ $item->user?->email }}</small>
                        </td>
                        <td>{{ $item->tanggal_transaksi?->format('d/m/Y H:i') }}</td>
                        <td>Rp {{ number_format($item->total_pembayaran, 0, ',', '.') }}</td>
                        <td>{{ $item->metode_pembayaran }}</td>
                        <td>
                            @php
                                $badge = match ($item->status) {
                                    'selesai' => 'success',
                                    'diproses' => 'warning',
                                    default => 'secondary',
                                };
                            @endphp
                            <span class="badge text-bg-{{ $badge }}">{{ $item->status }}</span>
                        </td>
                        <td>
                            <a href="{{ route('admin.pesanan.show', $item) }}" class="btn btn-sm btn-info text-white">Detail</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">Belum ada pesanan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-white">{{ $pesanan->links() }}</div>
</div>
@endsection

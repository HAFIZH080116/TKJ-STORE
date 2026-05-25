@extends('layouts.user')

@section('title', 'Riwayat Pesanan')

@section('content')
<h4 class="fw-bold mb-4"><i class="bi bi-clock-history me-2"></i>Riwayat Pesanan</h4>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
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
                            <a href="{{ route('user.pesanan.show', $item) }}" class="btn btn-sm btn-outline-primary">Detail</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">Belum ada pesanan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($pesanan->hasPages())
        <div class="card-footer bg-white">{{ $pesanan->links() }}</div>
    @endif
</div>
@endsection

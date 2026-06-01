@extends('layouts.admin')

@section('title', 'Detail Pesanan #' . $pesanan->id_transaksi)
@section('page-title', 'Detail Pesanan #' . $pesanan->id_transaksi)

@php
    $metode = $pesanan->metode_pembayaran;
    $isKompleks = false;
    $detailPembayaran = $metode;
    $detailPengiriman = null;

    if (str_contains($metode, ' | ')) {
        $parts = explode(' | ', $metode);
        if (count($parts) >= 2) {
            $isKompleks = true;
            $detailPembayaran = $parts[0];
            $detailPengiriman = $parts[1];
        }
    }

    $kurirLayanan = '';
    $penerima = '';
    $telp = '';
    $alamat = '';
    $catatan = '';

    if ($isKompleks && $detailPengiriman) {
        if (preg_match('/^(.*?)\((.*)\)$/', $detailPengiriman, $matches)) {
            $kurirLayanan = trim($matches[1]);
            $inner = $matches[2];
            
            preg_match('/Penerima:\s*(.*?),/', $inner, $matchP);
            preg_match('/Telp:\s*(.*?),/', $inner, $matchT);
            
            // Match Alamat and Catatan
            if (str_contains($inner, 'Catatan:')) {
                preg_match('/Alamat:\s*(.*?),\s*Catatan:\s*(.*)$/', $inner, $matchAC);
                if (!empty($matchAC)) {
                    $alamat = trim($matchAC[1]);
                    $catatan = trim($matchAC[2]);
                } else {
                    preg_match('/Alamat:\s*(.*?)$/', $inner, $matchA);
                    $alamat = isset($matchA[1]) ? trim($matchA[1]) : '';
                }
            } else {
                preg_match('/Alamat:\s*(.*?)$/', $inner, $matchA);
                $alamat = isset($matchA[1]) ? trim($matchA[1]) : '';
                $catatan = 'Tidak ada';
            }

            $penerima = isset($matchP[1]) ? trim($matchP[1]) : '';
            $telp = isset($matchT[1]) ? trim($matchT[1]) : '';
        }
    }
@endphp

@section('content')
<div class="row g-4 mb-4">
    <!-- Left: Order and Customer Information -->
    <div class="col-lg-8">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-body-tertiary fw-semibold py-3"><i class="bi bi-info-circle me-2 text-primary"></i>Informasi Pesanan Utama</div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-md-6 col-lg-3">
                        <small class="text-muted d-block">ID Transaksi</small>
                        <div class="fw-bold fs-6 text-primary">#{{ $pesanan->id_transaksi }}</div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <small class="text-muted d-block">Tanggal Transaksi</small>
                        <div class="fw-semibold">{{ $pesanan->tanggal_transaksi?->format('d M Y H:i') }}</div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <small class="text-muted d-block">Pembeli Utama</small>
                        <div class="fw-semibold">{{ $pesanan->user?->name }}</div>
                        <small class="text-muted">{{ $pesanan->user?->email }}</small>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <small class="text-muted d-block">Total Nominal</small>
                        <div class="fw-bold text-success fs-5">Rp {{ number_format($pesanan->total_pembayaran, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Payment box -->
            <div class="col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-body-tertiary fw-bold py-3"><i class="bi bi-wallet2 me-2 text-primary"></i>Metode & Status Bayar</div>
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <small class="text-muted d-block mb-1">Metode Pilihan Pelanggan</small>
                            <span class="fw-bold text-body fs-6"><i class="bi bi-shield-check text-success me-1"></i>{{ $detailPembayaran }}</span>
                        </div>

                        @if(str_contains($detailPembayaran, 'Virtual Account'))
                            @php
                                $vaCodes = [
                                    'BCA Virtual Account' => '88301' . str_pad($pesanan->id_transaksi, 11, '0', STR_PAD_LEFT),
                                    'Mandiri Virtual Account' => '89407' . str_pad($pesanan->id_transaksi, 11, '0', STR_PAD_LEFT),
                                    'BNI Virtual Account' => '82702' . str_pad($pesanan->id_transaksi, 11, '0', STR_PAD_LEFT),
                                ];
                                $vaCode = $vaCodes[$detailPembayaran] ?? '8800000000000000';
                            @endphp
                            <div class="bg-body-secondary rounded p-3 mb-2">
                                <small class="text-muted d-block mb-1">Nomor Virtual Account Simulasi</small>
                                <code class="fs-6 fw-bold text-primary">{{ $vaCode }}</code>
                            </div>
                        @elseif(str_contains($detailPembayaran, 'QRIS'))
                            <div class="bg-body-secondary rounded p-3 mb-2 text-center">
                                <i class="bi bi-qr-code text-success fs-1 mb-1"></i>
                                <span class="d-block small text-success fw-bold">QRIS Pembayaran Terdaftar</span>
                            </div>
                        @elseif($detailPembayaran === 'COD')
                            <div class="bg-body-secondary rounded p-3 mb-2">
                                <span class="d-block small fw-bold text-primary"><i class="bi bi-cash-stack me-1"></i>Cash on Delivery (COD)</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Shipping box -->
            <div class="col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-body-tertiary fw-bold py-3"><i class="bi bi-truck me-2 text-info"></i>Tujuan & Ekspedisi Pengiriman</div>
                    <div class="card-body p-4">
                        @if ($isKompleks && $penerima)
                            <div class="row g-2">
                                <div class="col-6">
                                    <small class="text-muted d-block">Nama Penerima</small>
                                    <span class="fw-semibold">{{ $penerima }}</span>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted d-block">No. HP</small>
                                    <span class="fw-semibold">{{ $telp }}</span>
                                </div>
                                <div class="col-12">
                                    <small class="text-muted d-block">Kurir</small>
                                    <span class="fw-bold text-info"><i class="bi bi-box me-1"></i>{{ $kurirLayanan }}</span>
                                </div>
                                <div class="col-12">
                                    <small class="text-muted d-block">Alamat Lengkap</small>
                                    <p class="mb-0 small text-body">{{ $alamat }}</p>
                                </div>
                                <div class="col-12">
                                    <small class="text-muted d-block">Catatan Pengiriman</small>
                                    <span class="badge text-bg-secondary fw-normal text-start text-wrap d-inline-block">{{ $catatan }}</span>
                                </div>
                            </div>
                        @else
                            <small class="text-muted d-block">Detail Tujuan Pengiriman</small>
                            <p class="mb-0 small">{{ $metode }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right: Update Status Form -->
    <div class="col-lg-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-body-tertiary fw-semibold py-3"><i class="bi bi-sliders me-2 text-warning"></i>Proses & Ubah Status</div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('admin.pesanan.update-status', $pesanan) }}">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="status" class="form-label fw-semibold text-secondary">Status Pesanan Saat Ini</label>
                        <select name="status" id="status" class="form-select" required>
                            @foreach (['pending', 'diproses', 'selesai'] as $s)
                                <option value="{{ $s }}" @selected($pesanan->status === $s)>{{ strtoupper($s) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
                        <i class="bi bi-save me-1"></i> Simpan Pembaruan Status
                    </button>
                </form>
                
                <div class="alert alert-secondary border-0 p-3 mt-4 small">
                    <i class="bi bi-info-circle-fill text-primary me-1"></i> <strong>Panduan Status:</strong>
                    <ul class="mb-0 ps-3 mt-2">
                        <li><strong>PENDING</strong>: Pesanan baru masuk menunggu konfirmasi pembayaran.</li>
                        <li><strong>DIPROSES</strong>: Pesanan telah dikonfirmasi dan sedang dikemas/dikirim via ekspedisi.</li>
                        <li><strong>SELESAI</strong>: Pesanan telah sampai di tangan pelanggan dan transaksi ditutup.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Purchased Products Card -->
<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-body-tertiary fw-semibold py-3"><i class="bi bi-box-seam me-2 text-info"></i>Daftar Item Pembelian</div>
    <div class="table-responsive">
        <table class="table mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th>Item Produk</th>
                    <th>Harga Satuan</th>
                    <th class="text-center">Kuantitas</th>
                    <th class="text-end pe-4">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pesanan->detailTransaksi as $detail)
                    <tr>
                        <td>
                            <div class="fw-semibold">{{ $detail->produk?->nama_produk ?? 'ID ' . $detail->id_produk }}</div>
                            <small class="text-muted">Kategori: {{ $detail->produk?->kategori ?? 'Lainnya' }}</small>
                        </td>
                        <td>Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                        <td class="text-center">{{ $detail->jumlah }} unit</td>
                        <td class="text-end pe-4 fw-semibold">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3" class="text-end py-3">Total Tagihan Transaksi:</th>
                    <th class="text-end pe-4 py-3 text-primary fs-5">Rp {{ number_format($pesanan->total_pembayaran, 0, ',', '.') }}</th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<div class="d-flex flex-wrap gap-2 mb-5">
    <a href="{{ route('admin.pesanan.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
    </a>
    <a href="{{ route('admin.pesanan.invoice', $pesanan->id_transaksi) }}" target="_blank" class="btn btn-primary">
        <i class="bi bi-printer me-1"></i> Cetak Struk / Invoice
    </a>
</div>
@endsection

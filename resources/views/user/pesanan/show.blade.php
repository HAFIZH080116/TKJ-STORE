@extends('layouts.user')

@section('title', 'Detail Pesanan #' . $pesanan->id_transaksi)

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
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb small">
        <li class="breadcrumb-item"><a href="{{ route('user.pesanan.index') }}">Riwayat Pesanan</a></li>
        <li class="breadcrumb-item active">#{{ $pesanan->id_transaksi }}</li>
    </ol>
</nav>

<!-- Main Pesanan Header Card -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div>
                <span class="text-muted small d-block">ID Transaksi</span>
                <h5 class="fw-bold mb-0">#{{ $pesanan->id_transaksi }}</h5>
            </div>
            <div>
                <span class="text-muted small d-block">Tanggal Pemesanan</span>
                <span class="fw-semibold">{{ $pesanan->tanggal_transaksi?->format('d M Y H:i') }}</span>
            </div>
            <div>
                <span class="text-muted small d-block">Status Pesanan</span>
                @php
                    $badge = match ($pesanan->status) {
                        'selesai' => 'success',
                        'diproses' => 'warning',
                        default => 'secondary',
                    };
                @endphp
                <span class="badge text-bg-{{ $badge }} px-3 py-2 text-capitalize">{{ $pesanan->status }}</span>
            </div>
            <div>
                <span class="text-muted small d-block">Total Pembayaran</span>
                <span class="fw-bold text-primary fs-5">Rp {{ number_format($pesanan->total_pembayaran, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Payment details box -->
    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-body-tertiary fw-bold py-3"><i class="bi bi-wallet2 me-2 text-primary"></i>Informasi Pembayaran</div>
            <div class="card-body p-4">
                <div class="mb-3">
                    <small class="text-muted d-block mb-1">Metode Pilihan</small>
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
                    <div class="bg-body-secondary rounded p-3 mb-3">
                        <small class="text-muted d-block mb-1">Nomor Rekening Virtual Account</small>
                        <div class="d-flex justify-content-between align-items-center">
                            <code class="fs-5 fw-bold text-primary">{{ $vaCode }}</code>
                            <button class="btn btn-sm btn-outline-primary border-0" onclick="navigator.clipboard.writeText('{{ $vaCode }}'); alert('Nomor VA berhasil disalin!');" title="Salin VA">
                                <i class="bi bi-copy"></i>
                            </button>
                        </div>
                    </div>
                    <div class="small text-muted">
                        <i class="bi bi-info-circle me-1"></i> <strong>Instruksi Transfer:</strong> Silakan masuk ke aplikasi Mobile Banking Anda, pilih menu transfer Virtual Account, masukkan nomor di atas, dan bayar sesuai nominal tagihan.
                    </div>
                @elseif(str_contains($detailPembayaran, 'QRIS'))
                    <div class="bg-body-secondary rounded p-3 mb-2 text-center">
                        <i class="bi bi-qr-code text-success fs-1 mb-2"></i>
                        <span class="d-block small text-success fw-bold"><i class="bi bi-check-circle-fill me-1"></i>QRIS Pembayaran Terdaftar</span>
                        <small class="text-muted" style="font-size: 0.75rem">Transaksi diproses otomatis via scan kode QR</small>
                    </div>
                @elseif($detailPembayaran === 'COD')
                    <div class="bg-body-secondary rounded p-3 mb-2">
                        <span class="d-block small fw-bold text-primary"><i class="bi bi-cash-stack me-1"></i>Bayar Cash on Delivery (COD)</span>
                        <small class="text-muted d-block mt-1">Harap siapkan uang tunai pas sebesar <strong>Rp {{ number_format($pesanan->total_pembayaran, 0, ',', '.') }}</strong> untuk diserahkan ke kurir pengantar saat paket tiba.</small>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Shipping details box -->
    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-body-tertiary fw-bold py-3"><i class="bi bi-truck me-2 text-info"></i>Informasi Pengiriman</div>
            <div class="card-body p-4">
                @if ($isKompleks && $penerima)
                    <div class="row g-3">
                        <div class="col-6">
                            <small class="text-muted d-block">Nama Penerima</small>
                            <span class="fw-semibold">{{ $penerima }}</span>
                        </div>
                        <div class="col-6">
                            <small class="text-muted d-block">No. HP Penerima</small>
                            <span class="fw-semibold">{{ $telp }}</span>
                        </div>
                        <div class="col-12">
                            <small class="text-muted d-block">Kurir Layanan</small>
                            <span class="fw-bold text-info"><i class="bi bi-box me-1"></i>{{ $kurirLayanan }}</span>
                        </div>
                        <div class="col-12">
                            <small class="text-muted d-block">Alamat Pengiriman</small>
                            <p class="mb-0 small text-body">{{ $alamat }}</p>
                        </div>
                        <div class="col-12">
                            <small class="text-muted d-block">Catatan Pengiriman</small>
                            <span class="badge text-bg-secondary fw-normal text-start text-wrap d-inline-block">{{ $catatan }}</span>
                        </div>
                    </div>
                @else
                    <small class="text-muted d-block">Detail Tujuan Pengiriman</small>
                    <p class="mb-0">{{ $metode }}</p>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-body-tertiary fw-semibold py-3">Daftar Produk yang Dibeli</div>
    <div class="table-responsive">
        <table class="table mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th>Nama Produk</th>
                    <th>Harga Satuan</th>
                    <th class="text-center">Jumlah</th>
                    <th class="text-end pe-4">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pesanan->detailTransaksi as $detail)
                    <tr>
                        <td>
                            <div class="fw-semibold">{{ $detail->produk?->nama_produk ?? 'Produk #' . $detail->id_produk }}</div>
                            <small class="text-muted">Kategori: {{ $detail->produk?->kategori ?? 'Lainnya' }}</small>
                        </td>
                        <td>Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                        <td class="text-center">{{ $detail->jumlah }} pcs</td>
                        <td class="text-end pe-4 fw-semibold">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3" class="text-end py-3">Total Pembayaran (Termasuk Ongkir):</th>
                    <th class="text-end pe-4 py-3 text-primary fs-5">Rp {{ number_format($pesanan->total_pembayaran, 0, ',', '.') }}</th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<div class="d-flex flex-wrap gap-2 mt-4 mb-5">
    <a href="{{ route('user.pesanan.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Riwayat
    </a>
    <a href="{{ route('user.pesanan.invoice', $pesanan->id_transaksi) }}" target="_blank" class="btn btn-outline-primary">
        <i class="bi bi-printer me-1"></i> Cetak Invoice
    </a>
</div>
@endsection

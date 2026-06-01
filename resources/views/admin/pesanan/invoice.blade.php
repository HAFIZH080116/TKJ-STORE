<!DOCTYPE html>
<html lang="id" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $pesanan->id_transaksi }} — {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body { background: #f8fafc; font-family: system-ui, -apple-system, sans-serif; color: #1e293b; }
        .invoice-card { background: #fff; border-radius: .75rem; border: none; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.05); }
        @media print {
            body { background: #fff; color: #000; font-size: 12px; }
            .invoice-card { box-shadow: none; border: none; padding: 0 !important; margin: 0 !important; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body class="py-5">
    <div class="container" style="max-width: 800px;">
        <!-- Header Actions (Print button) -->
        <div class="d-flex justify-content-between align-items-center mb-4 no-print">
            <a href="javascript:window.history.back();" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
            <button onclick="window.print();" class="btn btn-sm btn-primary">
                <i class="bi bi-printer me-1"></i> Cetak / Simpan ke PDF
            </button>
        </div>

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
            $ongkir = 0;

            if ($isKompleks && $detailPengiriman) {
                if (preg_match('/^(.*?)\((.*)\)$/', $detailPengiriman, $matches)) {
                    $kurirLayanan = trim($matches[1]);
                    $inner = $matches[2];
                    
                    preg_match('/Penerima:\s*(.*?),/', $inner, $matchP);
                    preg_match('/Telp:\s*(.*?),/', $inner, $matchT);
                    
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
                } else {
                    $alamat = str_replace('Kirim ke ', '', $detailPengiriman);
                    $penerima = $pesanan->user?->name;
                    $telp = $pesanan->user?->no_hp;
                    $kurirLayanan = 'Standar';
                }
            } else {
                $detailPengiriman = $metode;
            }

            // Extract shipping fee
            if (isset($parts[2]) && str_contains($parts[2], 'Ongkir:')) {
                $ongkir = (float) filter_var($parts[2], FILTER_SANITIZE_NUMBER_INT);
            } else {
                $subtotalProducts = 0;
                foreach ($pesanan->detailTransaksi as $dt) {
                    $subtotalProducts += $dt->subtotal;
                }
                $ongkir = (float)($pesanan->total_pembayaran - $subtotalProducts);
            }
        @endphp

        <!-- Invoice Card -->
        <div class="card invoice-card p-5">
            <div class="row mb-5 align-items-center">
                <div class="col-sm-6">
                    <h3 class="fw-bold text-primary mb-1"><i class="bi bi-shop me-2"></i>{{ config('app.name') }}</h3>
                    <p class="text-muted small mb-0">Toko Komputer & Komponen TKJ Terpercaya</p>
                </div>
                <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
                    <h4 class="fw-bold text-uppercase text-secondary mb-1">INVOICE</h4>
                    <span class="badge text-bg-secondary px-3 py-2 font-monospace" style="font-size: 0.85rem;">
                        #{{ $pesanan->id_transaksi }}
                    </span>
                </div>
            </div>

            <hr class="text-muted mb-4">

            <!-- Billing & Shipping Information -->
            <div class="row mb-4">
                <div class="col-sm-6">
                    <h6 class="fw-bold text-muted small text-uppercase mb-3">Ditagih Kepada:</h6>
                    <div class="fw-bold fs-6 text-dark">{{ $pesanan->user?->name }}</div>
                    <div class="small text-secondary mt-1"><i class="bi bi-telephone me-2"></i>{{ $pesanan->user?->no_hp ?? '-' }}</div>
                    <div class="small text-secondary"><i class="bi bi-envelope me-2"></i>{{ $pesanan->user?->email }}</div>
                </div>
                <div class="col-sm-6 text-sm-end mt-4 mt-sm-0">
                    <h6 class="fw-bold text-muted small text-uppercase mb-3">Detail Transaksi:</h6>
                    <div class="small"><span class="text-muted">Tanggal:</span> <span class="fw-semibold">{{ $pesanan->tanggal_transaksi?->format('d M Y, H:i') }}</span></div>
                    <div class="small mt-1"><span class="text-muted">Metode Pembayaran:</span> <span class="fw-semibold text-capitalize text-dark">{{ $detailPembayaran }}</span></div>
                    <div class="small mt-1"><span class="text-muted">Status:</span> <span class="badge text-bg-{{ $pesanan->status === 'selesai' ? 'success' : ($pesanan->status === 'diproses' ? 'warning' : 'secondary') }} text-capitalize">{{ $pesanan->status }}</span></div>
                </div>
            </div>

            @if ($isKompleks && $penerima)
                <!-- Shipping Address Alert Card -->
                <div class="card bg-light border-0 mb-4 rounded-3">
                    <div class="card-body p-4">
                        <h6 class="fw-bold text-dark small text-uppercase mb-3"><i class="bi bi-truck me-2 text-info"></i>Detail Pengiriman Ekspedisi</h6>
                        <div class="row g-2 small">
                            <div class="col-sm-6">
                                <span class="text-muted">Penerima:</span> <strong class="text-dark">{{ $penerima }}</strong> ({{ $telp }})
                            </div>
                            <div class="col-sm-6 text-sm-end">
                                <span class="text-muted">Kurir/Ekspedisi:</span> <span class="badge text-bg-info text-dark fw-bold">{{ $kurirLayanan }}</span>
                            </div>
                            <div class="col-12 mt-2">
                                <span class="text-muted">Alamat Tujuan:</span> <span class="text-dark fw-medium">{{ $alamat }}</span>
                            </div>
                            @if ($catatan && $catatan !== 'Tidak ada')
                                <div class="col-12 mt-1">
                                    <span class="text-muted">Catatan Kurir:</span> <em class="text-danger">"{{ $catatan }}"</em>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @elseif ($detailPengiriman)
                <!-- Old Shipping Address Fallback Card -->
                <div class="card bg-light border-0 mb-4 rounded-3">
                    <div class="card-body p-4">
                        <h6 class="fw-bold text-dark small text-uppercase mb-2"><i class="bi bi-truck me-2 text-info"></i>Alamat Pengiriman</h6>
                        <p class="small text-secondary mb-0 fw-semibold">{{ str_replace('Kirim ke ', '', $detailPengiriman) }}</p>
                    </div>
                </div>
            @endif

            <!-- Products Table -->
            <div class="table-responsive mb-4">
                <table class="table align-middle">
                    <thead class="table-light text-muted small">
                        <tr>
                            <th class="ps-3">Nama Produk</th>
                            <th class="text-center" style="width: 100px;">Kuantitas</th>
                            <th class="text-end" style="width: 150px;">Harga Satuan</th>
                            <th class="text-end pe-3" style="width: 180px;">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $subtotalProduk = 0; @endphp
                        @foreach ($pesanan->detailTransaksi as $detail)
                            @php $subtotalProduk += (float) $detail->subtotal; @endphp
                            <tr>
                                <td class="ps-3 py-3">
                                    <div class="fw-semibold text-dark">{{ $detail->produk?->nama_produk ?? 'Produk Dihapus' }}</div>
                                </td>
                                <td class="text-center fw-semibold text-secondary">{{ $detail->jumlah }}</td>
                                <td class="text-end text-secondary">Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                                <td class="text-end fw-bold text-dark pe-3">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Total Calcs -->
            <div class="row justify-content-end">
                <div class="col-md-5">
                    <table class="table table-borderless align-middle mb-0">
                        <tbody>
                            <tr>
                                <td class="text-muted py-2">Total Produk:</td>
                                <td class="text-end fw-semibold py-2">Rp {{ number_format($subtotalProduk, 0, ',', '.') }}</td>
                            </tr>
                            @if ($ongkir > 0)
                                <tr>
                                    <td class="text-muted py-2">Ongkos Kirim:</td>
                                    <td class="text-end text-success fw-semibold py-2">Rp {{ number_format($ongkir, 0, ',', '.') }}</td>
                                </tr>
                            @endif
                            <tr class="border-top">
                                <td class="text-dark fw-bold py-3 fs-5">Total Bayar:</td>
                                <td class="text-end text-primary fw-extrabold py-3 fs-4">Rp {{ number_format($pesanan->total_pembayaran, 0, ',', '.') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <hr class="text-muted my-5">

            <!-- Footer terms -->
            <div class="text-center text-muted small">
                <p class="mb-1 fw-bold">Terima kasih atas kepercayaan Anda berbelanja di {{ config('app.name') }}!</p>
                <p class="mb-0 small">Jika Anda memerlukan bantuan pengiriman atau garansi komponen, silakan hubungi tim Customer Service kami.</p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        if (new URLSearchParams(window.location.search).get('print') === 'true') {
            window.addEventListener('DOMContentLoaded', () => {
                setTimeout(() => { window.print(); }, 500);
            });
        }
    </script>
</body>
</html>

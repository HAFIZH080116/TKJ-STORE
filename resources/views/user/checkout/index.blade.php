@extends('layouts.user')

@section('title', 'Checkout')

@push('styles')
<style>
    .payment-card {
        border: 2px solid var(--bs-border-color);
        border-radius: 0.75rem;
        cursor: pointer;
        transition: all 0.2s ease-in-out;
        background-color: var(--bs-body-bg);
    }
    .payment-card:hover {
        border-color: var(--bs-primary-border-subtle);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }
    .payment-card.active {
        border-color: var(--bs-primary);
        background-color: rgba(13, 110, 253, 0.06);
        box-shadow: 0 4px 12px rgba(13, 110, 253, 0.12);
    }
    .payment-icon {
        font-size: 1.8rem;
    }
    .courier-card {
        border: 1px solid var(--bs-border-color);
        border-radius: 0.5rem;
        cursor: pointer;
        transition: all 0.15s ease-in-out;
    }
    .courier-card:hover {
        border-color: var(--bs-primary);
        background-color: rgba(13, 110, 253, 0.02);
    }
    .courier-card.active {
        border-color: var(--bs-primary);
        background-color: rgba(13, 110, 253, 0.05);
        font-weight: 600;
    }
</style>
@endpush

@section('content')
<h4 class="fw-bold mb-4"><i class="bi bi-credit-card me-2"></i>Selesaikan Pesanan Anda</h4>

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

<div class="row g-4">
    <!-- Left Column: Order Summary & Advanced Shipping Form -->
    <div class="col-lg-7">
        <!-- Order Summary Card -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-body-tertiary fw-bold py-3"><i class="bi bi-cart3 me-2 text-primary"></i>Ringkasan Pembelian</div>
            <ul class="list-group list-group-flush">
                @foreach ($items as $item)
                    <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                        <div class="d-flex align-items-center gap-3">
                            @if ($item['gambar'])
                                <img src="{{ asset('storage/'.$item['gambar']) }}" width="48" height="48" class="rounded object-fit-cover" alt="{{ $item['nama_produk'] }}">
                            @else
                                <div class="bg-body-secondary rounded d-flex align-items-center justify-content-center" style="width:48px;height:48px">
                                    <i class="bi bi-image text-muted"></i>
                                </div>
                            @endif
                            <div>
                                <span class="fw-semibold text-body d-block">{{ $item['nama_produk'] }}</span>
                                <small class="text-muted">{{ $item['jumlah'] }} unit x Rp {{ number_format($item['harga_satuan'], 0, ',', '.') }}</small>
                            </div>
                        </div>
                        <span class="fw-semibold">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</span>
                    </li>
                @endforeach
            </ul>
        </div>

        <!-- Advanced Shipping Details Form Card -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-body-tertiary fw-bold py-3"><i class="bi bi-truck me-2 text-info"></i>Detail Pengiriman Kompleks</div>
            <div class="card-body p-4">
                <form id="checkoutForm" method="POST" action="{{ route('user.checkout.proses') }}">
                    @csrf
                    
                    <!-- Destination & Receiver Info -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nama_penerima" class="form-label fw-semibold text-secondary small">Nama Penerima <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-body border-end-0"><i class="bi bi-person text-muted"></i></span>
                                <input type="text" name="nama_penerima" id="nama_penerima" class="form-control border-start-0 @error('nama_penerima') is-invalid @enderror" 
                                       placeholder="Nama Lengkap Penerima" value="{{ old('nama_penerima', auth()->user()->name) }}" required>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="telepon_penerima" class="form-label fw-semibold text-secondary small">No. HP Penerima <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-body border-end-0"><i class="bi bi-telephone text-muted"></i></span>
                                <input type="text" name="telepon_penerima" id="telepon_penerima" class="form-control border-start-0 @error('telepon_penerima') is-invalid @enderror" 
                                       placeholder="Contoh: 08123456789" value="{{ old('telepon_penerima', auth()->user()->no_hp) }}" required>
                            </div>
                        </div>
                    </div>

                    <!-- Courier & Service Selector -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="kurir" class="form-label fw-semibold text-secondary small">Kurir Pengiriman <span class="text-danger">*</span></label>
                            <select name="kurir" id="kurir" class="form-select @error('kurir') is-invalid @enderror" required>
                                <option value="">-- Pilih Kurir --</option>
                                <option value="JNE Express" @selected(old('kurir') === 'JNE Express')>JNE Express (Domestik)</option>
                                <option value="SiCepat Express" @selected(old('kurir') === 'SiCepat Express')>SiCepat Express (Cepat/Halu)</option>
                                <option value="Pos Indonesia" @selected(old('kurir') === 'Pos Indonesia')>Pos Indonesia (Pos Kilat)</option>
                                <option value="GoSend Instant" @selected(old('kurir') === 'GoSend Instant')>GoSend Instant (Area Lokal)</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="layanan" class="form-label fw-semibold text-secondary small">Layanan & Estimasi <span class="text-danger">*</span></label>
                            <select name="layanan" id="layanan" class="form-select @error('layanan') is-invalid @enderror" required disabled>
                                <option value="">-- Pilih Layanan Kurir Dahulu --</option>
                            </select>
                        </div>
                    </div>

                    <!-- Full Shipping Address TextArea -->
                    <div class="mb-3">
                        <label for="alamat_lengkap" class="form-label fw-semibold text-secondary small">Alamat Lengkap Pengiriman <span class="text-danger">*</span></label>
                        <textarea name="alamat_lengkap" id="alamat_lengkap" rows="3" 
                                  class="form-control @error('alamat_lengkap') is-invalid @enderror" 
                                  placeholder="Tuliskan nama jalan, nomor rumah, RT/RW, kecamatan, kota, provinsi, dan kode pos..." required>{{ old('alamat_lengkap') }}</textarea>
                    </div>

                    <!-- Shipping Notes -->
                    <div class="mb-3">
                        <label for="catatan" class="form-label fw-semibold text-secondary small">Catatan Khusus Pengiriman (Opsional)</label>
                        <input type="text" name="catatan" id="catatan" class="form-control" placeholder="Contoh: Taruh di pos satpam depan rumah / hubungi sebelum jalan" value="{{ old('catatan') }}">
                    </div>

                    <!-- Hidden Inputs for Form Submission -->
                    <input type="hidden" name="ongkir" id="ongkir_input" value="0">
                    <input type="hidden" name="metode_pembayaran" id="metode_pembayaran_input" value="{{ old('metode_pembayaran') }}">
                </form>
            </div>
        </div>
    </div>

    <!-- Right Column: Interactive Payment Selector & Recalculation -->
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm sticky-top" style="top: 90px; z-index: 10;">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-4 text-body"><i class="bi bi-wallet2 me-2 text-primary"></i>Metode & Total Pembayaran</h5>
                
                <!-- Interactive Payment Grid -->
                <div class="mb-4">
                    <label class="form-label fw-semibold text-secondary small mb-3">Pilih Metode Pembayaran <span class="text-danger">*</span></label>
                    
                    <!-- Tabs for Bank Transfer and E-Wallet -->
                    <div class="row g-2 mb-3">
                        <!-- Virtual Account BCA -->
                        <div class="col-6">
                            <div class="payment-card p-3 d-flex flex-column align-items-center text-center h-100" data-value="BCA Virtual Account">
                                <i class="bi bi-bank text-primary payment-icon mb-2"></i>
                                <span class="fw-bold small d-block">BCA VA</span>
                                <small class="text-muted" style="font-size:0.7rem">Virtual Account</small>
                            </div>
                        </div>
                        <!-- Virtual Account Mandiri -->
                        <div class="col-6">
                            <div class="payment-card p-3 d-flex flex-column align-items-center text-center h-100" data-value="Mandiri Virtual Account">
                                <i class="bi bi-bank text-warning payment-icon mb-2"></i>
                                <span class="fw-bold small d-block">Mandiri VA</span>
                                <small class="text-muted" style="font-size:0.7rem">Virtual Account</small>
                            </div>
                        </div>
                        <!-- Virtual Account BNI -->
                        <div class="col-6">
                            <div class="payment-card p-3 d-flex flex-column align-items-center text-center h-100" data-value="BNI Virtual Account">
                                <i class="bi bi-bank text-info payment-icon mb-2"></i>
                                <span class="fw-bold small d-block">BNI VA</span>
                                <small class="text-muted" style="font-size:0.7rem">Virtual Account</small>
                            </div>
                        </div>
                        <!-- QRIS Gopay -->
                        <div class="col-6">
                            <div class="payment-card p-3 d-flex flex-column align-items-center text-center h-100" data-value="QRIS Gopay">
                                <i class="bi bi-qr-code text-success payment-icon mb-2"></i>
                                <span class="fw-bold small d-block">QRIS GoPay</span>
                                <small class="text-muted" style="font-size:0.7rem">Scan E-Wallet</small>
                            </div>
                        </div>
                        <!-- QRIS OVO -->
                        <div class="col-6">
                            <div class="payment-card p-3 d-flex flex-column align-items-center text-center h-100" data-value="QRIS OVO">
                                <i class="bi bi-qr-code text-purple payment-icon mb-2" style="color: #6c5ce7"></i>
                                <span class="fw-bold small d-block">QRIS OVO</span>
                                <small class="text-muted" style="font-size:0.7rem">Scan E-Wallet</small>
                            </div>
                        </div>
                        <!-- QRIS Dana -->
                        <div class="col-6">
                            <div class="payment-card p-3 d-flex flex-column align-items-center text-center h-100" data-value="QRIS Dana">
                                <i class="bi bi-qr-code text-info payment-icon mb-2"></i>
                                <span class="fw-bold small d-block">QRIS DANA</span>
                                <small class="text-muted" style="font-size:0.7rem">Scan E-Wallet</small>
                            </div>
                        </div>
                        <!-- Credit Card -->
                        <div class="col-6">
                            <div class="payment-card p-3 d-flex flex-column align-items-center text-center h-100" data-value="Credit Card">
                                <i class="bi bi-credit-card-2-front text-danger payment-icon mb-2"></i>
                                <span class="fw-bold small d-block">Kartu Kredit</span>
                                <small class="text-muted" style="font-size:0.7rem">Visa / Mastercard</small>
                            </div>
                        </div>
                        <!-- COD -->
                        <div class="col-6">
                            <div class="payment-card p-3 d-flex flex-column align-items-center text-center h-100" data-value="COD">
                                <i class="bi bi-cash-stack text-success payment-icon mb-2"></i>
                                <span class="fw-bold small d-block">COD (Bayar Saja)</span>
                                <small class="text-muted" style="font-size:0.7rem">Cash On Delivery</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Price recapitulation -->
                <div class="bg-body-tertiary rounded p-3 mb-4">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small">Total Belanja Barang</span>
                        <span class="fw-semibold small">Rp {{ number_format($totalHarga, 0, ',', '.') }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small">Ongkos Kirim Kurir</span>
                        <span class="fw-semibold small text-success" id="ongkir_display">Rp 0</span>
                    </div>
                    <hr class="my-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-body fw-bold">Grand Total Bayar</span>
                        <span class="fs-4 fw-extrabold text-primary" id="total_display" data-subtotal="{{ $totalHarga }}">
                            Rp {{ number_format($totalHarga, 0, ',', '.') }}
                        </span>
                    </div>
                </div>

                <button type="button" id="btnSubmitCheckout" class="btn btn-primary w-100 py-2.5 mb-2 fw-semibold">
                    <i class="bi bi-check-circle me-1"></i> Buat Pesanan Sekarang
                </button>
                <a href="{{ route('user.keranjang.index') }}" class="btn btn-outline-secondary w-100 py-2">Kembali</a>
            </div>
        </div>
    </div>
</div>

<!-- QRIS Modal Generator (Cool visual popup!) -->
<div class="modal fade" id="qrisModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 380px;">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-dark text-white border-0 py-3 d-flex justify-content-center">
                <h5 class="modal-title fw-bold text-success text-center mb-0"><i class="bi bi-qr-code-scan me-2 text-success"></i>QRIS TOKO TKJ-STORE</h5>
            </div>
            <div class="modal-body text-center p-4">
                <p class="text-muted small">Scan kode QRIS di bawah ini dengan aplikasi e-wallet Anda (GoPay, OVO, Dana, LinkAja) untuk menyelesaikan pembayaran.</p>
                <div class="d-inline-block border rounded p-3 bg-white mb-3 shadow-sm">
                    <!-- Elegant SVG QR Code simulation -->
                    <svg width="220" height="220" viewBox="0 0 100 100" style="display: block;">
                        <rect width="100" height="100" fill="white" />
                        <!-- QR code grids simulation -->
                        <path d="M5 5h30v30H5zm0 60h30v30H5zm60 0h30v30H65zm0-60h30v30H65z" fill="black" />
                        <path d="M10 10h20v20H10zm0 60h20v20H10zm60 0h20v20H70zm0-60h20v20H70z" fill="white" />
                        <path d="M15 15h10v10H15zm0 60h10v10H15zm60 0h10v10H75zm0-60h10v10H75z" fill="black" />
                        <path d="M42 10h5v15h-5zm10 5h8v5h-8zm-5 15h15v5H47zm18 10h10v5H65zm-15 5h10v5H50zm10 5h5v10h-5zm12 5h12v5H72zm-25 15h15v5H47zm18-5h10v10H65z" fill="black" />
                        <path d="M5 45h10v5H5zm15 5h20v5H20zm10-15h5v10h-5zm20 5h10v5H50zm-15 25h5v15h-5z" fill="black" />
                    </svg>
                </div>
                <h5 class="fw-bold text-primary mb-1">TOTAL TAGIHAN</h5>
                <h4 class="fw-extrabold text-danger mb-3" id="qris_total_display">Rp 0</h4>
                <div class="alert alert-secondary border-0 p-2 mb-0 small text-start">
                    <i class="bi bi-info-circle me-1 text-primary"></i> <strong>Instruksi:</strong> Setelah menekan tombol "Saya Sudah Membayar", pesanan Anda akan segera masuk ke database dengan status "pending" menunggu persetujuan admin.
                </div>
            </div>
            <div class="modal-footer border-0 p-3 bg-body-secondary d-flex gap-2">
                <button type="button" class="btn btn-secondary btn-sm flex-grow-1" id="btnCancelQris">Batal</button>
                <button type="button" class="btn btn-success btn-sm flex-grow-1 text-white fw-bold" id="btnConfirmQris">Saya Sudah Membayar</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // --- 1. Dynamic Shipping & Courier Costs ---
        const courierSelect = document.getElementById('kurir');
        const serviceSelect = document.getElementById('layanan');
        const ongkirInput = document.getElementById('ongkir_input');
        const ongkirDisplay = document.getElementById('ongkir_display');
        const totalDisplay = document.getElementById('total_display');
        
        const subtotal = parseFloat(totalDisplay.getAttribute('data-subtotal'));

        // Courier rates mapping
        const shippingRates = {
            'JNE Express': {
                'Ekonomis': { tarif: 10000, desc: 'JNE OKE (5-7 Hari)' },
                'Reguler': { tarif: 15000, desc: 'JNE REG (2-3 Hari)' },
                'Express': { tarif: 28000, desc: 'JNE YES (1 Hari)' }
            },
            'SiCepat Express': {
                'Ekonomis': { tarif: 9000, desc: 'SiCepat HALU (5-7 Hari)' },
                'Reguler': { tarif: 14000, desc: 'SiCepat REG (2-3 Hari)' },
                'Express': { tarif: 26000, desc: 'SiCepat BEST (1 Hari)' }
            },
            'Pos Indonesia': {
                'Ekonomis': { tarif: 8000, desc: 'Pos Kilat Khusus (5-7 Hari)' },
                'Reguler': { tarif: 13000, desc: 'Pos Express (2-3 Hari)' }
            },
            'GoSend Instant': {
                'Instant': { tarif: 45000, desc: 'GoSend Kilat (3 Jam)' }
            }
        };

        courierSelect.addEventListener('change', function () {
            const courier = this.value;
            serviceSelect.innerHTML = '';
            
            if (!courier) {
                serviceSelect.disabled = true;
                serviceSelect.innerHTML = '<option value="">-- Pilih Layanan Kurir Dahulu --</option>';
                updateOngkir(0);
                return;
            }

            serviceSelect.disabled = false;
            serviceSelect.innerHTML = '<option value="">-- Pilih Layanan & Estimasi --</option>';
            
            const services = shippingRates[courier];
            for (const service in services) {
                const option = document.createElement('option');
                option.value = service;
                option.setAttribute('data-tarif', services[service].tarif);
                option.textContent = `${service} - ${services[service].desc} (Rp ${services[service].tarif.toLocaleString('id-ID')})`;
                serviceSelect.appendChild(option);
            }
            updateOngkir(0);
        });

        serviceSelect.addEventListener('change', function () {
            const selectedOption = this.options[this.selectedIndex];
            const tarif = parseFloat(selectedOption.getAttribute('data-tarif')) || 0;
            updateOngkir(tarif);
        });

        function updateOngkir(ongkir) {
            ongkirInput.value = ongkir;
            ongkirDisplay.textContent = 'Rp ' + ongkir.toLocaleString('id-ID');
            
            const grandTotal = subtotal + ongkir;
            totalDisplay.textContent = 'Rp ' + grandTotal.toLocaleString('id-ID');
        }

        // --- 2. Interactive Payment Selector ---
        const paymentCards = document.querySelectorAll('.payment-card');
        const paymentInput = document.getElementById('metode_pembayaran_input');

        paymentCards.forEach(card => {
            card.addEventListener('click', function () {
                paymentCards.forEach(c => c.classList.remove('active'));
                this.classList.add('active');
                
                const val = this.getAttribute('data-value');
                paymentInput.value = val;
            });
        });

        // Initialize active payment card if old input exists
        if (paymentInput.value) {
            const activeCard = document.querySelector(`.payment-card[data-value="${paymentInput.value}"]`);
            if (activeCard) activeCard.classList.add('active');
        }

        // --- 3. Interactive Form Submission & QRIS Popup ---
        const checkoutForm = document.getElementById('checkoutForm');
        const btnSubmit = document.getElementById('btnSubmitCheckout');
        const qrisModalEl = document.getElementById('qrisModal');
        const qrisModal = new bootstrap.Modal(qrisModalEl);
        const qrisTotal = document.getElementById('qris_total_display');

        btnSubmit.addEventListener('click', function () {
            // Check form validation first
            if (!checkoutForm.checkValidity()) {
                checkoutForm.reportValidity();
                return;
            }

            // Check if payment method selected
            if (!paymentInput.value) {
                alert('Silakan pilih salah satu Metode Pembayaran terlebih dahulu!');
                return;
            }

            // Check if QRIS selected
            if (paymentInput.value.startsWith('QRIS')) {
                const currentTotal = subtotal + parseFloat(ongkirInput.value);
                qrisTotal.textContent = 'Rp ' + currentTotal.toLocaleString('id-ID');
                qrisModal.show();
            } else {
                checkoutForm.submit();
            }
        });

        document.getElementById('btnConfirmQris').addEventListener('click', function () {
            qrisModal.hide();
            checkoutForm.submit();
        });

        document.getElementById('btnCancelQris').addEventListener('click', function () {
            qrisModal.hide();
        });
    });
</script>
@endpush
@endsection

<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\CartService;
use App\Services\CheckoutService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function __construct(
        private CartService $cart,
        private CheckoutService $checkout
    ) {
    }

    public function index(): View|RedirectResponse
    {
        if ($this->cart->isEmpty()) {
            return redirect()
                ->route('user.keranjang.index')
                ->with('error', 'Keranjang masih kosong.');
        }

        return view('user.checkout.index', [
            'items' => $this->cart->items(),
            'totalHarga' => $this->cart->totalHarga(),
        ]);
    }

    public function proses(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'metode_pembayaran' => ['required', 'string', 'max:100', 'in:BCA Virtual Account,Mandiri Virtual Account,BNI Virtual Account,QRIS Gopay,QRIS OVO,QRIS Dana,Credit Card,COD'],
            'nama_penerima' => ['required', 'string', 'max:100'],
            'telepon_penerima' => ['required', 'string', 'max:30'],
            'kurir' => ['required', 'string', 'in:JNE Express,SiCepat Express,Pos Indonesia,GoSend Instant'],
            'layanan' => ['required', 'string', 'in:Ekonomis,Reguler,Express,Instant'],
            'alamat_lengkap' => ['required', 'string', 'max:500'],
            'catatan' => ['nullable', 'string', 'max:200'],
            'ongkir' => ['required', 'numeric', 'min:0'],
        ], [
            'metode_pembayaran.required' => 'Metode pembayaran wajib dipilih.',
            'metode_pembayaran.in' => 'Metode pembayaran tidak valid.',
            'nama_penerima.required' => 'Nama penerima wajib diisi.',
            'telepon_penerima.required' => 'Nomor telepon penerima wajib diisi.',
            'kurir.required' => 'Kurir pengiriman wajib dipilih.',
            'kurir.in' => 'Kurir pengiriman tidak valid.',
            'layanan.required' => 'Layanan pengiriman wajib dipilih.',
            'layanan.in' => 'Layanan pengiriman tidak valid.',
            'alamat_lengkap.required' => 'Alamat lengkap pengiriman wajib diisi.',
        ]);

        // Formatted String: "[METODE] | [KURIR] - [LAYANAN] (Penerima: [NAMA], Telp: [TELP], Alamat: [ALAMAT], Catatan: [CATATAN])"
        $formattedMetode = $validated['metode_pembayaran'] . ' | ' . $validated['kurir'] . ' - ' . $validated['layanan'] . ' (Penerima: ' . $validated['nama_penerima'] . ', Telp: ' . $validated['telepon_penerima'] . ', Alamat: ' . $validated['alamat_lengkap'] . ', Catatan: ' . ($validated['catatan'] ?: 'Tidak ada') . ')';

        $result = $this->checkout->process($formattedMetode, (float) $validated['ongkir']);

        if (isset($result['error'])) {
            return redirect()
                ->route('user.keranjang.index')
                ->with('error', $result['error']);
        }

        return redirect()
            ->route('user.pesanan.show', $result['transaksi'])
            ->with('success', 'Pesanan berhasil dibuat. Status: pending.');
    }
}

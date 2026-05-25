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
            'metode_pembayaran' => ['required', 'string', 'max:50', 'in:Transfer Bank,COD,E-Wallet,Tunai'],
        ], [
            'metode_pembayaran.required' => 'Metode pembayaran wajib dipilih.',
            'metode_pembayaran.in' => 'Metode pembayaran tidak valid.',
        ]);

        $result = $this->checkout->process($validated['metode_pembayaran']);

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

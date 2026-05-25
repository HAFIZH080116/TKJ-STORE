<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use App\Services\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KeranjangController extends Controller
{
    public function __construct(private CartService $cart)
    {
    }

    public function index(): View
    {
        return view('user.keranjang.index', [
            'items' => $this->cart->items(),
            'totalHarga' => $this->cart->totalHarga(),
        ]);
    }

    public function tambah(Request $request, Produk $produk): RedirectResponse
    {
        $validated = $request->validate([
            'jumlah' => ['required', 'integer', 'min:1'],
        ]);

        $error = $this->cart->add($produk, (int) $validated['jumlah']);

        if ($error) {
            return back()->with('error', $error);
        }

        return back()->with('success', 'Produk ditambahkan ke keranjang.');
    }

    public function update(Request $request, int $id_produk): RedirectResponse
    {
        $validated = $request->validate([
            'jumlah' => ['required', 'integer', 'min:1'],
        ]);

        $error = $this->cart->update($id_produk, (int) $validated['jumlah']);

        if ($error) {
            return back()->with('error', $error);
        }

        return redirect()
            ->route('user.keranjang.index')
            ->with('success', 'Keranjang berhasil diperbarui.');
    }

    public function hapus(int $id_produk): RedirectResponse
    {
        $this->cart->remove($id_produk);

        return redirect()
            ->route('user.keranjang.index')
            ->with('success', 'Produk dihapus dari keranjang.');
    }
}

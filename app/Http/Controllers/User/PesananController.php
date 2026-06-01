<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PesananController extends Controller
{
    public function index(): View
    {
        $pesanan = Transaksi::where('id_user', Auth::id())
            ->orderByDesc('tanggal_transaksi')
            ->paginate(10);

        return view('user.pesanan.index', compact('pesanan'));
    }

    public function show(Transaksi $pesanan): View
    {
        abort_unless($pesanan->id_user === Auth::id(), 403);

        $pesanan->load(['detailTransaksi.produk']);

        return view('user.pesanan.show', compact('pesanan'));
    }

    public function invoice(Transaksi $pesanan): View
    {
        abort_unless($pesanan->id_user === Auth::id(), 403);
        $pesanan->load(['detailTransaksi.produk']);
        return view('admin.pesanan.invoice', compact('pesanan'));
    }
}

<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use App\Models\Transaksi;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $userId = Auth::id();

        return view('user.dashboard', [
            'produkTerbaru' => Produk::orderByDesc('id_produk')->limit(6)->get(),
            'riwayatPesanan' => Transaksi::where('id_user', $userId)
                ->orderByDesc('tanggal_transaksi')
                ->limit(5)
                ->get(),
            'pesananTerakhir' => Transaksi::where('id_user', $userId)
                ->orderByDesc('tanggal_transaksi')
                ->first(),
        ]);
    }
}

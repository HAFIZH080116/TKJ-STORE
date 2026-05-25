<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('admin.dashboard', [
            'totalProduk' => Produk::count(),
            'totalUser' => User::count(),
            'totalPesanan' => Transaksi::count(),
            'totalPendapatan' => Transaksi::where('status', 'selesai')->sum('total_pembayaran'),
            'pesananTerbaru' => Transaksi::with('user')
                ->orderByDesc('tanggal_transaksi')
                ->limit(5)
                ->get(),
        ]);
    }
}

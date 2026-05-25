<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DetailTransaksi;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class LaporanController extends Controller
{
    public function index(Request $request): View
    {
        $dari = $request->input('dari');
        $sampai = $request->input('sampai');

        $query = Transaksi::query();

        if ($dari) {
            $query->whereDate('tanggal_transaksi', '>=', $dari);
        }

        if ($sampai) {
            $query->whereDate('tanggal_transaksi', '<=', $sampai);
        }

        $totalPesanan = (clone $query)->count();
        $pesananSelesai = (clone $query)->where('status', 'selesai')->count();
        $pesananPending = (clone $query)->where('status', 'pending')->count();
        $pesananDiproses = (clone $query)->where('status', 'diproses')->count();
        $totalPendapatan = (clone $query)->where('status', 'selesai')->sum('total_pembayaran');
        $rataRataTransaksi = $pesananSelesai > 0
            ? $totalPendapatan / $pesananSelesai
            : 0;

        $rekapTransaksi = (clone $query)
            ->with('user')
            ->orderByDesc('tanggal_transaksi')
            ->get();

        $produkTerlaris = DetailTransaksi::query()
            ->select(
                'id_produk',
                DB::raw('SUM(jumlah) as total_terjual'),
                DB::raw('SUM(subtotal) as total_pendapatan')
            )
            ->whereHas('transaksi', function ($q) use ($dari, $sampai) {
                $q->where('status', 'selesai');
                if ($dari) {
                    $q->whereDate('tanggal_transaksi', '>=', $dari);
                }
                if ($sampai) {
                    $q->whereDate('tanggal_transaksi', '<=', $sampai);
                }
            })
            ->groupBy('id_produk')
            ->orderByDesc('total_terjual')
            ->with('produk')
            ->limit(10)
            ->get();

        $rekapPerStatus = (clone $query)
            ->select('status', DB::raw('COUNT(*) as jumlah'), DB::raw('SUM(total_pembayaran) as nominal'))
            ->groupBy('status')
            ->get();

        return view('admin.laporan.index', compact(
            'dari',
            'sampai',
            'totalPesanan',
            'pesananSelesai',
            'pesananPending',
            'pesananDiproses',
            'totalPendapatan',
            'rataRataTransaksi',
            'rekapTransaksi',
            'produkTerlaris',
            'rekapPerStatus'
        ));
    }
}

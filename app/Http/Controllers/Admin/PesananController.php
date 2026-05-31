<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PesananController extends Controller
{
    public function index(Request $request): View
    {
        $query = Transaksi::with('user')->orderByDesc('tanggal_transaksi');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('q')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->q.'%')
                    ->orWhere('email', 'like', '%'.$request->q.'%');
            });
        }

        $pesanan = $query->paginate(10)->withQueryString();

        return view('admin.pesanan.index', compact('pesanan'));
    }

    public function show(Transaksi $pesanan): View
    {
        $pesanan->load(['user', 'detailTransaksi.produk']);

        return view('admin.pesanan.show', compact('pesanan'));
    }

    public function updateStatus(Request $request, Transaksi $pesanan): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending,diproses,selesai'],
        ]);

        $pesanan->update(['status' => $validated['status']]);

        \App\Models\ActivityLog::record("Mengubah status pesanan #{$pesanan->id_transaksi} menjadi: {$validated['status']}");

        return redirect()
            ->route('admin.pesanan.show', $pesanan)
            ->with('success', 'Status pesanan berhasil diperbarui.');
    }
}

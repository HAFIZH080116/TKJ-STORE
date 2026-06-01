<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use App\Models\Transaksi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $userId = Auth::id();

        // 1. Fetch active unread status notifications
        $notifications = [];
        $dismissed = Cache::get('user_' . $userId . '_dismissed_notifications', []);

        $pesananStatusList = Transaksi::where('id_user', $userId)
            ->whereIn('status', ['diproses', 'selesai'])
            ->get();

        foreach ($pesananStatusList as $pesanan) {
            $key = $pesanan->id_transaksi . '_' . $pesanan->status;
            if (!in_array($key, $dismissed)) {
                $message = match ($pesanan->status) {
                    'diproses' => "Pesanan Anda #{$pesanan->id_transaksi} sedang diproses kurir ekspedisi!",
                    'selesai' => "Pesanan Anda #{$pesanan->id_transaksi} telah selesai. Terima kasih!",
                    default => "",
                };
                $notifications[] = [
                    'id_transaksi' => $pesanan->id_transaksi,
                    'status' => $pesanan->status,
                    'key' => $key,
                    'message' => $message,
                ];
            }
        }

        return view('user.dashboard', [
            'produkTerbaru' => Produk::orderByDesc('id_produk')->limit(6)->get(),
            'riwayatPesanan' => Transaksi::where('id_user', $userId)
                ->orderByDesc('tanggal_transaksi')
                ->limit(5)
                ->get(),
            'pesananTerakhir' => Transaksi::where('id_user', $userId)
                ->orderByDesc('tanggal_transaksi')
                ->first(),
            'notifications' => $notifications,
        ]);
    }

    public function bacaNotifikasi(string $key): RedirectResponse
    {
        $userId = Auth::id();
        $dismissed = Cache::get('user_' . $userId . '_dismissed_notifications', []);

        if (!in_array($key, $dismissed)) {
            $dismissed[] = $key;
            // Store read status for 30 days
            Cache::put('user_' . $userId . '_dismissed_notifications', $dismissed, now()->addDays(30));
        }

        return redirect()->back();
    }
}

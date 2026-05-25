<?php

namespace App\Services;

use App\Models\DetailTransaksi;
use App\Models\Produk;
use App\Models\Transaksi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutService
{
    public function __construct(private CartService $cart)
    {
    }

    public function process(string $metodePembayaran): array
    {
        if ($this->cart->isEmpty()) {
            return ['error' => 'Keranjang masih kosong.'];
        }

        $items = $this->cart->items();

        try {
            $transaksi = DB::transaction(function () use ($items, $metodePembayaran) {
                $total = 0;

                foreach ($items as $item) {
                    $produk = Produk::lockForUpdate()->find($item['id_produk']);

                    if (! $produk) {
                        throw new \RuntimeException('Produk tidak ditemukan.');
                    }

                    if ($produk->stok < $item['jumlah']) {
                        throw new \RuntimeException(
                            "Stok {$produk->nama_produk} tidak mencukupi. Tersedia: {$produk->stok}"
                        );
                    }
                }

                $transaksi = Transaksi::create([
                    'id_user' => Auth::id(),
                    'tanggal_transaksi' => now(),
                    'total_pembayaran' => 0,
                    'metode_pembayaran' => $metodePembayaran,
                    'status' => 'pending',
                ]);

                foreach ($items as $item) {
                    $produk = Produk::lockForUpdate()->find($item['id_produk']);
                    $harga = (float) $produk->harga_satuan;
                    $subtotal = $harga * $item['jumlah'];

                    DetailTransaksi::create([
                        'id_transaksi' => $transaksi->id_transaksi,
                        'id_produk' => $produk->id_produk,
                        'jumlah' => $item['jumlah'],
                        'harga_satuan' => $harga,
                        'subtotal' => $subtotal,
                    ]);

                    $produk->decrement('stok', $item['jumlah']);
                    $total += $subtotal;
                }

                $transaksi->update(['total_pembayaran' => $total]);

                return $transaksi->fresh(['detailTransaksi.produk']);
            });

            $this->cart->clear();

            return ['transaksi' => $transaksi];
        } catch (\RuntimeException $e) {
            return ['error' => $e->getMessage()];
        } catch (\Throwable $e) {
            return ['error' => 'Checkout gagal. Silakan coba lagi.'];
        }
    }
}

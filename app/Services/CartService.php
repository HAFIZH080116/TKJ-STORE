<?php

namespace App\Services;

use App\Models\Produk;

class CartService
{
    private const SESSION_KEY = 'cart';

    public function items(): array
    {
        return session(self::SESSION_KEY, []) ?? [];
    }

    public function totalQuantity(): int
    {
        return array_sum(array_column($this->items(), 'jumlah'));
    }

    public function totalHarga(): float
    {
        return array_sum(array_column($this->items(), 'subtotal'));
    }

    public function isEmpty(): bool
    {
        return empty($this->items());
    }

    public function add(Produk $produk, int $jumlah): ?string
    {
        if ($produk->stok < 1) {
            return 'Produk sedang habis.';
        }

        $cart = $this->items();
        $id = (string) $produk->id_produk;
        $current = $cart[$id]['jumlah'] ?? 0;
        $newQty = $current + $jumlah;

        if ($newQty > $produk->stok) {
            return 'Stok tidak mencukupi. Stok tersedia: '.$produk->stok;
        }

        $cart[$id] = $this->buildItem($produk, $newQty);
        session([self::SESSION_KEY => $cart]);

        return null;
    }

    public function update(int $idProduk, int $jumlah): ?string
    {
        $produk = Produk::find($idProduk);

        if (! $produk) {
            return 'Produk tidak ditemukan.';
        }

        if ($jumlah < 1) {
            return 'Jumlah minimal 1.';
        }

        if ($jumlah > $produk->stok) {
            return 'Stok tidak mencukupi. Stok tersedia: '.$produk->stok;
        }

        $cart = $this->items();
        $id = (string) $idProduk;

        if (! isset($cart[$id])) {
            return 'Produk tidak ada di keranjang.';
        }

        $cart[$id] = $this->buildItem($produk, $jumlah);
        session([self::SESSION_KEY => $cart]);

        return null;
    }

    public function remove(int $idProduk): void
    {
        $cart = $this->items();
        unset($cart[(string) $idProduk]);
        session([self::SESSION_KEY => $cart]);
    }

    public function clear(): void
    {
        session()->forget(self::SESSION_KEY);
    }

    private function buildItem(Produk $produk, int $jumlah): array
    {
        $harga = (float) $produk->harga_satuan;

        return [
            'id_produk' => $produk->id_produk,
            'nama_produk' => $produk->nama_produk,
            'harga_satuan' => $harga,
            'gambar' => $produk->gambar,
            'stok' => $produk->stok,
            'jumlah' => $jumlah,
            'subtotal' => $harga * $jumlah,
        ];
    }
}

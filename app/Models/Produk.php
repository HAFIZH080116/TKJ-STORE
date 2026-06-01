<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['nama_produk', 'harga_satuan', 'stok', 'deskripsi', 'gambar'])]
class Produk extends Model
{
    protected $table = 'produk';

    protected $primaryKey = 'id_produk';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'harga_satuan' => 'decimal:2',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'id_produk';
    }

    public function detailTransaksi(): HasMany
    {
        return $this->hasMany(DetailTransaksi::class, 'id_produk', 'id_produk');
    }

    public function getKategoriAttribute(): string
    {
        $name = strtolower($this->nama_produk);
        if (str_contains($name, 'laptop')) return 'Laptop';
        if (str_contains($name, 'keyboard') || str_contains($name, 'mouse')) return 'Periferal';
        if (str_contains($name, 'ram') || str_contains($name, 'ssd')) return 'Komponen';
        if (str_contains($name, 'monitor')) return 'Monitor';
        return 'Lainnya';
    }
}

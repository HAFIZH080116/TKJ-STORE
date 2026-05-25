<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['id_transaksi', 'id_produk', 'jumlah', 'harga_satuan', 'subtotal'])]
class DetailTransaksi extends Model
{
    protected $table = 'detail_transaksi';

    public $incrementing = false;

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'harga_satuan' => 'decimal:2',
            'subtotal' => 'decimal:2',
        ];
    }

    protected function setKeysForSaveQuery($query): Builder
    {
        return $query
            ->where('id_transaksi', $this->getAttribute('id_transaksi'))
            ->where('id_produk', $this->getAttribute('id_produk'));
    }

    public function transaksi(): BelongsTo
    {
        return $this->belongsTo(Transaksi::class, 'id_transaksi', 'id_transaksi');
    }

    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class, 'id_produk', 'id_produk');
    }
}

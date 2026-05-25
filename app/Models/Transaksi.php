<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['id_user', 'tanggal_transaksi', 'total_pembayaran', 'metode_pembayaran', 'status'])]
class Transaksi extends Model
{
    protected $table = 'transaksi';

    protected $primaryKey = 'id_transaksi';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'tanggal_transaksi' => 'datetime',
            'total_pembayaran' => 'decimal:2',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'id_transaksi';
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function detailTransaksi(): HasMany
    {
        return $this->hasMany(DetailTransaksi::class, 'id_transaksi', 'id_transaksi');
    }
}

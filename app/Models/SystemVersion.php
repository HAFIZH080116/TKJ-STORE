<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['versi', 'deskripsi', 'tanggal_rilis'])]
class SystemVersion extends Model
{
    protected $table = 'system_versions';

    protected $primaryKey = 'id_version';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'tanggal_rilis' => 'date',
        ];
    }
}

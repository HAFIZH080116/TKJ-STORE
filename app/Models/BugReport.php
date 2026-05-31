<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['judul', 'deskripsi', 'status', 'tanggal_dilaporkan'])]
class BugReport extends Model
{
    protected $table = 'bug_reports';

    protected $primaryKey = 'id_bug';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'tanggal_dilaporkan' => 'datetime',
        ];
    }
}

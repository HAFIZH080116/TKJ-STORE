<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['id_user', 'aktivitas', 'tanggal_log'])]
class ActivityLog extends Model
{
    protected $table = 'activity_logs';

    protected $primaryKey = 'id_log';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'tanggal_log' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public static function record(string $aktivitas, ?int $userId = null): void
    {
        self::create([
            'id_user' => $userId ?? \Illuminate\Support\Facades\Auth::id() ?? 1, // Fallback to user with ID 1 if guest
            'aktivitas' => $aktivitas,
            'tanggal_log' => now(),
        ]);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GiftCodeRedemption extends Model
{
    use HasFactory;

    protected $fillable = [
        'gift_code_id',
        'user_id',
        'redeemed_at',
        'vip_starts_at',
        'vip_ends_at',
        'ip_address',
    ];

    protected $casts = [
        'redeemed_at' => 'datetime',
        'vip_starts_at' => 'datetime',
        'vip_ends_at' => 'datetime',
    ];

    // Relaciones
    public function giftCode()
    {
        return $this->belongsTo(GiftCode::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Métodos
    public function isVipActive(): bool
    {
        return $this->vip_ends_at->isFuture();
    }

    public function getDaysRemaining(): int
    {
        if (!$this->isVipActive()) {
            return 0;
        }

        return now()->diffInDays($this->vip_ends_at, false);
    }
}
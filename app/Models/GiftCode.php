<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class GiftCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'vip_days',
        'expires_at',
        'is_active',
        'max_uses',
        'used_count',
        'created_by',
        'description',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_active'  => 'boolean',
    ];

    // Relaciones
    public function creator()
    {
        return $this->belongsTo(\App\Models\HexaAdmin::class, 'created_by');
    }

    public function getCreatorNameAttribute()
    {
        if ($this->created_by) {
            $admin = DB::table('hexa_admins')->where('id', $this->created_by)->first();
            return $admin ? $admin->name : 'Admin eliminado';
        }
        return 'Sistema';
    }

    public function redemptions()
    {
        return $this->hasMany(GiftCodeRedemption::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeNotExpired($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('expires_at')
              ->orWhere('expires_at', '>', now());
        });
    }

    public function scopeAvailable($query)
    {
        return $query->active()
                     ->notExpired()
                     ->whereRaw('used_count < max_uses');
    }

    // Métodos de validación
    public function isValid(): bool
    {
        return $this->is_active &&
               ($this->expires_at === null || $this->expires_at->isFuture()) &&
               $this->used_count < $this->max_uses;
    }

    public function canBeUsedBy(User $user): bool
    {
        if (!$this->isValid()) {
            return false;
        }
        return !$this->redemptions()
                     ->where('user_id', $user->id)
                     ->exists();
    }

    // ✅ Canje atómico — resuelve la race condition
    public function redeem(User $user): GiftCodeRedemption
    {
        // lockForUpdate() bloquea la fila en BD.
        // Si dos usuarios intentan canjear al mismo tiempo,
        // el segundo espera y cuando obtiene el lock
        // ya no cumple used_count < max_uses → falla correctamente.
        $locked = static::where('id', $this->id)
            ->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('expires_at')
                  ->orWhere('expires_at', '>', now());
            })
            ->whereRaw('used_count < max_uses')
            ->lockForUpdate()
            ->first();

        if (!$locked) {
            throw new \Exception('Este código no está disponible o ya alcanzó su límite de usos.');
        }

        $alreadyRedeemed = $locked->redemptions()
            ->where('user_id', $user->id)
            ->lockForUpdate()
            ->exists();

        if ($alreadyRedeemed) {
            throw new \Exception('Ya has canjeado este código anteriormente.');
        }

        $vipStartsAt = now();
        $vipEndsAt   = $vipStartsAt->copy()->addDays($locked->vip_days);

        $redemption = $locked->redemptions()->create([
            'user_id'       => $user->id,
            'redeemed_at'   => now(),
            'vip_starts_at' => $vipStartsAt,
            'vip_ends_at'   => $vipEndsAt,
            'ip_address'    => request()->ip(),
        ]);

        $locked->increment('used_count');
        $user->activateVip($locked->vip_days);

        return $redemption;
    }

    // Utilidades
    public static function generateUniqueCode(): string
    {
        do {
            $code = strtoupper(Str::random(8));
        } while (self::where('code', $code)->exists());

        return $code;
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->created_by && auth('admin')->check()) {
                $model->created_by = auth('admin')->id();
            }
        });
    }
}
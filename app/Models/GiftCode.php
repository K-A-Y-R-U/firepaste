<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
        'is_active' => 'boolean',
    ];

    // Relaciones
    public function creator()
    {
        // Usar directamente la tabla hexa_admins
        return $this->belongsTo(\App\Models\HexaAdmin::class, 'created_by');
    }

    // Método para obtener el nombre del creador sin el modelo
    public function getCreatorNameAttribute()
    {
        if ($this->created_by) {
            $admin = \DB::table('hexa_admins')->where('id', $this->created_by)->first();
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

    // Métodos
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

        // Verificar si el usuario ya usó este código
        return !$this->redemptions()
                    ->where('user_id', $user->id)
                    ->exists();
    }

    public function redeem(User $user): GiftCodeRedemption
    {
        if (!$this->canBeUsedBy($user)) {
            throw new \Exception('Este código no puede ser usado por este usuario');
        }

        $vipStartsAt = now();
        $vipEndsAt = $vipStartsAt->copy()->addDays($this->vip_days);

        // Crear el canje
        $redemption = $this->redemptions()->create([
            'user_id' => $user->id,
            'redeemed_at' => now(),
            'vip_starts_at' => $vipStartsAt,
            'vip_ends_at' => $vipEndsAt,
            'ip_address' => request()->ip(),
        ]);

        // Actualizar contador de usos
        $this->increment('used_count');

        // Activar VIP al usuario
        $this->activateVipForUser($user, $vipEndsAt);

        return $redemption;
    }

    private function activateVipForUser(User $user, $vipEndsAt)
    {
        // Usar el método simplificado del usuario
        $user->activateVip($this->vip_days);
    }

    // Generar código único
    public static function generateUniqueCode(): string
    {
        do {
            $code = strtoupper(Str::random(8));
        } while (self::where('code', $code)->exists());

        return $code;
    }

    // Boot method para agregar created_by automáticamente
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // Si no se ha establecido created_by y hay un admin logueado
            if (!$model->created_by && auth('admin')->check()) {
                $model->created_by = auth('admin')->id();
            }
        });
    }
}
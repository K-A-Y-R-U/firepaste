<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'vip_expires_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
        'vip_expires_at'    => 'datetime',
    ];

    // Relaciones
    public function giftCodeRedemptions()
    {
        return $this->hasMany(GiftCodeRedemption::class);
    }

    // ✅ VIP — una sola fuente de verdad: vip_expires_at
    // Ya no existe is_vip_active que podía desincronizarse.

    public function isVip(): bool
    {
        return $this->vip_expires_at !== null
            && $this->vip_expires_at->isFuture();
    }

    /** Alias para compatibilidad con código existente */
    public function canAccessVip(): bool
    {
        return $this->isVip();
    }

    public function getVipStatus(): array
    {
        $isActive      = $this->isVip();
        $daysRemaining = 0;

        if ($isActive && $this->vip_expires_at) {
            $daysRemaining = max(0, (int) now()->diffInDays($this->vip_expires_at, false));
        }

        return [
            'is_active'      => $isActive,
            'expires_at'     => $this->vip_expires_at,
            'days_remaining' => $daysRemaining,
        ];
    }

    /**
     * Activa o extiende el VIP.
     * Si ya tiene VIP activo, suma días a la fecha existente.
     * Si no tiene o venció, arranca desde ahora.
     */
    public function activateVip(int $days): void
    {
        if ($days <= 0) {
            return;
        }

        $base = ($this->isVip() && $this->vip_expires_at)
            ? $this->vip_expires_at->copy()
            : now();

        $this->update([
            'vip_expires_at' => $base->addDays($days),
        ]);
    }

    // Manejo de roles
    public function hasRole(string $roleName): bool
    {
        return DB::table('user_roles')
            ->join('hexa_roles', 'user_roles.role_id', '=', 'hexa_roles.id')
            ->where('user_roles.user_id', $this->id)
            ->where('hexa_roles.name', $roleName)
            ->exists();
    }

    public function hasAnyRole(array $roles): bool
    {
        return DB::table('user_roles')
            ->join('hexa_roles', 'user_roles.role_id', '=', 'hexa_roles.id')
            ->where('user_roles.user_id', $this->id)
            ->whereIn('hexa_roles.name', $roles)
            ->exists();
    }

    public function assignRole(string $roleName): void
    {
        $role = DB::table('hexa_roles')->where('name', $roleName)->first();

        if ($role && !$this->hasRole($roleName)) {
            DB::table('user_roles')->insert([
                'user_id'    => $this->id,
                'role_id'    => $role->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function removeRole(string $roleName): void
    {
        $role = DB::table('hexa_roles')->where('name', $roleName)->first();;

        if ($role) {
            DB::table('user_roles')
                ->where('user_id', $this->id)
                ->where('role_id', $role->id)
                ->delete();
        }
    }

    public function syncRoles(array $roleNames): void
    {
        $roleIds = DB::table('hexa_roles')
            ->whereIn('name', $roleNames)
            ->pluck('id')
            ->toArray();

        DB::table('user_roles')->where('user_id', $this->id)->delete();

        foreach ($roleIds as $roleId) {
            DB::table('user_roles')->insert([
                'user_id'    => $this->id,
                'role_id'    => $roleId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
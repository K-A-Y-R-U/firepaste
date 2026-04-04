<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'vip_expires_at',
        'is_vip_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'vip_expires_at' => 'datetime',
        'is_vip_active' => 'boolean',
    ];

    // Relaciones
    public function giftCodeRedemptions()
    {
        return $this->hasMany(GiftCodeRedemption::class);
    }

    // Simplificar la relación de roles - trabajar directamente con la tabla
    // public function roles()
    // {
    //     return $this->belongsToMany(
    //         \Hexters\HexaLite\Models\Role::class, 
    //         'user_roles', 
    //         'user_id', 
    //         'role_id'
    //     )->withTimestamps();
    // }

    // Métodos para VIP
    public function isVip(): bool
    {
        return $this->is_vip_active && 
               $this->vip_expires_at && 
               $this->vip_expires_at->isFuture();
    }

    // Método alternativo por compatibilidad
    public function canAccessVip(): bool
    {
        return $this->isVip();
    }

    public function getVipStatus(): array
    {
        $isActive = $this->isVip();
        $daysRemaining = 0;
        
        if ($isActive && $this->vip_expires_at) {
            $daysRemaining = now()->diffInDays($this->vip_expires_at, false);
            // Si es negativo, significa que ya expiró
            $daysRemaining = max(0, $daysRemaining);
        }

        return [
            'is_active' => $isActive,
            'expires_at' => $this->vip_expires_at,
            'days_remaining' => $daysRemaining,
        ];
    }

    // Métodos para manejo de roles - trabajar directamente con la BD
    public function hasRole(string $roleName): bool
    {
        return \DB::table('user_roles')
            ->join('hexa_roles', 'user_roles.role_id', '=', 'hexa_roles.id')
            ->where('user_roles.user_id', $this->id)
            ->where('hexa_roles.name', $roleName)
            ->exists();
    }

    public function hasAnyRole(array $roles): bool
    {
        return \DB::table('user_roles')
            ->join('hexa_roles', 'user_roles.role_id', '=', 'hexa_roles.id')
            ->where('user_roles.user_id', $this->id)
            ->whereIn('hexa_roles.name', $roles)
            ->exists();
    }

    public function assignRole(string $roleName): void
    {
        $role = \DB::table('hexa_roles')->where('name', $roleName)->first();
        
        if ($role && !$this->hasRole($roleName)) {
            \DB::table('user_roles')->insert([
                'user_id' => $this->id,
                'role_id' => $role->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function removeRole(string $roleName): void
    {
        $role = \DB::table('hexa_roles')->where('name', $roleName)->first();
        
        if ($role) {
            \DB::table('user_roles')
                ->where('user_id', $this->id)
                ->where('role_id', $role->id)
                ->delete();
        }
    }

    public function syncRoles(array $roleNames): void
    {
        $roleIds = \DB::table('hexa_roles')
            ->whereIn('name', $roleNames)
            ->pluck('id')
            ->toArray();

        \DB::table('user_roles')->where('user_id', $this->id)->delete();

        foreach ($roleIds as $roleId) {
            \DB::table('user_roles')->insert([
                'user_id' => $this->id,
                'role_id' => $roleId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    // Método para activar VIP (versión simplificada para testing)
    public function activateVip(int $days): void
    {
        $newExpirationDate = now()->addDays($days);
        
        // Si ya tiene VIP activo, extender la fecha
        if ($this->is_vip_active && $this->vip_expires_at && $this->vip_expires_at->isFuture()) {
            $newExpirationDate = $this->vip_expires_at->copy()->addDays($days);
        }

        $this->update([
            'vip_expires_at' => $newExpirationDate,
            'is_vip_active' => true,
        ]);

        // Comentar el manejo de roles por ahora para evitar errores
        // El VIP funciona basado en los campos vip_expires_at e is_vip_active
    }

    // Método para desactivar VIP (puede ser útil para tareas programadas)
    public function deactivateExpiredVip(): void
    {
        if ($this->vip_expires_at && $this->vip_expires_at->isPast()) {
            $this->update(['is_vip_active' => false]);
            $this->removeRole('VIP');
            
            // Asignar rol User básico si no tiene otro
            if (!$this->hasAnyRole(['VIP', 'Admin'])) {
                $this->assignRole('User');
            }
        }
    }
}
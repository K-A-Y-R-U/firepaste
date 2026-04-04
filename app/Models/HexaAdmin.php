<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HexaAdmin extends Model
{
    use HasFactory;

    protected $table = 'hexa_admins';

    protected $fillable = [
        'ulid',
        'name',
        'email',
        'email_verified_at',
        'online_at',
        'password',
        'is_superadmin',
        'avatar_url',
        'type',
        'state',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'online_at' => 'datetime',
        'is_superadmin' => 'boolean',
    ];

    // Relaciones inversas
    public function giftCodes()
    {
        return $this->hasMany(GiftCode::class, 'created_by');
    }
}
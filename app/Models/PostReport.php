<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PostReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id',
        'user_id',
        'reason',
        'description',
        'ip_address',
        'status',
    ];

    // Etiquetas legibles para el panel admin
    const REASONS = [
        'link_caido'           => '🔗 Enlace caído',
        'contenido_incorrecto' => '❌ Contenido incorrecto',
        'otro'                 => '💬 Otro',
    ];

    const STATUSES = [
        'pendiente' => 'Pendiente',
        'revisado'  => 'Revisado',
        'resuelto'  => 'Resuelto',
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getReasonLabelAttribute(): string
    {
        return self::REASONS[$this->reason] ?? $this->reason;
    }
}
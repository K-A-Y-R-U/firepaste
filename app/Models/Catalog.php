<?php
// app/Models/Catalog.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Catalog extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion',
        'slug',
        'activo'
    ];

    protected $casts = [
        'activo' => 'boolean'
    ];

    // Generar slug automáticamente
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($catalog) {
            if (empty($catalog->slug)) {
                $catalog->slug = Str::slug($catalog->nombre);
            }
        });

        static::updating(function ($catalog) {
            if ($catalog->isDirty('nombre')) {
                $catalog->slug = Str::slug($catalog->nombre);
            }
        });
    }

    // Relación con posts
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    // Scope para catálogos activos
    public function scopeActive($query)
    {
        return $query->where('activo', true);
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo',
        'pestana',
        'contenido',
        'catalog_id'  // ← Agregar esta línea
    ];

    // Relación con catálogo
    public function catalog()
    {
        return $this->belongsTo(Catalog::class);
    }

    // Scope para posts por catálogo
    public function scopeByCatalog($query, $catalogId)
    {
        return $query->where('catalog_id', $catalogId);
    }
}
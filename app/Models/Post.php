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
        'catalog_id',
        'views',
    ];

    // ✅ Sanitizar contenido peligroso al guardar
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($post) {
            // Eliminar scripts y eventos JS del contenido
            $post->contenido = self::sanitizeHtml($post->contenido);
        });
    }

    private static function sanitizeHtml(string $html): string
    {
        // Eliminar etiquetas <script>
        $html = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', $html);
        // Eliminar atributos de eventos JS (onclick, onload, onerror, etc.)
        $html = preg_replace('/\s*on\w+\s*=\s*["\'][^"\']*["\']/i', '', $html);
        // Eliminar javascript: en hrefs
        $html = preg_replace('/href\s*=\s*["\']?\s*javascript:[^"\'>\s]*/i', 'href="#"', $html);
        return $html;
    }

    public function catalog()
    {
        return $this->belongsTo(Catalog::class);
    }

    public function scopeByCatalog($query, $catalogId)
    {
        return $query->where('catalog_id', $catalogId);
    }
}
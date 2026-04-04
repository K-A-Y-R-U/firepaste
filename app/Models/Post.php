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
        'is_vip',
        'catalog_id',
        'views',
    ];

    protected $casts = [
        'is_vip' => 'boolean',
    ];

    // ✅ Sanitizar contenido peligroso al guardar
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($post) {
            $post->contenido = self::sanitizeHtml($post->contenido);
        });
    }

    /**
     * Sanitización robusta contra XSS.
     *
     * Cubre vectores que los regex simples no detectan:
     *  - Eventos sin comillas:      onerror=alert(1)
     *  - URLs encoded:              &#106;avascript: / \x6avascript:
     *  - Data URIs con scripts:     data:text/html,<script>...
     *  - SVG/MathML con handlers:   <svg onload=...>
     *  - Tags peligrosos:           <object>, <embed>, <base>, <form>, <meta>
     *  - srcdoc en iframes
     */
    private static function sanitizeHtml(string $html): string
    {
        // 1. Normalizar entidades HTML para detectar payloads encoded
        $html = html_entity_decode($html, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        // 2. Eliminar tags completamente peligrosos (con su contenido interno)
        $dangerousTags = 'script|iframe|object|embed|applet|form|base|link|meta|noscript|template|svg|math';
        $html = preg_replace('/<(' . $dangerousTags . ')[\s\S]*?<\/\1>/i', '', $html);
        // Tags auto-cierre: <base />, <meta />, <embed />
        $html = preg_replace('/<(' . $dangerousTags . ')\b[^>]*\/?>/i', '', $html);

        // 3. Eliminar TODOS los atributos de eventos JS (con o sin comillas)
        //    Cubre: onclick, onload, onerror, onmouseover, onanything
        $html = preg_replace('/\s+on\w+\s*=\s*(?:"[^"]*"|\'[^\']*\'|[^\s>]+)/i', '', $html);

        // 4. Eliminar javascript: / vbscript: / data: en atributos href, src, action, etc.
        $html = preg_replace(
            '/\b(href|src|action|formaction|xlink:href)\s*=\s*["\']?\s*(?:javascript|vbscript|data)\s*:/i',
            '$1="#"',
            $html
        );

        // 5. Eliminar atributos que pueden ejecutar código arbitrario
        $html = preg_replace('/\s+(srcdoc|xmlns)\s*=\s*(?:"[^"]*"|\'[^\']*\'|[^\s>]+)/i', '', $html);

        return trim($html);
    }

    // Relaciones
    public function catalog()
    {
        return $this->belongsTo(Catalog::class);
    }

    // Scopes
    public function scopeByCatalog($query, $catalogId)
    {
        return $query->where('catalog_id', $catalogId);
    }

    /** Solo posts públicos (no VIP) */
    public function scopePublic($query)
    {
        return $query->where('is_vip', false);
    }

    /** Solo posts VIP */
    public function scopeVip($query)
    {
        return $query->where('is_vip', true);
    }
}
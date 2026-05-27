<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo',
        'pestana',
        'contenido',
        'is_vip',
        'is_published',  // ← nuevo
        'catalog_id',
        'views',
    ];

    protected $casts = [
        'is_vip'       => 'boolean',
        'is_published' => 'boolean',  // ← nuevo
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($post) {
            $post->contenido = self::sanitizeHtml($post->contenido);
        });
    }

    private static function sanitizeHtml(string $html): string
    {
        $html = html_entity_decode($html, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        $dangerousTags = 'script|iframe|object|embed|applet|form|base|link|meta|noscript|template|svg|math';
        $html = preg_replace('/<(' . $dangerousTags . ')[\s\S]*?<\/\1>/i', '', $html);
        $html = preg_replace('/<(' . $dangerousTags . ')\b[^>]*\/?>/i', '', $html);
        $html = preg_replace('/\s+on\w+\s*=\s*(?:"[^"]*"|\'[^\']*\'|[^\s>]+)/i', '', $html);
        $html = preg_replace(
            '/\b(href|src|action|formaction|xlink:href)\s*=\s*["\']?\s*(?:javascript|vbscript|data)\s*:/i',
            '$1="#"',
            $html
        );
        $html = preg_replace('/\s+(srcdoc|xmlns)\s*=\s*(?:"[^"]*"|\'[^\']*\'|[^\s>]+)/i', '', $html);

        return trim($html);
    }

    public function catalog()
    {
        return $this->belongsTo(Catalog::class);
    }

    public function postViews()
    {
        return $this->hasMany(PostView::class);
    }

    public function scopeByCatalog($query, $catalogId)
    {
        return $query->where('catalog_id', $catalogId);
    }

    public function scopePublic($query)
    {
        return $query->where('is_vip', false);
    }

    public function scopeVip($query)
    {
        return $query->where('is_vip', true);
    }

    public function scopeSearch($query, string $term)
    {
        $like = '%' . addcslashes(trim($term), '%_') . '%';

        return $query
            ->leftJoin('catalogs', 'posts.catalog_id', '=', 'catalogs.id')
            ->where(function ($q) use ($like) {
                $q->whereRaw('posts.titulo LIKE ?', [$like])
                  ->orWhereRaw('catalogs.nombre LIKE ?', [$like]);
            })
            ->select('posts.*');
    }

    public function registerUniqueView(string $ip): void
    {
        $inserted = DB::table('post_views')->insertOrIgnore([
            'post_id'    => $this->id,
            'ip_address' => $ip,
            'viewed_at'  => now(),
        ]);

        if ($inserted) {
            $this->increment('views');
        }
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Catalog;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::query();

        // ✅ Búsqueda mejorada: título + nombre del catálogo
        if ($request->filled('search')) {
            $query->search($request->search);
        } else {
            // Solo hacer el JOIN de catálogos si no hay búsqueda (evita JOIN doble)
            // La relación se carga con eager loading abajo
        }

        // Filtro por catálogo (slug)
        if ($request->filled('catalog')) {
            $catalog = Catalog::where('slug', $request->catalog)->first();
            if ($catalog) {
                $query->where('posts.catalog_id', $catalog->id);
            }
        }

        // Ordenamiento
        switch ($request->input('sort')) {
            case 'most_viewed':
                $query->orderBy('posts.views', 'desc');
                break;
            default:
                $query->orderBy('posts.created_at', 'desc');
        }

        $posts    = $query->with('catalog')->paginate(15);
        $catalogs = Catalog::active()->orderBy('nombre')->get();

        return view('posts.index', compact('posts', 'catalogs'));
    }

    public function show($id)
    {
        $post = Post::findOrFail($id);

        // ✅ Contador de visitas único por IP — ventana de 24 horas
        $post->registerUniqueView(request()->ip());

        $moreConfigs = [
            'url_shortener_api_full'  => config('app.url_shortener_api_full'),
            'url_shortener_enabled'   => config('app.url_shortener_enabled', false),
        ];

        return view('posts.show', compact('post', 'moreConfigs'));
    }
}
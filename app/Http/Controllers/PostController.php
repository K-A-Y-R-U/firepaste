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

        // Filtro por búsqueda
        if ($request->has('search') && $request->search != '') {
            $query->where('titulo', 'like', '%' . $request->search . '%');
        }

        // Filtro por catálogo
        if ($request->has('catalog') && $request->catalog != '') {
            $catalog = Catalog::where('slug', $request->catalog)->first();
            if ($catalog) {
                $query->where('catalog_id', $catalog->id);
            }
        }

        // Ordenamiento
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'most_viewed':
                    $query->orderBy('views', 'desc');
                    break;
                default:
                    $query->orderBy('created_at', 'desc');
            }
        } else {
            // Orden por defecto
            $query->orderBy('created_at', 'desc');
        }

        // Obtener posts paginados
        $posts = $query->paginate(15);

        // Obtener catálogos activos
        $catalogs = Catalog::active()->orderBy('nombre')->get();

        return view('posts.index', compact('posts', 'catalogs'));
    }

    public function show($id)
    {
        $post = Post::findOrFail($id);
        
        // Incrementar contador de visitas
        $post->increment('views');
        
        // Obtener configuraciones para el acortador de URLs
        $moreConfigs = [
            'url_shortener_api_full' => config('app.url_shortener_api_full'),
            'url_shortener_enabled' => config('app.url_shortener_enabled', false)
        ];

        return view('posts.show', compact('post', 'moreConfigs'));
    }
}
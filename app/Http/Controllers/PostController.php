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

        // ✅ Búsqueda segura con binding explícito
        if ($request->filled('search')) {
            $search = '%' . addcslashes($request->search, '%_') . '%';
            $query->whereRaw('titulo LIKE ?', [$search]);
        }

        if ($request->filled('catalog')) {
            $catalog = Catalog::where('slug', $request->catalog)->first();
            if ($catalog) {
                $query->where('catalog_id', $catalog->id);
            }
        }

        switch ($request->input('sort')) {
            case 'most_viewed':
                $query->orderBy('views', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }

        $posts = $query->paginate(15);
        $catalogs = Catalog::active()->orderBy('nombre')->get();

        return view('posts.index', compact('posts', 'catalogs'));
    }

    public function show($id)
    {
        $post = Post::findOrFail($id);
        $post->increment('views');

        $moreConfigs = [
            'url_shortener_api_full' => config('app.url_shortener_api_full'),
            'url_shortener_enabled' => config('app.url_shortener_enabled', false)
        ];

        return view('posts.show', compact('post', 'moreConfigs'));
    }
}
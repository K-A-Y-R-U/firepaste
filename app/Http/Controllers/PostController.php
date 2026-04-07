<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * ✅ La lógica de búsqueda/filtrado vive en App\Livewire\SearchPosts.
     * Este método solo renderiza la vista.
     */
    public function index()
    {
        return view('posts.index');
    }

    public function show($id)
    {
        $post = Post::findOrFail($id);

        // ✅ Visita única atómica con insertOrIgnore
        $post->registerUniqueView(request()->ip());

        $moreConfigs = [
            'url_shortener_api_full'  => config('app.url_shortener_api_full'),
            'url_shortener_enabled'   => config('app.url_shortener_enabled', false),
        ];

        return view('posts.show', compact('post', 'moreConfigs'));
    }
}
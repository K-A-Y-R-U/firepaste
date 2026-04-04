<?php

namespace App\Http\Controllers;

use App\Models\Post;

class VipPostController extends Controller
{
    public function show(int $id)
    {
        // ✅ Filtra explícitamente is_vip = true.
        // Si el post no existe o no es VIP → 404 automático.
        $post = Post::where('id', $id)
            ->where('is_vip', true)
            ->firstOrFail();

        return view('vip.show', compact('post'));
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Post;

class VipPostController extends Controller
{
    public function show(int $id)
    {
        $post = Post::where('id', $id)
            ->where('is_vip', true)
            ->firstOrFail();

        // ✅ Registrar visita igual que en PostController
        $post->registerUniqueView(request()->ip());

        return view('vip.show', compact('post'));
    }
}
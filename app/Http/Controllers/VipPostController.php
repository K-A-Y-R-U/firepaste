<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class VipPostController extends Controller
{
    public function show($id)
    {
        $post = Post::findOrFail($id);
        return view('vip.show', compact('post'));
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\GeneralSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PostController extends Controller
{
    public function index()
    {
        return view('posts.index');
    }

    public function show($id)
    {
        $post = Post::findOrFail($id);

        // Si está oculto, devuelve 404
        if (!$post->is_published) {
            abort(404);
        }

        $post->registerUniqueView(request()->ip());
        return view('posts.show', compact('post'));
    }

    public function shortenUrl(Request $request)
    {
        $url = $request->query('url');

        if (!$url || !filter_var($url, FILTER_VALIDATE_URL)) {
            return response()->json(['status' => 'error', 'message' => 'URL inválida'], 400);
        }

        try {
            $settings    = GeneralSetting::first();
            $moreConfigs = $settings ? (json_decode($settings->more_configs, true) ?? []) : [];
            $apiBase     = $moreConfigs['url_shortener_api_full'] ?? null;
            $isEnabled   = $moreConfigs['url_shortener_enabled'] ?? false;

            if (!$isEnabled || !$apiBase) {
                return response()->json(['status' => 'error', 'message' => 'Acortador desactivado'], 503);
            }

            $response = Http::timeout(5)->get($apiBase . urlencode($url));

            if (!$response->successful()) {
                return response()->json(['status' => 'error', 'message' => 'Error de la API'], 502);
            }

            $body = preg_replace('/<script[^>]*>.*?<\/script>/is', '', $response->body());
            $body = trim($body);
            $data = json_decode($body, true);

            if (!$data || ($data['status'] ?? '') !== 'success' || empty($data['shortenedUrl'])) {
                return response()->json(['status' => 'error', 'message' => 'Respuesta inválida'], 502);
            }

            return response()->json([
                'status'       => 'success',
                'shortenedUrl' => $data['shortenedUrl'],
            ]);

        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Error interno'], 500);
        }
    }
}
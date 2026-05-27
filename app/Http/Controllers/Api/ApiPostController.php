<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Catalog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * API para crear posts en Firepaste desde el bot externo.
 *
 * Autenticación: token estático definido en .env
 *   BOT_API_TOKEN=tu_token_secreto_aqui
 *
 * Endpoints:
 *   GET  /api/bot/catalogs          → listar catálogos
 *   POST /api/bot/catalogs          → crear catálogo
 *   POST /api/bot/posts             → crear post
 *
 * Header requerido en todas las peticiones:
 *   X-Bot-Token: {BOT_API_TOKEN}
 */
class ApiPostController extends Controller
{
    // ─── Middleware de autenticación ───────────────────────────────────────

    private function autenticar(Request $request): bool
    {
        $token = env('BOT_API_TOKEN', '');
        if (empty($token)) {
            return false;
        }
        return $request->header('X-Bot-Token') === $token;
    }

    private function noAutorizado()
    {
        return response()->json([
            'ok'    => false,
            'error' => 'Token inválido o no configurado.',
        ], 401);
    }

    // ─── GET /api/bot/catalogs ─────────────────────────────────────────────

    public function listCatalogs(Request $request)
    {
        if (!$this->autenticar($request)) return $this->noAutorizado();

        $catalogs = Catalog::where('activo', true)
            ->orderBy('nombre')
            ->get(['id', 'nombre', 'slug']);

        return response()->json([
            'ok'       => true,
            'catalogs' => $catalogs,
        ]);
    }

    // ─── POST /api/bot/catalogs ────────────────────────────────────────────

    public function createCatalog(Request $request)
    {
        if (!$this->autenticar($request)) return $this->noAutorizado();

        $nombre = trim($request->input('nombre', ''));
        if (empty($nombre)) {
            return response()->json(['ok' => false, 'error' => 'nombre requerido'], 422);
        }

        $existing = Catalog::whereRaw('LOWER(nombre) = ?', [strtolower($nombre)])->first();
        if ($existing) {
            return response()->json([
                'ok'      => true,
                'created' => false,
                'catalog' => ['id' => $existing->id, 'nombre' => $existing->nombre, 'slug' => $existing->slug],
            ]);
        }

        $slug = Str::slug($nombre);
        $base = $slug;
        $i = 1;
        while (Catalog::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $i++;
        }

        $catalog = Catalog::create([
            'nombre'      => $nombre,
            'slug'        => $slug,
            'descripcion' => null,
            'activo'      => true,
        ]);

        return response()->json([
            'ok'      => true,
            'created' => true,
            'catalog' => ['id' => $catalog->id, 'nombre' => $catalog->nombre, 'slug' => $catalog->slug],
        ], 201);
    }

    // ─── POST /api/bot/posts ───────────────────────────────────────────────

    public function createPost(Request $request)
    {
        if (!$this->autenticar($request)) return $this->noAutorizado();

        $titulo = trim($request->input('titulo', ''));
        if (empty($titulo)) {
            return response()->json(['ok' => false, 'error' => 'titulo requerido'], 422);
        }

        $contenido = $request->input('contenido', '');
        if (empty($contenido)) {
            return response()->json(['ok' => false, 'error' => 'contenido requerido'], 422);
        }

        $catalogId   = null;
        $catalogName = trim($request->input('catalogo', ''));

        if (!empty($catalogName)) {
            $catalog = Catalog::whereRaw('LOWER(nombre) = ?', [strtolower($catalogName)])->first();

            if (!$catalog) {
                $slug = Str::slug($catalogName);
                $base = $slug; $i = 1;
                while (Catalog::where('slug', $slug)->exists()) {
                    $slug = $base . '-' . $i++;
                }
                $catalog = Catalog::create([
                    'nombre' => $catalogName,
                    'slug'   => $slug,
                    'activo' => true,
                ]);
            }

            $catalogId = $catalog->id;
        }

        // Siempre se crea como borrador — se publica manualmente desde el panel
        $post = Post::create([
            'titulo'       => $titulo,
            'catalog_id'   => $catalogId,
            'pestana'      => trim($request->input('pestana', '')),
            'contenido'    => $contenido,
            'is_vip'       => (bool) $request->input('is_vip', false),
            'is_published' => false,
        ]);

        return response()->json([
            'ok'       => true,
            'post_id'  => $post->id,
            'url'      => url('/posts/' . $post->id),
            'titulo'   => $post->titulo,
            'catalogo' => $catalogName ?: null,
        ], 201);
    }
}
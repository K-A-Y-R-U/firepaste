<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Language;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $locale = session('locale', config('app.locale', 'es'));

        // Validar contra idiomas activos en la BD
        try {
            $supported = Language::getActiveCodes();
        } catch (\Exception $e) {
            // Si la tabla no existe aún (primera instalación), usar fallback
            $supported = ['es', 'en'];
        }

        if (!in_array($locale, $supported)) {
            $locale = Language::getDefault()?->code ?? 'es';
        }

        App::setLocale($locale);

        return $next($request);
    }
}
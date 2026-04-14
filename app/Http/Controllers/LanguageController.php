<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Language;

class LanguageController extends Controller
{
    public function switch(Request $request, string $locale)
    {
        // Validar contra idiomas activos en la BD
        $supported = Language::getActiveCodes();

        if (in_array($locale, $supported)) {
            session(['locale' => $locale]);
        }

        return redirect()->back();
    }
}
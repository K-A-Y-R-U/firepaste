<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiPostController;

/*
|--------------------------------------------------------------------------
| Bot API Routes — Firepaste
|--------------------------------------------------------------------------
|
| Estas rutas son usadas por el bot externo (firepaste-pywebview) para
| crear posts y catálogos sin necesidad de abrir el navegador.
|
| Autenticación: header  X-Bot-Token: {BOT_API_TOKEN}
|
| Configurar en .env:
|   BOT_API_TOKEN=pon_aqui_un_token_secreto_largo
|
*/

Route::prefix('bot')->group(function () {

    // Catálogos
    Route::get('/catalogs',  [ApiPostController::class, 'listCatalogs']);
    Route::post('/catalogs', [ApiPostController::class, 'createCatalog']);

    // Posts
    Route::post('/posts', [ApiPostController::class, 'createPost']);

});

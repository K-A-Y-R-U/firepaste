<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\PostController;
use App\Http\Controllers\VipPostController;
use App\Http\Controllers\GiftCodeController;
use App\Http\Controllers\MembresiaController;
use App\Http\Controllers\LanguageController;

// ✅ Ruta para cambiar idioma — sin tocar las demás URLs
Route::get('/lang/{locale}', [LanguageController::class, 'switch'])
    ->name('lang.switch')
    ->where('locale', 'es|en');

// Rutas públicas
Route::get('/', [PostController::class, 'index'])->name('home');
Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
Route::get('/posts/{id}', [PostController::class, 'show'])->name('posts.show');
Route::get('/memberships', [MembresiaController::class, 'index'])->name('membresias');

// Rutas protegidas
Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

// ✅ Gift codes con rate limiting
Route::middleware(['auth', 'throttle:20,1'])->group(function () {
    Route::get('/gift-codes/redeem', [GiftCodeController::class, 'showRedeemForm'])->name('gift-codes.redeem');
    Route::post('/gift-codes/redeem', [GiftCodeController::class, 'redeem'])->name('gift-codes.redeem.process');
    Route::get('/gift-codes/success', [GiftCodeController::class, 'success'])->name('gift-codes.success');
    Route::get('/gift-codes/my-redemptions', [GiftCodeController::class, 'myRedemptions'])->name('gift-codes.my-redemptions');
});

// Rutas VIP
Route::middleware(['auth', 'vip'])->group(function () {
    Route::get('/vip/{id}', [VipPostController::class, 'show'])->name('vip.show');
});

// Logout
Route::post('/logout', function (Request $request) {
    \Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
})->name('logout')->middleware('auth');

require __DIR__.'/auth.php';
<?php

namespace App\Http\Controllers;

use App\Models\GiftCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;

class GiftCodeController extends Controller
{
    public function showRedeemForm()
    {
        $user = Auth::user();
        return view('gift-codes.redeem', [
            'user' => $user,
            'vipStatus' => $user->getVipStatus(),
        ]);
    }

    public function redeem(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:20',
        ]);

        $user = Auth::user();

        // ✅ Rate limiting: máximo 5 intentos por minuto por usuario
        $key = 'gift-code-redeem:' . $user->id;
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            return back()->withErrors([
                'code' => "Demasiados intentos. Espera {$seconds} segundos antes de intentarlo de nuevo.",
            ]);
        }
        RateLimiter::hit($key, 60);

        // ✅ Rate limiting adicional por IP
        $ipKey = 'gift-code-ip:' . $request->ip();
        if (RateLimiter::tooManyAttempts($ipKey, 10)) {
            return back()->withErrors([
                'code' => 'Demasiados intentos desde tu dirección IP. Intenta más tarde.',
            ]);
        }
        RateLimiter::hit($ipKey, 60);

        $code = strtoupper(trim($request->code));

        try {
            DB::beginTransaction();

            $giftCode = GiftCode::where('code', $code)->first();

            if (!$giftCode) {
                return back()->withErrors(['code' => 'Código no válido o no encontrado.']);
            }

            if (!$giftCode->canBeUsedBy($user)) {
                return back()->withErrors(['code' => $this->getErrorMessage($giftCode, $user)]);
            }

            $redemption = $giftCode->redeem($user);

            // ✅ Limpiar rate limiter al tener éxito
            RateLimiter::clear($key);

            DB::commit();

            return redirect()->route('gift-codes.success')->with([
                'success' => true,
                'message' => "¡Código canjeado exitosamente! Has recibido {$giftCode->vip_days} días de VIP.",
                'vip_days' => $giftCode->vip_days,
                'expires_at' => $redemption->vip_ends_at,
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error al canjear gift code', [
                'user_id' => $user->id,
                'code' => $code,
                'error' => $e->getMessage(),
            ]);
            return back()->withErrors([
                'code' => 'Ocurrió un error al procesar el código. Inténtalo de nuevo.',
            ]);
        }
    }

    public function success()
    {
        if (!session('success')) {
            return redirect()->route('gift-codes.redeem');
        }

        $user = Auth::user();
        return view('gift-codes.success', [
            'user' => $user,
            'vipStatus' => $user->getVipStatus(),
            'message' => session('message'),
            'vip_days' => session('vip_days'),
            'expires_at' => session('expires_at'),
        ]);
    }

    public function myRedemptions()
    {
        $user = Auth::user();
        $redemptions = $user->giftCodeRedemptions()
            ->with('giftCode')
            ->orderBy('redeemed_at', 'desc')
            ->get();

        return view('gift-codes.my-redemptions', [
            'user' => $user,
            'redemptions' => $redemptions,
            'vipStatus' => $user->getVipStatus(),
        ]);
    }

    private function getErrorMessage(GiftCode $giftCode, $user): string
    {
        if (!$giftCode->is_active) return 'Este código está desactivado.';
        if ($giftCode->expires_at && $giftCode->expires_at->isPast()) return 'Este código ha expirado.';
        if ($giftCode->used_count >= $giftCode->max_uses) return 'Este código ya ha alcanzado su límite de usos.';
        if ($giftCode->redemptions()->where('user_id', $user->id)->exists()) return 'Ya has usado este código anteriormente.';
        return 'Este código no puede ser usado en este momento.';
    }
}
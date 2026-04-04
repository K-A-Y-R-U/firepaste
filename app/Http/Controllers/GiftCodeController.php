<?php

namespace App\Http\Controllers;

use App\Models\GiftCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GiftCodeController extends Controller
{
    // Eliminar el constructor ya que el middleware se aplica en las rutas

    public function showRedeemForm()
    {
        $user = Auth::user();
        $vipStatus = $user->getVipStatus();
        
        return view('gift-codes.redeem', [
            'user' => $user,
            'vipStatus' => $vipStatus
        ]);
    }

    public function redeem(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:20',
        ]);

        $user = Auth::user();
        $code = strtoupper(trim($request->code));

        try {
            DB::beginTransaction();

            // Buscar el código
            $giftCode = GiftCode::where('code', $code)->first();

            if (!$giftCode) {
                return back()->withErrors([
                    'code' => 'Código no válido o no encontrado.'
                ]);
            }

            // Verificar si puede ser usado por este usuario
            if (!$giftCode->canBeUsedBy($user)) {
                $errorMessage = $this->getErrorMessage($giftCode, $user);
                return back()->withErrors([
                    'code' => $errorMessage
                ]);
            }

            // Canjear el código
            $redemption = $giftCode->redeem($user);

            DB::commit();

            return redirect()->route('gift-codes.success')->with([
                'success' => true,
                'message' => "¡Código canjeado exitosamente! Has recibido {$giftCode->vip_days} días de VIP.",
                'vip_days' => $giftCode->vip_days,
                'expires_at' => $redemption->vip_ends_at,
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            
            return back()->withErrors([
                'code' => 'Error al canjear el código: ' . $e->getMessage()
            ]);
        }
    }

    public function success()
    {
        if (!session('success')) {
            return redirect()->route('gift-codes.redeem');
        }

        $user = Auth::user();
        $vipStatus = $user->getVipStatus();

        return view('gift-codes.success', [
            'user' => $user,
            'vipStatus' => $vipStatus,
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
            'vipStatus' => $user->getVipStatus()
        ]);
    }

    private function getErrorMessage(GiftCode $giftCode, $user): string
    {
        if (!$giftCode->is_active) {
            return 'Este código está desactivado.';
        }

        if ($giftCode->expires_at && $giftCode->expires_at->isPast()) {
            return 'Este código ha expirado.';
        }

        if ($giftCode->used_count >= $giftCode->max_uses) {
            return 'Este código ya ha alcanzado su límite de usos.';
        }

        // Verificar si el usuario ya lo usó
        if ($giftCode->redemptions()->where('user_id', $user->id)->exists()) {
            return 'Ya has usado este código anteriormente.';
        }

        return 'Este código no puede ser usado en este momento.';
    }
}
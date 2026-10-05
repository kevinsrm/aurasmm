<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Coupon;

class CouponController extends Controller
{
    public function redeem(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        $code = strtoupper(trim($request->code));
        $coupon = Coupon::where('code', $code)->first();

        if (!$coupon || !$coupon->is_active) {
            return back()->with('error', 'Cupom inválido ou expirado.');
        }

        $user = Auth::user();

        // Check if user already used this coupon
        if ($user->coupons()->where('coupon_id', $coupon->id)->exists()) {
            return back()->with('error', 'Você já resgatou este cupom anteriormente.');
        }

        if ($coupon->used_count >= $coupon->max_uses) {
            return back()->with('error', 'Este cupom já atingiu o limite máximo de resgates.');
        }

        // Attach coupon to user, increment used_count, add balance
        $user->coupons()->attach($coupon->id);
        $coupon->used_count += 1;
        $coupon->save();

        $user->balance += $coupon->amount;
        $user->save();

        return back()->with('success', 'Cupom resgatado com sucesso! R$ ' . number_format($coupon->amount, 2, ',', '.') . ' adicionados ao seu saldo.');
    }
}

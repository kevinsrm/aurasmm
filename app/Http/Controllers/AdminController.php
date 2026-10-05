<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use App\Models\Order;
use App\Models\Coupon;
use App\Models\Deposit;
use App\Notifications\NewCouponNotification;
use App\Models\Setting;
use Illuminate\Support\Facades\Notification;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalUsers = User::count();
        $totalOrders = Order::count();
        $totalRevenue = Order::sum('charge');
        $totalDeposits = Deposit::where('status', 'approved')->sum('amount');
        
        $apiKey = Setting::get('smm_api_key', "dbe48289d6d94754380128791a3824a4628489962707031a450f9ed11ec226e6");
        $apiBalanceRes = Http::asForm()->post("https://smmhub.com.br/api/v2", [
            "key" => $apiKey,
            "action" => "balance"
        ])->json();

        $apiBalance = $apiBalanceRes['balance'] ?? '0.00';
        $apiCurrency = $apiBalanceRes['currency'] ?? 'BRL';

        $recentOrders = Order::with('user')->latest()->take(5)->get();
        Order::syncApiStatuses($recentOrders);

        return view('admin.dashboard', compact('totalUsers', 'totalOrders', 'totalRevenue', 'totalDeposits', 'apiBalance', 'apiCurrency', 'recentOrders'));
    }

    public function users()
    {
        $users = User::latest()->paginate(15);
        return view('admin.users', compact('users'));
    }

    public function updateUserBalance(Request $request, $id)
    {
        $request->validate([
            'amount' => 'required|numeric',
            'action' => 'required|in:add,subtract,set'
        ]);

        $user = User::findOrFail($id);
        $amount = (float)$request->amount;

        if ($request->action === 'add') {
            $user->balance += $amount;
        } elseif ($request->action === 'subtract') {
            $user->balance = max(0, $user->balance - $amount);
        } elseif ($request->action === 'set') {
            $user->balance = max(0, $amount);
        }

        $user->save();

        return back()->with('success', 'Saldo do usuário atualizado com sucesso!');
    }

    public function toggleAdmin($id)
    {
        $user = User::findOrFail($id);
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Você não pode alterar seu próprio status de admin.');
        }
        $user->is_admin = !$user->is_admin;
        $user->save();

        return back()->with('success', 'Status de administrador alterado com sucesso!');
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Você não pode deletar sua própria conta.');
        }
        $user->delete();

        return back()->with('success', 'Usuário excluído com sucesso!');
    }

    public function orders()
    {
        $orders = Order::with('user')->latest()->paginate(20);
        Order::syncApiStatuses($orders->items());
        return view('admin.orders', compact('orders'));
    }

    public function refunds(Request $request)
    {
        $query = Order::with(['user', 'refundedBy']);

        // Filtro por status do reembolso: todos | nao-reembolsados | reembolsados
        $refundFilter = $request->input('refund_status');
        if ($refundFilter === 'approved') {
            $query->where('refund_status', 'approved');
        } elseif ($refundFilter === 'none') {
            $query->where('refund_status', '!=', 'approved');
        }

        $search = trim($request->input('search') ?? '');
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('api_order_id', 'like', "%{$search}%")
                  ->orWhere('service_name', 'like', "%{$search}%")
                  ->orWhere('link', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        $orders = $query->latest()->paginate(20)->withQueryString();
        Order::syncApiStatuses($orders->items());

        $stats = [
            'refunded' => Order::where('refund_status', 'approved')->count(),
            'refunded_total' => (float)Order::where('refund_status', 'approved')->sum('charge'),
        ];

        return view('admin.refunds', compact('orders', 'stats'));
    }

    public function refundOrder(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        $order = Order::with('user')->findOrFail($id);

        if ($order->isRefunded()) {
            return back()->with('error', 'Este pedido já foi reembolsado. Use "Estornar reembolso" para desfazer.');
        }
        if (!$order->user) {
            return back()->with('error', 'Não é possível reembolsar este pedido: o usuário foi excluído.');
        }

        $amount = (float)$order->charge;

        $order->refund_status = 'approved';
        $order->refund_reason = $request->reason;
        $order->refunded_at = now();
        $order->refunded_by = auth()->id();
        $order->save();

        // Devolve o valor ao saldo do cliente
        $order->user->balance += $amount;
        $order->user->save();

        return back()->with('success', "Pedido #{$order->api_order_id} reembolsado: R$ " . number_format($amount, 2, ',', '.') . " devolvidos ao saldo de " . $order->user->name . '.');
    }

    public function unrefundOrder($id)
    {
        $order = Order::with('user')->findOrFail($id);

        if (!$order->isRefunded()) {
            return back()->with('error', 'Este pedido não está reembolsado.');
        }
        if (!$order->user) {
            return back()->with('error', 'Não é possível estornar: o usuário foi excluído.');
        }

        $amount = (float)$order->charge;

        $order->refund_status = 'none';
        $order->refund_reason = null;
        $order->refunded_at = null;
        $order->refunded_by = null;
        $order->save();

        // Estorna: retira do saldo, sem deixar negativo
        $order->user->balance = max(0, (float)$order->user->balance - $amount);
        $order->user->save();

        return back()->with('success', "Reembolso do pedido #{$order->api_order_id} estornado: R$ " . number_format($amount, 2, ',', '.') . ' retirados do saldo.');
    }

    public function coupons()
    {
        $coupons = Coupon::latest()->paginate(15);
        return view('admin.coupons', compact('coupons'));
    }

    public function storeCoupon(Request $request)
    {
        $request->validate([
            'code' => 'required|string|unique:coupons,code|max:50',
            'amount' => 'required|numeric|min:0.01',
            'max_uses' => 'required|integer|min:1',
        ]);

        $coupon = Coupon::create([
            'code' => strtoupper($request->code),
            'amount' => $request->amount,
            'max_uses' => $request->max_uses,
            'is_active' => true,
        ]);

        // Notify all users about the new coupon
        $users = User::all();
        Notification::send($users, new NewCouponNotification($coupon));

        return back()->with('success', 'Cupom criado e notificação enviada para todos os usuários!');
    }

    public function deleteCoupon($id)
    {
        Coupon::findOrFail($id)->delete();
        return back()->with('success', 'Cupom excluído com sucesso!');
    }

    public function settings()
    {
        $smmApiKey = Setting::get('smm_api_key', "dbe48289d6d94754380128791a3824a4628489962707031a450f9ed11ec226e6");
        $mpToken = Setting::get('mercadopago_token', "");
        $profitMargin = Setting::get('profit_margin', 0);
        
        $adGoogleTagId = Setting::get('ad_google_tag_id', "");
        $adFbPixelId = Setting::get('ad_fb_pixel_id', "");
        $adTiktokPixelId = Setting::get('ad_tiktok_pixel_id', "");
        $adCustomHeadScript = Setting::get('ad_custom_head_script', "");
        $adCustomBodyScript = Setting::get('ad_custom_body_script', "");

        $seoTitle = Setting::get('seo_title', "AuraSMM - Painel SMM");
        $seoDescription = Setting::get('seo_description', "Painel SMM de revenda de seguidores, curtidas e visualizações com os melhores preços.");
        $seoKeywords = Setting::get('seo_keywords', "painel smm, seguidores, instagram, tiktok, youtube, curtidas");
        $seoOgImage = Setting::get('seo_og_image', "");
        $supportWhatsapp = Setting::get('support_whatsapp', "");

        return view('admin.settings', compact(
            'smmApiKey', 'mpToken', 'profitMargin',
            'adGoogleTagId', 'adFbPixelId', 'adTiktokPixelId', 'adCustomHeadScript', 'adCustomBodyScript',
            'seoTitle', 'seoDescription', 'seoKeywords', 'seoOgImage', 'supportWhatsapp'
        ));
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'smm_api_key' => 'nullable|string',
            'mercadopago_token' => 'nullable|string',
            'profit_margin' => 'nullable|numeric|min:0',
            'ad_google_tag_id' => 'nullable|string',
            'ad_fb_pixel_id' => 'nullable|string',
            'ad_tiktok_pixel_id' => 'nullable|string',
            'ad_custom_head_script' => 'nullable|string',
            'ad_custom_body_script' => 'nullable|string',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string|max:500',
            'seo_keywords' => 'nullable|string|max:255',
            'seo_og_image' => 'nullable|string|max:500',
            'support_whatsapp' => 'nullable|string|max:30',
        ]);

        if ($request->filled('smm_api_key')) {
            Setting::set('smm_api_key', $request->smm_api_key);
        }
        if ($request->filled('mercadopago_token')) {
            Setting::set('mercadopago_token', $request->mercadopago_token);
        }
        if ($request->has('profit_margin')) {
            Setting::set('profit_margin', $request->profit_margin);
        }
        
        Setting::set('ad_google_tag_id', $request->ad_google_tag_id ?? '');
        Setting::set('ad_fb_pixel_id', $request->ad_fb_pixel_id ?? '');
        Setting::set('ad_tiktok_pixel_id', $request->ad_tiktok_pixel_id ?? '');
        Setting::set('ad_custom_head_script', $request->ad_custom_head_script ?? '');
        Setting::set('ad_custom_body_script', $request->ad_custom_body_script ?? '');

        Setting::set('seo_title', $request->seo_title ?? '');
        Setting::set('seo_description', $request->seo_description ?? '');
        Setting::set('seo_keywords', $request->seo_keywords ?? '');
        Setting::set('seo_og_image', $request->seo_og_image ?? '');
        Setting::set('support_whatsapp', $request->support_whatsapp ?? '');

        return back()->with('success', 'Configurações salvas com sucesso!');
    }
}

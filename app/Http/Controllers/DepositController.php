<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Models\Deposit;
use App\Models\Setting;

class DepositController extends Controller
{
    private $mpAccessToken = "APP_USR-3796853874619472-092520-25251a3a30c0c226e6f9ed11ec226e6"; // Default placeholder key, can be updated by admin in settings
    private $apiUrl = "https://api.mercadopago.com/v1/payments";

    public function show()
    {
        $user = Auth::user();

        // Expirar depósitos pendentes que passaram de 10 minutos
        $deposits = Deposit::where('user_id', $user->id)
            ->latest()
            ->get()
            ->each(function ($dep) {
                if ($dep->status === 'pending' && $dep->created_at->addMinutes(10)->isPast()) {
                    $dep->status = 'cancelled';
                    $dep->save();
                }
            });

        return view('site.deposit', compact('deposits'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1|max:10000',
        ]);

        $user = Auth::user();
        $amount = (float)$request->amount;

        // Custom setting value if exists
        $customToken = Setting::get('mercadopago_token', $this->mpAccessToken);

        // Define tempo de expiração do PIX para 10 minutos
        // Formato exigido pelo Mercado Pago: yyyy-MM-dd'T'HH:mm:ssz (ex.: 2026-09-28T15:00:00-03:00)
        $dateOfExpiration = now()->addMinutes(10)
            ->setTimezone('America/Sao_Paulo')
            ->format('Y-m-d\TH:i:sP');

        $payload = [
            'transaction_amount' => $amount,
            'payment_method_id' => 'pix',
            'date_of_expiration' => $dateOfExpiration,
            'description' => 'Adição de saldo no AuraSMM para ' . $user->name,
            'payer' => [
                'email' => $user->email,
                'first_name' => explode(' ', $user->name)[0],
                'last_name' => explode(' ', $user->name)[1] ?? 'SMM',
            ]
        ];

        // Call Mercado Pago API to create Pix payment
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $customToken,
            'Content-Type' => 'application/json',
            'X-Idempotency-Key' => uniqid()
        ])->post($this->apiUrl, $payload);

        // Fallback: o Mercado Pago (sandbox) pode rejeitar o formato de date_of_expiration
        // (erro 23). Re-cria sem o campo; a expiração de 10 min segue valendo no nosso painel.
        if ($response->status() === 400 && str_contains($response->json('message') ?? '', 'date_of_expiration')) {
            unset($payload['date_of_expiration']);
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $customToken,
                'Content-Type' => 'application/json',
                'X-Idempotency-Key' => uniqid()
            ])->post($this->apiUrl, $payload);
        }

        // Retentativas: o sandbox do MP retorna 500/503/429 de forma intermitente
        $attempts = 2;
        while (in_array($response->status(), [408, 429, 500, 502, 503, 504]) && $attempts > 0) {
            $attempts--;
            usleep(500000); // pausa de 0,5s entre tentativas
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $customToken,
                'Content-Type' => 'application/json',
                'X-Idempotency-Key' => uniqid()
            ])->post($this->apiUrl, $payload);
        }

        $data = $response->json();

        if ($response->successful() && isset($data['id'])) {
            $paymentId = $data['id'];
            $qrCode = $data['point_of_interaction']['transaction_data']['qr_code'] ?? null;
            $qrCodeBase64 = $data['point_of_interaction']['transaction_data']['qr_code_base64'] ?? null;

            $deposit = Deposit::create([
                'user_id' => $user->id,
                'payment_id' => $paymentId,
                'amount' => $amount,
                'status' => 'pending',
                'qr_code' => $qrCode,
                'qr_code_base64' => $qrCodeBase64
            ]);

            return redirect()->route('deposit.showPayment', $deposit->id)->with('success', 'PIX Gerado com sucesso!');
        } else {
            $errorMsg = $data['message'] ?? 'Erro desconhecido ao tentar gerar o PIX no Mercado Pago. Verifique as configurações.';
            return back()->withErrors(['api' => $errorMsg])->withInput();
        }
    }

    public function showPayment($id)
    {
        $deposit = Deposit::where('user_id', Auth::id())->findOrFail($id);

        // Expiracao: se o PIX continuo pendente por mais de 10 minutos, cancela
        if ($deposit->status === 'pending' && $deposit->created_at->addMinutes(10)->isPast()) {
            $deposit->status = 'cancelled';
            $deposit->save();
        }

        return view('site.payment', compact('deposit'));
    }

    public function checkStatus($id)
    {
        $deposit = Deposit::where('user_id', Auth::id())->findOrFail($id);

        if ($deposit->status === 'approved') {
            return response()->json(['status' => 'approved']);
        }

        $customToken = Setting::get('mercadopago_token', $this->mpAccessToken);

        // Fetch payment status from Mercado Pago
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $customToken,
        ])->get($this->apiUrl . '/' . $deposit->payment_id);

        $data = $response->json();
        $mpStatus = $data['status'] ?? null; // approved, pending, cancelled, rejected, expired

        // Expiracao: se passou de 10 min e nao ha pagamento aprovado, cancela
        $expirationTime = $deposit->created_at->addMinutes(10);
        if ($deposit->status === 'pending' && now()->greaterThan($expirationTime) && $mpStatus !== 'approved') {
            $deposit->status = 'cancelled';
            $deposit->save();
            return response()->json(['status' => 'cancelled']);
        }

        if ($response->successful() && isset($mpStatus)) {
            if ($mpStatus === 'approved' && $deposit->status !== 'approved') {
                $deposit->status = 'approved';
                $deposit->save();

                // Add balance to user
                $user = $deposit->user;
                $user->balance += $deposit->amount;
                $user->save();
            } elseif (in_array($mpStatus, ['cancelled', 'rejected', 'expired'])) {
                $deposit->status = 'cancelled';
                $deposit->save();
            }

            return response()->json(['status' => $deposit->status]);
        }

        return response()->json(['status' => $deposit->status]);
    }
}

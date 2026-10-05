<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\Setting;

class ApiController extends Controller
{
    private $apiKey = "dbe48289d6d94754380128791a3824a4628489962707031a450f9ed11ec226e6";
    private $apiUrl = "https://smmhub.com.br/api/v2";

    public function index()
    {
        $customApiKey = Setting::get('smm_api_key', $this->apiKey);

        $response = Http::asForm()->post($this->apiUrl, [
            "key" => $customApiKey,
            "action" => "services"
        ]);
        $itens = $response->json();
        
        if (!is_array($itens)) {
            $itens = [];
        }

        // Filtra para remover as categorias indesejadas
        $itens = array_values(array_filter($itens, function ($item) {
            $cat = mb_strtoupper(trim($item['category'] ?? ''));
            if (strpos($cat, 'EXCLUSIVO DA SMMHUB') !== false || strpos($cat, 'NÃO USE') !== false || strpos($cat, 'BANIMENTO') !== false) {
                return false;
            }
            return true;
        }));

        // Busca o mapeamento de descrições do site da SMMHub e armazena em cache por 1 hora
        $descriptions = \Illuminate\Support\Facades\Cache::driver('file')->remember('smm_descriptions', 3600, function () {
            try {
                $htmlResponse = Http::get('https://smmhub.com.br/services');
                if ($htmlResponse->successful()) {
                    $html = $htmlResponse->body();
                    if (preg_match_all('/<script[^>]*>(.*?)<\/script>/s', $html, $matches)) {
                        foreach ($matches[1] as $script) {
                            if (strlen($script) > 50000 && preg_match('/(\{[\s\S]*?\"(1066|195|14)\"[\s\S]*?\})/s', $script, $m)) {
                                $decoded = json_decode($m[1], true);
                                if (is_array($decoded) && count($decoded) > 50) {
                                    return $decoded;
                                }
                            }
                        }
                    }
                }
            } catch (\Exception $e) {
                // Em caso de erro, retorna array vazio para não quebrar a página
            }
            return [];
        });

        // Mescla as descrições nos itens de serviço
        $profitMargin = (float)Setting::get('profit_margin', 0) / 100;
        foreach ($itens as &$item) {
            $serviceId = (string)($item['service'] ?? '');
            $rawDesc = $descriptions[$serviceId] ?? '';
            // Remove o rodapé do SMMHub (aviso de acompanhar pedido)
            $cleanedDesc = preg_replace('/(<br\s*\/?>|\r|\n)*\s*👇?\s*Para acompanhar o andamento do seu pedido[\s\S]*$/ui', '', $rawDesc);
            $cleanedDesc = preg_replace('/(?:<br\s*\/?>|\r|\n|\s)+$/ui', '', $cleanedDesc);
            $item['description'] = trim($cleanedDesc);
            
            // Aplica a margem de lucro no preço
            $originalRate = (float)$item['rate'];
            $item['rate'] = number_format($originalRate * (1 + $profitMargin), 2, '.', '');
        }
        unset($item);
        
        $categories = collect($itens)->pluck('category')->unique()->values()->all();
        
        return view("site.services", compact("itens", "categories"));
    }

    public function storeOrder(Request $request)
    {
        $request->validate([
            'service' => 'required|integer',
            'link' => 'required|url',
            'quantity' => 'required|integer|min:1',
        ]);

        $customApiKey = Setting::get('smm_api_key', $this->apiKey);

        $servicesResponse = Http::asForm()->post($this->apiUrl, [
            "key" => $customApiKey,
            "action" => "services"
        ]);
        $services = $servicesResponse->json();
        
        $selectedService = collect($services)->firstWhere('service', (int)$request->service);
        if (!$selectedService) {
            return back()->withErrors(['service' => 'Serviço inválido selecionado.'])->withInput();
        }

        $profitMargin = (float)Setting::get('profit_margin', 0) / 100;
        $originalRate = (float)($selectedService['rate'] ?? 0);
        $rate = number_format($originalRate * (1 + $profitMargin), 2, '.', '');
        $quantity = (int)$request->quantity;
        
        $runs = (int)($request->runs ?? 1);
        if ($runs < 1) $runs = 1;
        $totalQuantity = $quantity * $runs;
        $charge = ($totalQuantity * $rate) / 1000;

        $user = Auth::user();

        // Check if user has enough balance
        if ($user->balance < $charge) {
            return back()->withErrors(['balance' => 'Saldo insuficiente! Adicione saldo antes de realizar este pedido. Custo: R$ ' . number_format($charge, 2, ',', '.') . ' | Seu Saldo: R$ ' . number_format($user->balance, 2, ',', '.')])->withInput();
        }

        $payload = [
            'key' => $customApiKey,
            'action' => 'add',
            'service' => $request->service,
            'link' => $request->link,
            'quantity' => $quantity,
        ];

        if ($request->filled('comments')) {
            $payload['comments'] = $request->comments;
        }
        if ($request->filled('answer_number')) {
            $payload['answer_number'] = $request->answer_number;
        }
        if ($request->filled('username')) {
            $payload['username'] = $request->username;
        }
        if ($request->boolean('dripfeed')) {
            $payload['runs'] = $runs;
            $payload['interval'] = (int)$request->interval;
        }

        $apiResponse = Http::asForm()->post($this->apiUrl, $payload);
        $responseData = $apiResponse->json();

        if (isset($responseData['order'])) {
            // Deduct balance from user
            $user->balance -= $charge;
            $user->save();

            Order::create([
                'user_id' => $user->id,
                'api_order_id' => $responseData['order'],
                'service_id' => $selectedService['service'],
                'service_name' => $selectedService['name'],
                'link' => $request->link,
                'quantity' => $totalQuantity,
                'charge' => $charge,
                'status' => 'Pending',
                'comments' => $request->comments,
                'answer_number' => $request->answer_number,
                'username' => $request->username,
                'runs' => $request->boolean('dripfeed') ? $runs : null,
                'interval' => $request->boolean('dripfeed') ? $request->interval : null,
                'api_response' => json_encode($responseData),
            ]);

            return redirect()->route('orders.index')->with('success', 'Pedido realizado com sucesso! ID do Pedido: #' . $responseData['order']);
        } else {
            $errorMsg = $responseData['error'] ?? 'Erro desconhecido ao processar pedido na API do fornecedor.';
            return back()->withErrors(['api' => $errorMsg])->withInput();
        }
    }

    public function ordersIndex()
    {
        $orders = Order::where('user_id', Auth::id())->latest()->paginate(15);
        Order::syncApiStatuses($orders->items());
        return view('site.orders', compact('orders'));
    }

    public function checkStatus($id)
    {
        $order = Order::where('user_id', Auth::id())->findOrFail($id);
        if (!$order->api_order_id) {
            return back()->with('error', 'Este pedido não possui ID na API.');
        }

        $customApiKey = Setting::get('smm_api_key', $this->apiKey);

        $response = Http::asForm()->post($this->apiUrl, [
            'key' => $customApiKey,
            'action' => 'status',
            'order' => $order->api_order_id
        ]);
        $data = $response->json();

        if (isset($data['status'])) {
            $order->status = ucfirst($data['status']);
            $order->save();
            return back()->with('success', 'Status atualizado com sucesso: ' . $order->status);
        } else {
            return back()->with('error', $data['error'] ?? 'Erro ao consultar status.');
        }
    }

    public function getBalance()
    {
        $customApiKey = Setting::get('smm_api_key', $this->apiKey);

        $response = Http::asForm()->post($this->apiUrl, [
            'key' => $customApiKey,
            'action' => 'balance'
        ]);
        return response()->json($response->json());
    }
}

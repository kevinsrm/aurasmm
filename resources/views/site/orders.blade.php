@extends("layouts.layout")

@section("titulo1", "Painel SMM - Meus Pedidos")

@section("content")
<div class="max-w-4xl mx-auto p-6 bg-white shadow-lg rounded-xl my-8">
  <div class="flex justify-between items-center mb-6 border-b pb-4">
    <h2 class="text-2xl font-bold text-gray-800">Meus Pedidos SMM</h2>
    <div class="flex gap-3 text-sm">
      <a href="{{ route('services') }}" class="text-gray-600 hover:text-blue-600">Novo Pedido</a>
      <a href="{{ route('orders.index') }}" class="text-blue-600 font-semibold hover:underline">Meus Pedidos</a>
    </div>
  </div>

  @if(session('success'))
    <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">
      {{ session('success') }}
    </div>
  @endif

  @if(session('error'))
    <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm">
      {{ session('error') }}
    </div>
  @endif

  <div class="overflow-x-auto">
    <table class="w-full text-left border-collapse">
      <thead>
        <tr class="bg-gray-100 border-b text-xs font-semibold text-gray-600 uppercase">
          <th class="p-3">ID API</th>
          <th class="p-3">Serviço</th>
          <th class="p-3">Link</th>
          <th class="p-3">Qtd</th>
          <th class="p-3">Custo</th>
          <th class="p-3">Status</th>
          <th class="p-3">Data</th>
          <th class="p-3 text-center">Ações</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-200 text-sm">
        @forelse($orders as $order)
          <tr>
            <td class="p-3 font-bold text-blue-600">#{{ $order->api_order_id }}</td>
            <td class="p-3 max-w-xs truncate" title="{{ $order->service_name }}">
              <span class="text-xs text-gray-400">[{{ $order->service_id }}]</span> {{ $order->service_name }}
            </td>
            <td class="p-3 max-w-xs truncate">
              <a href="{{ $order->link }}" target="_blank" class="text-blue-500 underline" title="{{ $order->link }}">{{ $order->link }}</a>
            </td>
            <td class="p-3">{{ number_format($order->quantity, 0, ',', '.') }}</td>
            <td class="p-3 font-semibold text-green-600">R$ {{ number_format($order->charge, 2, ',', '.') }}</td>
            <td class="p-3">
              @if($order->isRefunded())
                <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-100 text-rose-800" title="Valor devolvido ao saldo">
                  Reembolsado
                </span>
              @else
                <span class="px-2.5 py-1 rounded-full text-xs font-semibold 
                  @if(in_array(strtolower($order->status), ['completed', 'concluído'])) bg-green-100 text-green-800
                  @elseif(in_array(strtolower($order->status), ['pending', 'pendente', 'processing', 'in progress'])) bg-yellow-100 text-yellow-800
                  @else bg-gray-100 text-gray-800 @endif">
                  {{ $order->status }}
                </span>
              @endif
            </td>
            <td class="p-3 text-xs text-gray-500">{{ $order->created_at->format('d/m/Y H:i') }}</td>
            <td class="p-3 text-center">
              <div class="flex items-center justify-center gap-1.5 flex-wrap">
                <form action="{{ route('orders.status', $order->id) }}" method="POST" class="inline">
                  @csrf
                  <button type="submit" class="px-2.5 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-medium rounded transition-colors" title="Atualizar status do pedido">
                    Atualizar
                  </button>
                </form>

                @if(!$order->isRefunded())
                  @php
                    $whatsapp = \App\Models\Setting::get('support_whatsapp', '');
                    $msg = "Olá, preciso solicitar o reembolso do pedido #" . $order->api_order_id . " (" . $order->service_name . ") no valor de R$ " . number_format($order->charge, 2, ',', '.') . ". Poderia analisar por favor?";
                    $waLink = $whatsapp ? "https://wa.me/" . $whatsapp . "?text=" . urlencode($msg) : route('profile.show');
                  @endphp
                  <a href="{{ $waLink }}" target="_blank" class="px-2.5 py-1 bg-green-50 hover:bg-green-100 text-green-700 text-xs font-bold rounded transition-colors flex items-center gap-1" title="Solicitar reembolso via WhatsApp">
                    <span>💬</span> Reembolso
                  </a>
                @endif
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="8" class="p-6 text-center text-gray-400">Nenhum pedido realizado ainda.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="mt-4">
    {{ $orders->links() }}
  </div>
</div>
@endsection

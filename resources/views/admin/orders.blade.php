@extends("layouts.layout")

@section("titulo1", "Gerenciar Pedidos")

@section("content")
<div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
  <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
    <h2 class="text-2xl font-black text-gray-800">Todos os Pedidos</h2>
    <div class="flex gap-3">
      <a href="{{ route('admin.refunds') }}" class="px-4 py-2 bg-rose-50 text-rose-600 hover:bg-rose-100 text-xs font-bold rounded-xl transition-colors">
        Análise de Reembolsos
      </a>
    </div>
  </div>
  
  <div class="overflow-x-auto">
    <table class="w-full text-left border-collapse">
      <thead>
        <tr class="bg-gray-50 border-b border-gray-100 text-xs font-bold text-gray-400 uppercase">
          <th class="p-4">ID Pedido</th>
          <th class="p-4">Usuário</th>
          <th class="p-4">Serviço</th>
          <th class="p-4">Custo</th>
          <th class="p-4">Status</th>
          <th class="p-4">Reembolso</th>
          <th class="p-4">Data</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-50 text-sm">
        @foreach($orders as $order)
          <tr>
            <td class="p-4 font-bold text-blue-600">#{{ $order->api_order_id }}</td>
            <td class="p-4 font-semibold text-gray-800">{{ $order->user->name ?? 'Usuário Deletado' }}</td>
            <td class="p-4 text-gray-600 max-w-xs truncate">{{ $order->service_name }}</td>
            <td class="p-4 font-bold text-green-600">R$ {{ number_format($order->charge, 2, ',', '.') }}</td>
            <td class="p-4">
              <span class="px-3 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-700">
                {{ $order->status }}
              </span>
            </td>
            <td class="p-4">
              @if($order->isRefunded())
                <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-rose-100 text-rose-700" title="{{ $order->refund_reason }}">
                  Reembolsado
                </span>
              @else
                <span class="text-xs text-gray-400">-</span>
              @endif
            </td>
            <td class="p-4 text-xs text-gray-400">{{ $order->created_at->format('d/m/Y H:i') }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  <div class="mt-6">{{ $orders->links() }}</div>
</div>
@endsection

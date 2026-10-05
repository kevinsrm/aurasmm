@extends("layouts.layout")

@section("titulo1", "Admin Dashboard")

@section("content")
<div class="space-y-8">
  <!-- Top Bar -->
  <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
    <div>
      <h1 class="text-2xl font-black text-gray-800">Painel Administrativo</h1>
      <p class="text-xs text-gray-500">Visão geral e controle completo da plataforma SMM</p>
    </div>
    <div class="flex-wrap flex gap-3">
      <a href="{{ route('admin.users') }}" class="flex-shrink-0 px-4 py-2 bg-blue-50 text-blue-600 hover:bg-blue-100 text-xs font-bold rounded-xl transition-colors">Gerenciar Usuários</a>
      <a href="{{ route('admin.orders') }}" class="flex-shrink-0 px-4 py-2 bg-indigo-50 text-indigo-600 hover:bg-indigo-100 text-xs font-bold rounded-xl transition-colors">Gerenciar Pedidos</a>
      <a href="{{ route('admin.refunds') }}" class="flex-shrink-0 px-4 py-2 bg-rose-50 text-rose-600 hover:bg-rose-100 text-xs font-bold rounded-xl transition-colors">Reembolsos</a>
      <a href="{{ route('admin.coupons') }}" class="flex-shrink-0 px-4 py-2 bg-purple-50 text-purple-600 hover:bg-purple-100 text-xs font-bold rounded-xl transition-colors">Cupons</a>
      <a href="{{ route('admin.settings') }}" class="flex-shrink-0 px-4 py-2 bg-gray-100 text-gray-700 hover:bg-gray-200 text-xs font-bold rounded-xl transition-colors">Configurações</a>
    </div>
  </div>

  @if(session('success'))
    <div class="p-4 bg-green-50 border border-green-200 text-green-700 rounded-2xl text-sm font-medium">
      {{ session('success') }}
    </div>
  @endif

  <!-- Stats Grid -->
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6">
    <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 space-y-2">
      <div class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Usuários</div>
      <div class="text-3xl font-black text-gray-800">{{ number_format($totalUsers, 0, ',', '.') }}</div>
    </div>

    <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 space-y-2">
      <div class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Pedidos</div>
      <div class="text-3xl font-black text-blue-600">{{ number_format($totalOrders, 0, ',', '.') }}</div>
    </div>

    <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 space-y-2">
      <div class="text-xs font-bold text-gray-400 uppercase tracking-wider">Faturamento (Pedidos)</div>
      <div class="text-3xl font-black text-green-600">R$ {{ number_format($totalRevenue, 2, ',', '.') }}</div>
    </div>

    <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 space-y-2">
      <div class="text-xs font-bold text-gray-400 uppercase tracking-wider">Depósitos Aprovados</div>
      <div class="text-3xl font-black text-indigo-600">R$ {{ number_format($totalDeposits, 2, ',', '.') }}</div>
    </div>

    <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 space-y-2">
      <div class="text-xs font-bold text-gray-400 uppercase tracking-wider">Saldo API Fornecedor</div>
      <div class="text-3xl font-black text-rose-600">{{ $apiCurrency }} {{ $apiBalance }}</div>
    </div>
  </div>

  <!-- Recent Orders Table -->
  <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
    <h3 class="text-xl font-bold text-gray-800 mb-6">Pedidos Recentes na Plataforma</h3>
    <div class="overflow-x-auto">
      <table class="w-full text-left border-collapse">
        <thead>
          <tr class="bg-gray-50 border-b border-gray-100 text-xs font-bold text-gray-400 uppercase">
            <th class="p-4">ID API</th>
            <th class="p-4">Usuário</th>
            <th class="p-4">Serviço</th>
            <th class="p-4">Quantidade</th>
            <th class="p-4">Custo</th>
            <th class="p-4">Status</th>
            <th class="p-4">Data</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-50 text-sm">
          @forelse($recentOrders as $order)
            <tr>
              <td class="p-4 font-bold text-blue-600">#{{ $order->api_order_id }}</td>
              <td class="p-4 font-semibold text-gray-700">{{ $order->user->name ?? 'N/A' }}</td>
              <td class="p-4 max-w-xs truncate" title="{{ $order->service_name }}">
                <span class="text-xs text-gray-400">[{{ $order->service_id }}]</span> {{ $order->service_name }}
              </td>
              <td class="p-4">{{ number_format($order->quantity, 0, ',', '.') }}</td>
              <td class="p-4 font-bold text-green-600">R$ {{ number_format($order->charge, 2, ',', '.') }}</td>
              <td class="p-4">
                <span class="px-3 py-1 rounded-full text-xs font-bold 
                  @if(in_array(strtolower($order->status), ['completed', 'concluído'])) bg-green-100 text-green-700
                  @elseif(in_array(strtolower($order->status), ['pending', 'pendente', 'processing', 'in progress'])) bg-yellow-100 text-yellow-700
                  @else bg-gray-100 text-gray-700 @endif">
                  {{ $order->status }}
                </span>
              </td>
              <td class="p-4 text-xs text-gray-400">{{ $order->created_at->format('d/m/Y H:i') }}</td>
            </tr>
          @empty
            <tr>
              <td colspan="7" class="p-8 text-center text-gray-400">Nenhum pedido recente.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection

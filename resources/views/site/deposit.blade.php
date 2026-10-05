@extends("layouts.layout")

@section("titulo1", "Adicionar Saldo e Resgatar Cupons")

@section("content")
<div class="max-w-4xl mx-auto space-y-8">
  
  <!-- Notificações de Cupons (se houver) -->
  @php
    $notifications = Auth::user()->unreadNotifications;
  @endphp
  @if($notifications->count() > 0)
    <div class="bg-gradient-to-r from-purple-600 to-indigo-600 rounded-3xl p-6 text-white shadow-lg space-y-3">
      <div class="flex items-center justify-between">
        <h3 class="font-black text-lg flex items-center gap-2">
          🎁 Notificações de Cupons Recentes
        </h3>
        <span class="px-2.5 py-1 bg-white/20 rounded-full text-xs font-bold">{{ $notifications->count() }} nova(s)</span>
      </div>
      <div class="space-y-2">
        @foreach($notifications as $notification)
          <div class="p-3 bg-white/10 rounded-2xl backdrop-blur-sm text-sm flex justify-between items-center">
            <div>
              <strong>{{ $notification->data['title'] ?? 'Notificação' }}</strong>: 
              {{ $notification->data['message'] ?? '' }}
            </div>
          </div>
        @endforeach
      </div>
      @php Auth::user()->unreadNotifications->markAsRead(); @endphp
    </div>
  @endif

  <!-- Resgatar Cupom Card -->
  <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
    <div class="flex items-center gap-4 mb-6">
      <div class="w-12 h-12 bg-purple-100 text-purple-600 rounded-2xl flex items-center justify-center font-black">
        🏷️
      </div>
      <div>
        <h2 class="text-xl font-black text-gray-800">Resgatar Cupom de Desconto</h2>
        <p class="text-xs text-gray-500">Digite seu código de cupom para adicionar saldo instantaneamente</p>
      </div>
    </div>

    @if(session('coupon_success'))
      <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-2xl text-sm font-medium">
        {{ session('coupon_success') }}
      </div>
    @endif

    <form action="{{ route('coupon.redeem') }}" method="POST" class="flex gap-3">
      @csrf
      <input type="text" name="code" required placeholder="DIGITE SEU CUPOM AQUI" class="flex-1 px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl font-bold uppercase text-gray-800 focus:bg-white focus:ring-2 focus:ring-purple-500 outline-none text-sm">
      <button type="submit" class="px-8 py-3.5 bg-purple-600 hover:bg-purple-700 text-white font-bold rounded-2xl shadow-lg shadow-purple-500/30 transition-all text-sm">
        Resgatar
      </button>
    </form>
  </div>

  <!-- Adicionar Saldo Card -->
  <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
    <div class="flex items-center gap-4 mb-6">
      <div class="w-12 h-12 bg-green-100 text-green-600 rounded-2xl flex items-center justify-center font-black">
        PIX
      </div>
      <div>
        <h2 class="text-xl font-black text-gray-800">Adicionar Saldo via PIX</h2>
        <p class="text-xs text-gray-500">Pagamento instantâneo via Mercado Pago com liberação automática</p>
      </div>
    </div>

    @if(session('success'))
      <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-2xl text-sm font-medium">
        {{ session('success') }}
      </div>
    @endif

    @if($errors->any())
      <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-2xl text-sm">
        <ul class="list-disc pl-4 space-y-1">
          @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form action="{{ route('deposit.store') }}" method="POST" class="space-y-6">
      @csrf
      <div>
        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Valor do Depósito (R$)</label>
        <div class="relative max-w-sm">
          <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-500 font-bold text-sm">R$</span>
          <input type="number" step="0.01" min="1" max="10000" name="amount" required value="{{ old('amount', '50.00') }}" 
                 class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl font-bold text-gray-800 focus:bg-white focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition-all">
        </div>
        <p class="text-xs text-gray-400 mt-2">Valor mínimo: R$ 1,00</p>
      </div>

      <!-- Quick Amount Buttons -->
      <div class="flex gap-2 flex-wrap">
        <button type="button" onclick="document.querySelector('input[name=amount]').value='20.00'" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold rounded-xl transition-colors">R$ 20,00</button>
        <button type="button" onclick="document.querySelector('input[name=amount]').value='50.00'" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold rounded-xl transition-colors">R$ 50,00</button>
        <button type="button" onclick="document.querySelector('input[name=amount]').value='100.00'" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold rounded-xl transition-colors">R$ 100,00</button>
        <button type="button" onclick="document.querySelector('input[name=amount]').value='250.00'" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold rounded-xl transition-colors">R$ 250,00</button>
        <button type="button" onclick="document.querySelector('input[name=amount]').value='500.00'" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold rounded-xl transition-colors">R$ 500,00</button>
      </div>

      <div>
        <button type="submit" class="px-8 py-3.5 bg-green-600 hover:bg-green-700 text-white font-bold rounded-2xl shadow-lg shadow-green-500/30 transition-all transform active:scale-95 text-sm flex items-center gap-2">
          Gerar QR Code PIX
        </button>
      </div>
    </form>
  </div>

  <!-- Histórico de Depósitos -->
  <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
    <h3 class="text-xl font-bold text-gray-800 mb-6">Histórico de Depósitos</h3>
    <div class="overflow-x-auto">
      <table class="w-full text-left border-collapse">
        <thead>
          <tr class="bg-gray-50 border-b border-gray-100 text-xs font-bold text-gray-400 uppercase">
            <th class="p-4">ID</th>
            <th class="p-4">Valor</th>
            <th class="p-4">Status</th>
            <th class="p-4">Data</th>
            <th class="p-4 text-center">Ações</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-50 text-sm">
          @forelse($deposits as $deposit)
            <tr>
              <td class="p-4 font-bold text-gray-600">#{{ $deposit->id }}</td>
              <td class="p-4 font-black text-green-600">R$ {{ number_format($deposit->amount, 2, ',', '.') }}</td>
              <td class="p-4">
                <span class="px-3 py-1 rounded-full text-xs font-bold 
                  @if($deposit->status === 'approved') bg-green-100 text-green-700 
                  @elseif($deposit->status === 'pending') bg-yellow-100 text-yellow-700 
                  @else bg-red-100 text-red-700 @endif">
                  {{ ucfirst($deposit->status) }}
                </span>
              </td>
              <td class="p-4 text-xs text-gray-400">{{ $deposit->created_at->format('d/m/Y H:i') }}</td>
              <td class="p-4 text-center">
                @if($deposit->status === 'pending')
                  <a href="{{ route('deposit.showPayment', $deposit->id) }}" class="px-4 py-1.5 bg-blue-50 text-blue-600 hover:bg-blue-100 text-xs font-bold rounded-xl transition-colors">
                    Pagar / Ver PIX
                  </a>
                @else
                  <span class="text-xs text-gray-400">-</span>
                @endif
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="5" class="p-8 text-center text-gray-400">Nenhum depósito realizado.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection

@extends("layouts.layout")

@section("titulo1", "Análise de Reembolsos")

@section("content")
@php
  $whatsapp = \App\Models\Setting::get('support_whatsapp', '');
@endphp
<div class="space-y-6">
  <!-- Top Bar -->
  <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
    <div>
      <h1 class="text-2xl font-black text-gray-800">Análise de Reembolsos</h1>
      <p class="text-xs text-gray-500">Analise pedidos do suporte (WhatsApp) e devolva o valor ao saldo do cliente</p>
    </div>
    <div class="flex gap-3">
      <a href="{{ route('admin.orders') }}" class="px-4 py-2 bg-indigo-50 text-indigo-600 hover:bg-indigo-100 text-xs font-bold rounded-xl transition-colors">Ver Todos os Pedidos</a>
      <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 bg-gray-100 text-gray-700 hover:bg-gray-200 text-xs font-bold rounded-xl transition-colors">Dashboard</a>
    </div>
  </div>

  @if(session('success'))
    <div class="p-4 bg-green-50 border border-green-200 text-green-700 rounded-2xl text-sm font-medium">{{ session('success') }}</div>
  @endif
  @if(session('error'))
    <div class="p-4 bg-red-50 border border-red-200 text-red-700 rounded-2xl text-sm font-medium">{{ session('error') }}</div>
  @endif

  <!-- Stats -->
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6">
    <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 space-y-2">
      <div class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Pedidos</div>
      <div class="text-3xl font-black text-gray-800">{{ number_format($orders->total(), 0, ',', '.') }}</div>
    </div>
    <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 space-y-2">
      <div class="text-xs font-bold text-gray-400 uppercase tracking-wider">Reembolsos Processados</div>
      <div class="text-3xl font-black text-blue-600">{{ number_format($stats['refunded'], 0, ',', '.') }}</div>
    </div>
    <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 space-y-2">
      <div class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Reembolsado</div>
      <div class="text-3xl font-black text-rose-600">R$ {{ number_format($stats['refunded_total'], 2, ',', '.') }}</div>
    </div>
    <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 space-y-2 self-stretch">
      <div class="text-xs font-bold text-gray-400 uppercase tracking-wider">Suporte via WhatsApp</div>
      @if($whatsapp)
        <a href="https://wa.me/{{ $whatsapp }}" target="_blank" class="text-lg font-black text-green-600 hover:text-green-700 break-all">
          +{{ $whatsapp }}
        </a>
        <p class="text-[11px] text-gray-400">Cliente solicita reembolso aqui. Responda na conversa com o botão "WhatsApp" de cada pedido.</p>
      @else
        <div class="text-lg font-black text-gray-300">Não configurado</div>
        <a href="{{ route('admin.settings') }}" class="text-[11px] text-blue-500 hover:underline font-bold">Configurar número →</a>
      @endif
    </div>
  </div>

  <!-- Filters + Table -->
  <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8 space-y-6">
    <form method="GET" action="{{ route('admin.refunds') }}" class="flex flex-col sm:flex-row gap-3">
      <div class="relative flex-1">
        <input type="text" name="search" value="{{ request('search', '') }}" placeholder="Buscar por ID do pedido, serviço, link, nome ou e-mail do cliente..."
          class="w-full pl-4 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:bg-white focus:ring-2 focus:ring-blue-500 outline-none">
      </div>
      <select name="refund_status" class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm font-bold text-gray-700 focus:ring-2 focus:ring-blue-500 outline-none">
        <option value="">Todos os pedidos</option>
        <option value="none" @selected(request('refund_status') === 'none')>Aguardando análise</option>
        <option value="approved" @selected(request('refund_status') === 'approved')>Reembolsados</option>
      </select>
      <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-2xl transition-colors">Filtrar</button>
      @if(request()->hasAny(['search', 'refund_status']))
        <a href="{{ route('admin.refunds') }}" class="px-4 py-3 bg-gray-100 hover:bg-gray-200 text-gray-600 text-sm font-bold rounded-2xl transition-colors">Limpar</a>
      @endif
    </form>

    <div class="overflow-x-auto">
      <table class="w-full text-left border-collapse">
        <thead>
          <tr class="bg-gray-50 border-b border-gray-100 text-xs font-bold text-gray-400 uppercase">
            <th class="p-4">Pedido</th>
            <th class="p-4">Cliente</th>
            <th class="p-4">Serviço</th>
            <th class="p-4">Valor</th>
            <th class="p-4">Status da API</th>
            <th class="p-4">Reembolso</th>
            <th class="p-4 text-right">Ações</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-50 text-sm">
          @forelse($orders as $order)
            @php
              $user = $order->user;
              $isRefunded = $order->isRefunded();
              $canRefund = !$isRefunded && $user !== null;
            @endphp
            <tr class="{{ $isRefunded ? 'bg-rose-50/40' : '' }}">
              <td class="p-4">
                <div class="font-bold text-blue-600">#{{ $order->api_order_id }}</div>
                <div class="text-xs text-gray-400">{{ $order->created_at->format('d/m/Y H:i') }}</div>
              </td>
              <td class="p-4">
                <div class="font-semibold text-gray-800">{{ $user->name ?? 'Usuário Deletado' }}</div>
                @if($user)
                  <div class="text-xs text-gray-400 max-w-[180px] truncate">{{ $user->email }}</div>
                  <div class="text-xs font-bold text-green-600">Saldo: R$ {{ number_format($user->balance, 2, ',', '.') }}</div>
                @endif
              </td>
              <td class="p-4 text-gray-600 max-w-[220px]">
                <div class="truncate" title="{{ $order->service_name }}">{{ $order->service_name }}</div>
                <div class="text-xs text-gray-400 truncate max-w-[220px]" title="{{ $order->link }}">{{ $order->link }}</div>
              </td>
              <td class="p-4 font-bold text-green-600 whitespace-nowrap">R$ {{ number_format($order->charge, 2, ',', '.') }}</td>
              <td class="p-4">
                <span class="px-3 py-1 rounded-full text-xs font-bold
                  @if(in_array(strtolower($order->status), ['completed', 'concluído'])) bg-green-100 text-green-700
                  @elseif(in_array(strtolower($order->status), ['pending', 'pendente', 'processing', 'in progress'])) bg-yellow-100 text-yellow-700
                  @else bg-gray-100 text-gray-700 @endif">
                  {{ $order->status }}
                </span>
              </td>
              <td class="p-4">
                @if($isRefunded)
                  <div>
                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-rose-100 text-rose-700">✓ Reembolsado</span>
                    @if($order->refunded_by)
                      <div class="text-[11px] text-gray-400 mt-1">por {{ $order->refundedBy->name ?? 'admin' }} · {{ $order->refunded_at->format('d/m/Y H:i') }}</div>
                    @endif
                    @if($order->refund_reason)
                      <div class="text-[11px] text-gray-500 mt-1 max-w-[180px] truncate" title="{{ $order->refund_reason }}">Motivo: {{ $order->refund_reason }}</div>
                    @endif
                  </div>
                @else
                  <span class="px-3 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-500">Aguardando</span>
                @endif
              </td>
              <td class="p-4">
                <div class="flex justify-end gap-2">
                  @if($canRefund)
                    <button type="button"
                      data-action="{{ route('admin.orders.refund', $order->id) }}"
                      data-order-id="{{ $order->api_order_id }}"
                      data-customer="{{ $user->name }}"
                      data-email="{{ $user->email }}"
                      data-service="{{ $order->service_name }}"
                      data-charge="{{ (float) $order->charge }}"
                      data-status="{{ $order->status }}"
                      onclick="openRefundModal(this)"
                      class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-xl transition-colors">
                      Reembolsar
                    </button>
                  @endif
                  @if($isRefunded)
                    <button type="button"
                      data-action="{{ route('admin.orders.unrefund', $order->id) }}"
                      data-order-id="{{ $order->api_order_id }}"
                      data-customer="{{ $user->name ?? 'usuário' }}"
                      data-charge="{{ (float) $order->charge }}"
                      onclick="openUnrefundModal(this)"
                      class="px-4 py-2 bg-gray-700 hover:bg-gray-800 text-white text-xs font-bold rounded-xl transition-colors">
                      Estornar
                    </button>
                  @endif
                  @if($user)
                    <button type="button"
                      data-order-id="{{ $order->api_order_id }}"
                      data-customer="{{ $user->name }}"
                      data-email="{{ $user->email }}"
                      data-service="{{ $order->service_name }}"
                      data-charge="{{ (float) $order->charge }}"
                      data-status="{{ $order->status }}"
                      data-refunded="{{ $isRefunded ? '1' : '0' }}"
                      onclick="openWaModal(this)"
                      class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-xs font-bold rounded-xl transition-colors"
                      title="Gerar mensagem para responder o cliente no WhatsApp">
                      WhatsApp
                    </button>
                  @endif
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="7" class="p-10 text-center text-gray-400">Nenhum pedido encontrado com os filtros atuais.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    @if($orders->hasPages())
      <div class="mt-6">{{ $orders->links() }}</div>
    @endif
  </div>
</div>

<!-- ================= MODAL: Reembolsar ================= -->
<div id="refundModal" class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4">
  <div class="absolute inset-0 bg-gray-900/50 backdrop-blur-sm" onclick="closeModal('refundModal')"></div>
  <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-lg p-8 space-y-6 max-h-[90vh] overflow-y-auto">
    <div class="flex items-center justify-between">
      <h3 class="text-lg font-black text-gray-800">Confirmar Reembolso</h3>
      <button type="button" onclick="closeModal('refundModal')" class="p-2 text-gray-400 hover:text-gray-600 rounded-xl hover:bg-gray-100">✕</button>
    </div>

    <div id="refundSummary" class="p-4 bg-gray-50 border border-gray-100 rounded-2xl text-sm space-y-1">
      <!-- preenchido via JS -->
    </div>

    <form id="refundForm" method="POST" action="">
      @csrf
      <input type="hidden" name="order_id_holder" value="">
      <div>
        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Motivo do Reembolso (obrigatório)</label>
        <textarea name="reason" rows="3" required maxlength="500"
          class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:bg-white focus:ring-2 focus:ring-rose-500 outline-none"
          placeholder="Ex.: Cliente informou via WhatsApp que o serviço não foi entregue corretamente..."></textarea>
        <p class="text-[11px] text-gray-400 mt-1">O motivo fica registrado no histórico do pedido. O <strong>valor é devolvido ao saldo do cliente</strong>.</p>
      </div>
      <div class="flex gap-3 pt-2">
        <button type="button" onclick="closeModal('refundModal')" class="flex-1 px-4 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-bold rounded-2xl transition-colors">Cancelar</button>
        <button type="submit" class="flex-1 px-4 py-3 bg-rose-600 hover:bg-rose-700 text-white text-sm font-bold rounded-2xl transition-colors">Confirmar Reembolso</button>
      </div>
    </form>
  </div>
</div>

<!-- ================= MODAL: Estornar ================= -->
<div id="unrefundModal" class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4">
  <div class="absolute inset-0 bg-gray-900/50 backdrop-blur-sm" onclick="closeModal('unrefundModal')"></div>
  <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-lg p-8 space-y-6 max-h-[90vh] overflow-y-auto">
    <div class="flex items-center justify-between">
      <h3 class="text-lg font-black text-gray-800">Estornar Reembolso</h3>
      <button type="button" onclick="closeModal('unrefundModal')" class="p-2 text-gray-400 hover:text-gray-600 rounded-xl hover:bg-gray-100">✕</button>
    </div>

    <div id="unrefundSummary" class="p-4 bg-gray-50 border border-gray-100 rounded-2xl text-sm space-y-1"></div>

    <div class="p-4 bg-amber-50 border border-amber-200 rounded-2xl text-xs text-amber-700 font-medium">
      ⚠️ Atenção: o valor será <strong>retirado do saldo</strong> do cliente e o pedido voltará a "Aguardando análise". Esta ação é registrada no histórico.
    </div>

    <form id="unrefundForm" method="POST" action="">
      @csrf
      <div class="flex gap-3">
        <button type="button" onclick="closeModal('unrefundModal')" class="flex-1 px-4 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-bold rounded-2xl transition-colors">Cancelar</button>
        <button type="submit" class="flex-1 px-4 py-3 bg-gray-700 hover:bg-gray-800 text-white text-sm font-bold rounded-2xl transition-colors">Confirmar Estorno</button>
      </div>
    </form>
  </div>
</div>

<!-- ================= MODAL: WhatsApp ================= -->
<div id="waModal" class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4">
  <div class="absolute inset-0 bg-gray-900/50 backdrop-blur-sm" onclick="closeModal('waModal')"></div>
  <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-lg p-8 space-y-6 max-h-[90vh] overflow-y-auto">
    <div class="flex items-center justify-between">
      <h3 class="text-lg font-black text-gray-800">Mensagem de Resposta (WhatsApp)</h3>
      <button type="button" onclick="closeModal('waModal')" class="p-2 text-gray-400 hover:text-gray-600 rounded-xl hover:bg-gray-100">✕</button>
    </div>

    <p class="text-xs text-gray-500">
      Edite a mensagem se precisar e <strong>cole na conversa do cliente no seu WhatsApp de suporte</strong>.
    </p>

    <textarea id="waMessage" rows="8"
      class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:bg-white focus:ring-2 focus:ring-green-500 outline-none resize-y"
      placeholder="Mensagem pré-preenchida..."></textarea>

    <div class="flex gap-3">
      <button type="button" onclick="copyWaMessage()" class="flex-1 px-4 py-3 bg-green-600 hover:bg-green-700 text-white text-sm font-bold rounded-2xl transition-colors">📋 Copiar Mensagem</button>
    </div>
    <p id="waCopyHint" class="text-[11px] text-green-600 font-bold text-center hidden">✓ Mensagem copiada! Agora abra a conversa do cliente no WhatsApp e cole.</p>
  </div>
</div>

<script>
function closeModal(id) {
  document.getElementById(id).classList.add('hidden');
}

function openRefundModal(btn) {
  var data = btn.dataset;
  var fmt = function (v) { return 'R$ ' + Number(v).toFixed(2).replace('.', ','); };
  document.getElementById('refundSummary').innerHTML =
    '<div><strong>Pedido:</strong> #' + data.orderId + ' por <strong>' + data.customer + '</strong> (' + data.email + ')</div>' +
    '<div><strong>Serviço:</strong> ' + data.service + '</div>' +
    '<div class="flex justify-between items-center"><span><strong>Valor a devolver:</strong> ' + fmt(data.charge) + '</span><span><strong>API:</strong> ' + data.status + '</span></div>';
  var form = document.getElementById('refundForm');
  form.action = data.action;
  form.reason.value = '';
  document.getElementById('refundModal').classList.remove('hidden');
  setTimeout(function () { form.reason.focus(); }, 50);
}

function openUnrefundModal(btn) {
  var data = btn.dataset;
  var fmt = function (v) { return 'R$ ' + Number(v).toFixed(2).replace('.', ','); };
  document.getElementById('unrefundSummary').innerHTML =
    '<div><strong>Pedido:</strong> #' + data.orderId + ' em nome de <strong>' + data.customer + '</strong></div>' +
    '<div><strong>Valor a retirar do saldo:</strong> ' + fmt(data.charge) + '</div>';
  document.getElementById('unrefundForm').action = data.action;
  document.getElementById('unrefundModal').classList.remove('hidden');
}

function openWaModal(btn) {
  var data = btn.dataset;
  var isRefunded = data.refunded === '1';
  var fmt = function (v) { return 'R$ ' + Number(v).toFixed(2).replace('.', ','); };
  var msg;
  if (isRefunded) {
    msg = 'Olá ' + data.customer + '! 👋\n\n' +
      'Confirmo aqui que o reembolso do pedido #' + data.orderId + ' (' + data.service + '), no valor de ' + fmt(data.charge) + ', já foi processado e o valor foi devolvido ao seu saldo na conta AuraSMM.\n\n' +
      'Qualquer dúvida, é só me chamar. \n\n— Equipe AuraSMM';
  } else {
    msg = 'Olá ' + data.customer + '! 👋\n\n' +
      'Aqui é da equipe AuraSMM. Recebemos sua solicitação referente ao pedido #' + data.orderId + ' (' + data.service + '), no valor de ' + fmt(data.charge) + '.\n\n' +
      'Já estamos analisando o caso e retornaremos em breve com uma resposta.\n\n— Equipe AuraSMM';
  }
  document.getElementById('waMessage').value = msg;
  document.getElementById('waCopyHint').classList.add('hidden');
  document.getElementById('waModal').classList.remove('hidden');
}

function copyWaMessage() {
  var el = document.getElementById('waMessage');
  el.select();
  if (navigator.clipboard && navigator.clipboard.writeText) {
    navigator.clipboard.writeText(el.value).then(function () {
      document.getElementById('waCopyHint').classList.remove('hidden');
    });
  } else {
    document.execCommand('copy');
    document.getElementById('waCopyHint').classList.remove('hidden');
  }
}
</script>
@endsection
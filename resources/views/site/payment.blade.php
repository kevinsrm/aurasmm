@extends("layouts.layout")

@section("titulo1", "Aguardando Pagamento PIX")

@section("content")
<div class="max-w-md mx-auto my-8 bg-white rounded-3xl shadow-xl border border-gray-100 p-8 text-center space-y-6">
  <div class="inline-flex items-center justify-center w-16 h-16 bg-green-100 text-green-600 rounded-2xl">
    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
    </svg>
  </div>

  <div>
    <h2 class="text-2xl font-black text-gray-800">Pagamento PIX</h2>
    <p class="text-xs text-gray-500 mt-1">Escaneie o QR Code abaixo ou copie a chave PIX</p>
  </div>

  <div class="p-4 bg-green-50 border border-green-200 rounded-2xl text-center space-y-1">
    <div class="text-xs text-green-700 font-bold uppercase">Valor a pagar</div>
    <div class="text-3xl font-black text-green-600">R$ {{ number_format($deposit->amount, 2, ',', '.') }}</div>
  </div>

  <!-- Countdown Timer -->
  @if($deposit->status === 'pending')
    @php
      $expiresAt = $deposit->created_at->addMinutes(10);
      $remainingSeconds = max(0, (int) now()->diffInSeconds($expiresAt, false));
    @endphp
    @if($remainingSeconds > 0)
      <div id="countdownBox" data-expires="{{ $expiresAt->timestamp }}" class="p-4 bg-orange-50 border border-orange-200 rounded-2xl text-center space-y-1">
        <div class="text-xs text-orange-700 font-bold uppercase">⏱ Tempo restante para pagar</div>
        <div id="countdownTimer" class="text-3xl font-black text-orange-600 tabular-nums">
          {{ str_pad(floor($remainingSeconds / 60), 2, '0', STR_PAD_LEFT) }}:{{ str_pad($remainingSeconds % 60, 2, '0', STR_PAD_LEFT) }}
        </div>
        <div data-hint class="text-[10px] text-orange-500">O PIX será cancelado ao zerar o tempo</div>
      </div>
    @endif
  @endif

  <!-- QR Code Image -->
  @if($deposit->qr_code_base64)
    <div id="qrWrap" class="flex justify-center p-4 bg-gray-50 rounded-2xl border border-gray-100">
      <img src="data:image/jpeg;base64,{{ $deposit->qr_code_base64 }}" alt="QR Code PIX" class="w-64 h-64 rounded-xl">
    </div>
  @endif

  <!-- Pix Copy & Paste -->
  @if($deposit->qr_code)
    <div id="pixCopyWrap" class="space-y-2">
      <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider">Copia e Cola PIX</label>
      <div class="flex gap-2">
        <input id="pixKey" type="text" readonly value="{{ $deposit->qr_code }}" class="flex-1 px-3 py-2 bg-gray-50 border border-gray-200 text-xs rounded-xl font-mono text-gray-600 truncate">
        <button type="button" onclick="copyPixKey()" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl transition-colors">
          Copiar
        </button>
      </div>
    </div>
  @endif

  <!-- Payment Status Check -->
  @php
    $status = $deposit->status;
    $isPending = $status === 'pending';
    $wasExpired = !$isPending && $deposit->created_at->addMinutes(10)->isPast();
  @endphp
  @if($status === 'approved')
    <div id="statusBadge" class="p-3 bg-green-50 text-green-700 text-xs font-bold rounded-xl border border-green-200 flex items-center justify-center gap-2">
      <span>✓</span>
      <span id="statusText">Pagamento Confirmado! Redirecionando...</span>
    </div>
  @elseif($isPending)
    <div id="statusBadge" class="p-3 bg-yellow-50 text-yellow-700 text-xs font-bold rounded-xl border border-yellow-200 flex items-center justify-center gap-2">
      <span class="w-2 h-2 rounded-full bg-yellow-500 animate-ping"></span>
      <span id="statusText">Aguardando confirmação do pagamento...</span>
    </div>
  @else
    <div id="statusBadge" class="p-3 bg-red-50 text-red-700 text-xs font-bold rounded-xl border border-red-200 flex items-center justify-center gap-2">
      <span>✕</span>
      <span id="statusText">
        @if($wasExpired)
          ⏱ Pagamento Expirado! Tempo esgotado para pagar o PIX.
        @else
          ✕ Pagamento Cancelado.
        @endif
      </span>
    </div>
  @endif

  @if(!$isPending && $status !== 'approved')
    <a href="{{ route('deposit.show') }}" class="block px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl transition-colors">
      Gerar Novo PIX
    </a>
  @endif

  <a href="{{ route('deposit.show') }}" class="block text-xs text-gray-400 hover:text-gray-600 font-bold">
    Voltar para Depósitos
  </a>
</div>

<script>
function copyPixKey() {
  const pixKeyInput = document.getElementById('pixKey');
  pixKeyInput.select();
  document.execCommand('copy');
  alert('Chave PIX copiada para a área de transferência!');
}

var depositId = @json($deposit->id);
var statusUrl = "{{ route('deposit.checkStatus', $deposit->id) }}";
var servicesUrl = "{{ route('services') }}";
var expiredShown = false;
var finished = false;

function pad(n) { return String(n).padStart(2, '0'); }

function setStatusBadge(cls, text) {
  var badge = document.getElementById('statusBadge');
  var txt = document.getElementById('statusText');
  if (!badge || !txt) return;
  badge.className = cls;
  txt.innerText = text;
}

function hideQrElements() {
  var qrWrap = document.getElementById('qrWrap');
  var pixCopyWrap = document.getElementById('pixCopyWrap');
  var box = document.getElementById('countdownBox');
  if (qrWrap) qrWrap.style.display = 'none';
  if (pixCopyWrap) pixCopyWrap.style.display = 'none';
  if (box) box.style.display = 'none';
}

function showExpired() {
  if (expiredShown || finished) return;
  expiredShown = true;

  hideQrElements();

  setStatusBadge(
    "p-3 bg-red-50 text-red-700 text-xs font-bold rounded-xl border border-red-200 flex items-center justify-center gap-2",
    "⏱ Pagamento Expirado! O tempo para pagar o PIX acabou."
  );

  // Confirma o estado real no servidor (caso tenha pago no último segundo)
  fetch(statusUrl)
    .then(function (r) { return r.json(); })
    .then(function (data) {
      if (data.status === 'approved') {
        finished = true;
        setStatusBadge(
          "p-3 bg-green-50 text-green-700 text-xs font-bold rounded-xl border border-green-200 flex items-center justify-center gap-2",
          "✓ Pagamento Confirmado! Redirecionando..."
        );
        setTimeout(function () { window.location.href = servicesUrl; }, 2000);
      }
    });
}

function showCancelledOnServer() {
  hideQrElements();
  setStatusBadge(
    "p-3 bg-red-50 text-red-700 text-xs font-bold rounded-xl border border-red-200 flex items-center justify-center gap-2",
    expiredShown
      ? "⏱ Pagamento Expirado! O tempo para pagar o PIX acabou."
      : "✕ Pagamento Cancelado. Gere um novo PIX para tentar novamente."
  );
}

// Contador regressivo: confere a cada segundo
var countdownBox = document.getElementById('countdownBox');
if (countdownBox) {
  var expiresAt = parseInt(countdownBox.getAttribute('data-expires') || '0', 10);

  var timerEl = document.getElementById('countdownTimer');
  var hintEl = countdownBox.querySelector('[data-hint]');

  setInterval(function () {
    if (finished || expiredShown) return;

    var now = Math.floor(Date.now() / 1000);
    var remaining = expiresAt - now;

    if (remaining <= 0) {
      if (timerEl) timerEl.innerText = '00:00';
      showExpired();
      return;
    }

    if (timerEl) {
      var mins = Math.floor(remaining / 60);
      var secs = remaining % 60;
      timerEl.innerText = pad(mins) + ':' + pad(secs);

      // Últimos 60 segundos: alerta visual
      if (remaining <= 60) {
        countdownBox.className = "p-4 bg-red-50 border border-red-200 rounded-2xl text-center space-y-1";
        if (timerEl) timerEl.className = "text-3xl font-black text-red-600 tabular-nums";
        var label = countdownBox.querySelector('.uppercase');
        if (label) label.style.color = 'rgb(185 28 28)';
        if (hintEl) { hintEl.innerText = '⚠ Último minuto! Pague agora para não perder o PIX.'; hintEl.style.color = 'rgb(185 28 28)'; }
      }
    }
  }, 1000);
}

// Consulta o status do pagamento a cada 5 segundos
function pollStatus() {
  if (finished) return;

  fetch(statusUrl)
    .then(function (response) { return response.json(); })
    .then(function (data) {
      if (data.status === 'approved') {
        finished = true;
        setStatusBadge(
          "p-3 bg-green-50 text-green-700 text-xs font-bold rounded-xl border border-green-200 flex items-center justify-center gap-2",
          "✓ Pagamento Confirmado! Redirecionando..."
        );
        setTimeout(function () { window.location.href = servicesUrl; }, 2000);
      } else if (data.status === 'cancelled' || data.status === 'expired') {
        showCancelledOnServer();
      }
    })
    .catch(function () { /* falha de rede: tenta de novo no próximo ciclo */ });
}

setInterval(pollStatus, 5000);
setTimeout(pollStatus, 1500);
</script>
@endsection

@extends("layouts.layout")

@section("titulo1", "Painel SMM - Fazer Pedido")

@section("content")
<div class="max-w-xl mx-auto p-6 bg-white shadow-lg rounded-xl my-8">
  <div class="flex justify-between items-center mb-6 border-b pb-4">
    <h2 class="text-2xl font-bold text-gray-800">Fazer Pedido SMM</h2>
    <div class="flex gap-3 text-sm">
      <a href="{{ route('services') }}" class="text-blue-600 font-semibold hover:underline">Novo Pedido</a>
      <a href="{{ route('orders.index') }}" class="text-gray-600 hover:text-blue-600">Meus Pedidos</a>
    </div>
  </div>

  @if(session('success'))
    <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">
      {{ session('success') }}
    </div>
  @endif

  @if($errors->any())
    <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm">
      <ul class="list-disc pl-4 space-y-1">
        @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form action="{{ route('order.store') }}" method="POST" class="space-y-5">
    @csrf

    <!-- Category Filter -->
    <div>
      <label for="category-filter" class="block mb-1 text-sm font-semibold text-gray-700">Filtrar por Categoria</label>
      <select id="category-filter" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-300 text-gray-800 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500">
        <option value="">Todas as categorias</option>
        @foreach($categories as $category)
          <option value="{{ $category }}">{{ $category }}</option>
        @endforeach
      </select>
    </div>

    <!-- Service Select -->
    <div>
      <label for="services" class="block mb-1 text-sm font-semibold text-gray-700">Escolha um serviço</label>
      <select id="services" name="service" required class="w-full px-3 py-2.5 bg-gray-50 border border-gray-300 text-gray-800 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500">
        <option value="" selected disabled>Escolha um serviço</option>
        @foreach($itens as $item)
          <option value="{{ $item['service'] }}" 
                  data-category="{{ $item['category'] }}" 
                  data-min="{{ $item['min'] }}" 
                  data-max="{{ $item['max'] }}" 
                  data-rate="{{ $item['rate'] }}"
                  data-type="{{ $item['type'] ?? 'Default' }}"
                  data-dripfeed="{{ isset($item['dripfeed']) && $item['dripfeed'] ? 'true' : 'false' }}"
                  data-description="{{ $item['description'] ?? '' }}">
            {{ $item['service'] }} - {{ $item['name'] }} (R$ {{ $item['rate'] }} / 1000)
          </option>
        @endforeach
      </select>
    </div>

    <!-- Service Info Box -->
    <div id="service-info" class="hidden p-3 bg-blue-50 border border-blue-100 rounded-lg text-xs text-blue-800 space-y-1">
      <div>Tipo: <span id="info-type" class="font-bold">Default</span></div>
      <div>Mínimo: <span id="info-min" class="font-bold">0</span> | Máximo: <span id="info-max" class="font-bold">0</span></div>
      <div>Preço por 1000: R$ <span id="info-rate" class="font-bold">0.00</span></div>
    </div>

    <!-- Link Input -->
    <div>
      <label for="link" class="block mb-1 text-sm font-semibold text-gray-700">Link alvo (URL)</label>
      <input id="link" type="url" name="link" required value="{{ old('link') }}" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-300 text-gray-800 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="https://instagram.com/p/...">
    </div>

    <!-- Answer Number (Poll) -->
    <div id="divAnswerNumber" class="hidden space-y-1">
      <label for="answer_number" class="block mb-1 text-sm font-semibold text-gray-700">Número da Resposta (Enquete)</label>
      <input id="answer_number" type="text" name="answer_number" value="{{ old('answer_number') }}" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-300 text-gray-800 text-sm rounded-lg" placeholder="Ex: 1 ou 2">
    </div>

    <!-- Username (Comment Likes) -->
    <div id="divUsername" class="hidden space-y-1">
      <label for="username" class="block mb-1 text-sm font-semibold text-gray-700">Usuário do Comentário</label>
      <input id="username" type="text" name="username" value="{{ old('username') }}" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-300 text-gray-800 text-sm rounded-lg" placeholder="Nome de usuário do autor do comentário">
    </div>

    <!-- Custom Comments -->
    <div id="divComentarios" class="hidden space-y-2 border p-3 rounded-lg bg-gray-50">
      <label for="comment-input" class="block text-sm font-semibold text-gray-700">Adicionar Comentários</label>
      <div class="flex gap-2">
        <input id="comment-input" type="text" class="flex-1 px-3 py-2 bg-white border border-gray-300 text-sm rounded-lg" placeholder="Digite um comentário e clique em adicionar">
        <button type="button" id="add-comment-btn" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700">Adicionar</button>
      </div>
      
      <div id="previewComentarios" class="mt-2 space-y-1 max-h-48 overflow-y-auto border border-gray-200 rounded-lg p-2 bg-white">
        <p class="text-gray-400 text-xs text-center py-2">Nenhum comentário adicionado</p>
      </div>

      <textarea id="comentarios" name="comments" class="hidden">{{ old('comments') }}</textarea>
      
      <div class="text-xs font-medium">
        Status: <span id="statusComentarios" class="text-red-500">Aguardando comentários</span> 
        (<span id="saidaComentarios">0</span> / <span id="targetQuantity">0</span>)
      </div>
    </div>

    <!-- Quantity Input -->
    <div>
      <label for="quantity" class="block mb-1 text-sm font-semibold text-gray-700">Quantidade</label>
      <input id="quantity" type="number" name="quantity" required value="{{ old('quantity') }}" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-300 text-gray-800 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="Quantidade">
      <div class="text-xs text-gray-500 mt-1">Quantidade selecionada: <span id="saida">0</span></div>
    </div>

    <!-- Drip-feed Option -->
    <div id="divDripfeedContainer" class="hidden border p-3 rounded-lg bg-gray-50 space-y-3">
      <div class="flex items-center gap-2">
        <input type="checkbox" id="dripfeed-checkbox" name="dripfeed" value="1" class="w-4 h-4 text-blue-600 rounded">
        <label for="dripfeed-checkbox" class="text-sm font-semibold text-gray-700">Ativar Drip-feed (Entregas parceladas)</label>
      </div>
      <div id="dripfeed-fields" class="hidden grid grid-cols-2 gap-3">
        <div>
          <label for="runs" class="block text-xs font-medium text-gray-600 mb-1">Execuções (Runs)</label>
          <input type="number" id="runs" name="runs" value="1" min="1" class="w-full px-3 py-2 bg-white border border-gray-300 text-sm rounded-lg">
        </div>
        <div>
          <label for="interval" class="block text-xs font-medium text-gray-600 mb-1">Intervalo (Minutos)</label>
          <input type="number" id="interval" name="interval" value="30" min="1" class="w-full px-3 py-2 bg-white border border-gray-300 text-sm rounded-lg">
        </div>
      </div>
    </div>

    <!-- Total Price Display -->
    <div class="p-4 bg-gray-100 border border-gray-200 rounded-lg flex justify-between items-center">
      <div>
        <div class="text-xs text-gray-600 font-medium uppercase">Preço Total Estimado</div>
        <div class="text-xl font-bold text-green-600">R$ <span id="total-price">0.00</span></div>
      </div>
      <div id="total-quantity-display" class="text-xs text-gray-500 hidden">
        Total Qty: <span id="total-qty-val">0</span>
      </div>
    </div>
    
    <!-- DESCRIÇÃO DO SERVIÇO -->
    <div id="description" class="p-6 bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-xl shadow-lg mb-6">
    
    </div>

    <!-- Submit Button -->
    <button type="submit" id="submit-order" class="w-full py-3 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 transition-colors shadow">
      Finalizar Pedido
    </button>
  </form>
</div>

<script type="text/javascript" src="{{asset('js/script.js')}}"></script>
@endsection

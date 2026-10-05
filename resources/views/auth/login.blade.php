@extends("layouts.layout")

@section("titulo1", "Entrar - AuraSMM")

@section("content")
<div class="max-w-4xl mx-auto flex flex-col md:flex-row items-center justify-between gap-12 py-12 px-6">
  
  <!-- Lado Esquerdo: Benefícios -->
  <div class="flex-1 space-y-6">
    <div class="inline-block px-4 py-2 bg-blue-50 text-blue-700 text-xs font-bold rounded-full uppercase tracking-widest">
      Bem-vindo à AuraSMM
    </div>
    <h1 class="text-4xl md:text-5xl font-black text-gray-900 leading-tight">
      Serviços para redes sociais com <span class="text-blue-600">preço de revenda</span>
    </h1>
    <p class="text-lg text-gray-600">
      Feito para agências e revendedores que querem comprar em reais, pagar por Pix e acompanhar cada pedido em um só lugar.
    </p>
    
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-4">
      <div class="flex items-center gap-3 text-sm font-semibold text-gray-700">
        <span class="text-green-500">✅</span> Entrega em minutos
      </div>
      <div class="flex items-center gap-3 text-sm font-semibold text-gray-700">
        <span class="text-green-500">✅</span> Suporte em português
      </div>
      <div class="flex items-center gap-3 text-sm font-semibold text-gray-700">
        <span class="text-green-500">✅</span> Pagamento via PIX
      </div>
      <div class="flex items-center gap-3 text-sm font-semibold text-gray-700">
        <span class="text-green-500">✅</span> +650 Serviços
      </div>
    </div>
  </div>

  <!-- Lado Direito: Formulário -->
  <div class="flex-1 max-w-md w-full bg-white rounded-3xl shadow-2xl p-8 border border-gray-100">
    <div class="mb-8">
      <h2 class="text-2xl font-black text-gray-800">Acesse sua conta</h2>
      <p class="text-xs text-gray-500 mt-1">Novo por aqui? <a href="{{ route('register') }}" class="font-bold text-blue-600 hover:underline">Crie sua conta grátis →</a></p>
    </div>

    @if(isset($errors) && $errors->any())
      <div class="mb-5 p-3.5 bg-red-50 border border-red-200 text-red-700 rounded-xl text-xs">
        @foreach($errors->all() as $error)
          <p>{{ $error }}</p>
        @endforeach
      </div>
    @endif

    <form action="{{ route('login') }}" method="POST" class="space-y-4">
      @csrf
      <div>
        <label for="email" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">E-mail</label>
        <input type="email" name="email" id="email" required value="{{ old('email') }}" 
               class="w-full px-4 py-3 bg-gray-50 border border-gray-200 text-gray-800 text-sm rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all outline-none" 
               placeholder="seu@email.com">
      </div>

      <div>
        <label for="password" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Senha</label>
        <input type="password" name="password" id="password" required 
               class="w-full px-4 py-3 bg-gray-50 border border-gray-200 text-gray-800 text-sm rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all outline-none" 
               placeholder="••••••••">
      </div>

      <div class="flex items-center justify-between text-xs">
        <label class="flex items-center gap-2 cursor-pointer text-gray-600">
          <input type="checkbox" name="remember" class="w-4 h-4 text-blue-600 rounded">
          <span>Lembrar senha</span>
        </label>
      </div>

      <button type="submit" class="w-full py-4 px-4 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold rounded-xl shadow-lg shadow-blue-500/30 transition-all transform active:scale-95 text-sm">
        Entrar
      </button>
    </form>
  </div>
</div>
@endsection

@extends("layouts.layout")

@section("titulo1", "Criar Conta - AuraSMM")

@section("content")
<div class="max-w-4xl mx-auto flex flex-col md:flex-row items-center justify-between gap-12 py-12 px-6">
  
  <!-- Lado Esquerdo: Benefícios -->
  <div class="flex-1 space-y-6">
    <div class="inline-block px-4 py-2 bg-green-50 text-green-700 text-xs font-bold rounded-full uppercase tracking-widest">
      Cadastro Grátis
    </div>
    <h1 class="text-4xl md:text-5xl font-black text-gray-900 leading-tight">
      Comece a revender <span class="text-blue-600">em menos de 1 minuto</span>
    </h1>
    <p class="text-lg text-gray-600">
      Junte-se a centenas de agências e revendedores com a plataforma de SMM mais rápida do mercado.
    </p>
    
    <div class="space-y-4 pt-4">
      <div class="flex items-start gap-4">
        <div class="p-2 bg-blue-100 text-blue-600 rounded-xl font-bold">1</div>
        <div>
          <h4 class="font-bold text-gray-800">Crie sua conta</h4>
          <p class="text-sm text-gray-500">Sem mensalidade ou fidelidade.</p>
        </div>
      </div>
      <div class="flex items-start gap-4">
        <div class="p-2 bg-blue-100 text-blue-600 rounded-xl font-bold">2</div>
        <div>
          <h4 class="font-bold text-gray-800">Adicione saldo via PIX</h4>
          <p class="text-sm text-gray-500">Aprovação imediata e automática.</p>
        </div>
      </div>
      <div class="flex items-start gap-4">
        <div class="p-2 bg-blue-100 text-blue-600 rounded-xl font-bold">3</div>
        <div>
          <h4 class="font-bold text-gray-800">Faça seus pedidos</h4>
          <p class="text-sm text-gray-500">Entrega rápida com acompanhamento em tempo real.</p>
        </div>
      </div>
    </div>
  </div>

  <!-- Lado Direito: Formulário -->
  <div class="flex-1 max-w-md w-full bg-white rounded-3xl shadow-2xl p-8 border border-gray-100">
    <div class="mb-8">
      <h2 class="text-2xl font-black text-gray-800">Criar conta grátis</h2>
      <p class="text-xs text-gray-500 mt-1">Já possui uma conta? <a href="{{ route('login') }}" class="font-bold text-blue-600 hover:underline">Fazer login →</a></p>
    </div>

    @if(isset($errors) && $errors->any())
      <div class="mb-5 p-3.5 bg-red-50 border border-red-200 text-red-700 rounded-xl text-xs">
        @foreach($errors->all() as $error)
          <p>{{ $error }}</p>
        @endforeach
      </div>
    @endif

    <form action="{{ route('register') }}" method="POST" class="space-y-4">
      @csrf
      <div>
        <label for="name" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Nome Completo</label>
        <input type="text" name="name" id="name" required value="{{ old('name') }}" 
               class="w-full px-4 py-3 bg-gray-50 border border-gray-200 text-gray-800 text-sm rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all outline-none" 
               placeholder="Seu Nome">
      </div>

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
               placeholder="Mínimo de 6 caracteres">
      </div>

      <div>
        <label for="password_confirmation" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Confirmar Senha</label>
        <input type="password" name="password_confirmation" id="password_confirmation" required 
               class="w-full px-4 py-3 bg-gray-50 border border-gray-200 text-gray-800 text-sm rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all outline-none" 
               placeholder="Repita a senha">
      </div>

      <button type="submit" class="w-full py-4 px-4 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold rounded-xl shadow-lg shadow-blue-500/30 transition-all transform active:scale-95 text-sm">
        Criar Minha Conta
      </button>
    </form>
  </div>
</div>
@endsection

@extends("layouts.layout")

@section("titulo1", "Meu Perfil")

@section("content")
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
  <!-- Informações do Perfil -->
  <div class="lg:col-span-1 space-y-6">
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
      <div class="flex flex-col items-center text-center">
        <div class="w-24 h-24 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-3xl flex items-center justify-center text-white text-3xl font-black mb-4 shadow-lg shadow-blue-200">
          {{ substr($user->name, 0, 1) }}
        </div>
        <h3 class="text-xl font-bold text-gray-800">{{ $user->name }}</h3>
        <p class="text-sm text-gray-500">{{ $user->email }}</p>
        
        <div class="mt-6 w-full pt-6 border-t border-gray-50 space-y-4">
          <div class="flex justify-between items-center text-sm">
            <span class="text-gray-500">Saldo Atual:</span>
            <span class="font-bold text-green-600">R$ {{ number_format($user->balance, 2, ',', '.') }}</span>
          </div>
          <div class="flex justify-between items-center text-sm">
            <span class="text-gray-500">Tipo de Conta:</span>
            <span class="px-2 py-0.5 bg-blue-50 text-blue-600 rounded-lg font-bold text-[10px] uppercase">
              {{ $user->is_admin ? 'Administrador' : 'Cliente' }}
            </span>
          </div>
        </div>
      </div>
    </div>

    <!-- Banner Rápido -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-3xl p-6 text-white shadow-xl shadow-blue-200">
      <h4 class="font-bold mb-1">Precisa de Ajuda?</h4>
      <p class="text-xs opacity-80 mb-4">Entre em contato com o suporte para qualquer dúvida sobre seus pedidos.</p>
      <a href="https://wa.me/{{ \App\Models\Setting::get('support_whatsapp', '5511999999999') }}" target="_blank" class="inline-block px-4 py-2 bg-white text-blue-600 text-xs font-bold rounded-xl">Suporte WhatsApp</a>
    </div>
  </div>

  <!-- Formulário de Edição -->
  <div class="lg:col-span-2 space-y-6">
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
      <h3 class="text-xl font-bold text-gray-800 mb-6">Editar Informações</h3>

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

      <form action="{{ route('profile.update') }}" method="POST" class="space-y-6">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Nome Completo</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all text-sm">
          </div>
          <div>
            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">E-mail</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all text-sm">
          </div>
        </div>

        <div class="pt-6 border-t border-gray-50">
          <h4 class="text-sm font-bold text-gray-700 mb-4 uppercase tracking-widest">Alterar Senha</h4>
          <div class="space-y-6">
            <div>
              <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Senha Atual</label>
              <input type="password" name="current_password" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all text-sm" placeholder="Deixe em branco para não alterar">
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Nova Senha</label>
                <input type="password" name="new_password" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all text-sm">
              </div>
              <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Confirmar Nova Senha</label>
                <input type="password" name="new_password_confirmation" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all text-sm">
              </div>
            </div>
          </div>
        </div>

        <div class="flex justify-end pt-4">
          <button type="submit" class="px-8 py-3.5 bg-blue-600 text-white font-bold rounded-2xl shadow-lg shadow-blue-500/30 hover:bg-blue-700 transition-all transform active:scale-95 text-sm">
            Salvar Alterações
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

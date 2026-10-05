@extends("layouts.layout")

@section("titulo1", "Gerenciar Cupons")

@section("content")
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
  <!-- Criar Cupom -->
  <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8 h-fit">
    <h3 class="text-xl font-bold text-gray-800 mb-6">Criar Novo Cupom</h3>

    @if(session('success'))
      <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-2xl text-sm">{{ session('success') }}</div>
    @endif

    <form action="{{ route('admin.coupons.store') }}" method="POST" class="space-y-4">
      @csrf
      <div>
        <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Código do Cupom</label>
        <input type="text" name="code" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm uppercase" placeholder="EX: PROMO10">
      </div>
      <div>
        <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Valor (R$)</label>
        <input type="number" step="0.01" name="amount" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm" placeholder="10.00">
      </div>
      <div>
        <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Limite de Usos</label>
        <input type="number" name="max_uses" value="100" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm">
      </div>
      <button type="submit" class="w-full py-3.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl text-sm transition-all shadow-md shadow-blue-500/20">
        Criar Cupom
      </button>
    </form>
  </div>

  <!-- Lista de Cupons -->
  <div class="lg:col-span-2 bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
    <h3 class="text-xl font-bold text-gray-800 mb-6">Cupons Ativos</h3>
    <div class="overflow-x-auto">
      <table class="w-full text-left border-collapse">
        <thead>
          <tr class="bg-gray-50 border-b border-gray-100 text-xs font-bold text-gray-400 uppercase">
            <th class="p-4">Código</th>
            <th class="p-4">Valor</th>
            <th class="p-4">Usos</th>
            <th class="p-4 text-center">Ações</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-50 text-sm">
          @foreach($coupons as $coupon)
            <tr>
              <td class="p-4 font-black text-blue-600">{{ $coupon->code }}</td>
              <td class="p-4 font-bold text-green-600">R$ {{ number_format($coupon->amount, 2, ',', '.') }}</td>
              <td class="p-4 text-gray-600">{{ $coupon->used_count }} / {{ $coupon->max_uses }}</td>
              <td class="p-4 text-center">
                <form action="{{ route('admin.coupons.delete', $coupon->id) }}" method="POST" onsubmit="return confirm('Excluir cupom?')">
                  @csrf @method('DELETE')
                  <button type="submit" class="px-3 py-1 bg-red-50 text-red-600 hover:bg-red-100 text-xs font-bold rounded-lg">Excluir</button>
                </form>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    <div class="mt-6">{{ $coupons->links() }}</div>
  </div>
</div>
@endsection

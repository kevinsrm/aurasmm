@extends("layouts.layout")

@section("titulo1", "Gerenciar Usuários")

@section("content")
<div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
  <div class="flex justify-between items-center mb-8">
    <h2 class="text-2xl font-black text-gray-800">Usuários Cadastrados</h2>
  </div>

  @if(session('success'))
    <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-2xl text-sm font-medium">{{ session('success') }}</div>
  @endif

  <div class="overflow-x-auto">
    <table class="w-full text-left border-collapse">
      <thead>
        <tr class="bg-gray-50 border-b border-gray-100 text-xs font-bold text-gray-400 uppercase">
          <th class="p-4">ID</th>
          <th class="p-4">Nome</th>
          <th class="p-4">E-mail</th>
          <th class="p-4">Saldo</th>
          <th class="p-4">Tipo</th>
          <th class="p-4 text-center">Ações</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-50 text-sm">
        @foreach($users as $user)
          <tr>
            <td class="p-4 text-gray-600">#{{ $user->id }}</td>
            <td class="p-4 font-bold text-gray-800">{{ $user->name }}</td>
            <td class="p-4 text-gray-500">{{ $user->email }}</td>
            <td class="p-4 font-bold text-green-600">R$ {{ number_format($user->balance, 2, ',', '.') }}</td>
            <td class="p-4">
              <span class="px-3 py-1 rounded-full text-xs font-bold {{ $user->is_admin ? 'bg-purple-100 text-purple-700' : 'bg-blue-50 text-blue-700' }}">
                {{ $user->is_admin ? 'ADMIN' : 'CLIENTE' }}
              </span>
            </td>
            <td class="p-4 text-center flex gap-2 justify-center">
              <form action="{{ route('admin.users.balance', $user->id) }}" method="POST" class="flex gap-1">
                @csrf
                <input type="number" name="amount" step="0.01" class="w-20 px-2 py-1 text-xs border border-gray-200 rounded-lg outline-none" placeholder="0.00">
                <select name="action" class="text-xs border border-gray-200 rounded-lg px-1">
                  <option value="add">Add</option>
                  <option value="subtract">Sub</option>
                  <option value="set">Set</option>
                </select>
                <button type="submit" class="px-3 py-1 bg-green-50 text-green-600 hover:bg-green-100 text-xs font-bold rounded-lg transition-colors">Saldo</button>
              </form>
              
              <form action="{{ route('admin.users.toggleAdmin', $user->id) }}" method="POST">
                @csrf
                <button type="submit" class="px-3 py-1 bg-purple-50 text-purple-600 hover:bg-purple-100 text-xs font-bold rounded-lg transition-colors">
                  {{ $user->is_admin ? 'Remover Admin' : 'Tornar Admin' }}
                </button>
              </form>

              <form action="{{ route('admin.users.delete', $user->id) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir este usuário?')">
                @csrf @method('DELETE')
                <button type="submit" class="px-3 py-1 bg-red-50 text-red-600 hover:bg-red-100 text-xs font-bold rounded-lg transition-colors">Excluir</button>
              </form>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  <div class="mt-6">{{ $users->links() }}</div>
</div>
@endsection

@extends('errors.layout')

@section('error_title')
419 - Página Expirada
@endsection

@section('error_icon')
<svg class="error-icon w-20 h-20 text-amber-600 animate-pulse-slow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
</svg>
@endsection

@section('error_code')
419
@endsection

@section('error_title_color')
text-amber-600
@endsection

@section('error_message')
Desculpe! A página que você tentou acessar expirou ou já foi utilizada.
@endsection

@section('error_actions')
<div class="flex flex-col gap-3">
    <a href="{{ route('services') }}" class="block w-full py-3 px-6 bg-amber-600 text-white font-semibold rounded-xl hover:bg-amber-700 transition-colors text-center shadow-lg hover:shadow-amber-500/20">
        Gerar Novo Pedido
    </a>
    <a href="{{ route('orders.index') }}" class="block w-full py-3 px-6 bg-gray-100 text-gray-700 font-semibold rounded-xl hover:bg-gray-200 transition-colors text-center border border-gray-300">
        Meus Pedidos Atuais
    </a>
</div>
@endsection

@section('error_details')
Tipo de erro: Sessão expirada | Código: 419 | Tempo: {{ date('d/m/Y H:i:s') }}
@endsection
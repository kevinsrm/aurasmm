@extends('errors.layout')

@section('error_title')
404 - Página Não Encontrada
@endsection

@section('error_icon')
<svg class="error-icon w-20 h-20 text-orange-500 animate-pulse-slow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M12 12h.01M12 8h.01M12 16h.01M16 16v1a2 2 0 01-2 2H6a2 2 0 01-2-2V7a2 2 0 012-2h4a2 2 0 012 2v1m0 6h.01" />
</svg>
@endsection

@section('error_code')
404
@endsection

@section('error_title_color')
text-orange-600
@endsection

@section('error_message')
Desculpe! A página que você está procurando não existe ou foi movida para outro local.
@endsection

@section('error_actions')
<div class="flex flex-col gap-3">
    <a href="{{ route('services') }}" class="block w-full py-3 px-6 bg-blue-600 text-white font-semibold rounded-xl hover:bg-blue-700 transition-colors text-center shadow-lg hover:shadow-blue-500/20">
        Voltar ao Início
    </a>
    <a href="{{ route('orders.index') }}" class="block w-full py-3 px-6 bg-gray-100 text-gray-700 font-semibold rounded-xl hover:bg-gray-200 transition-colors text-center border border-gray-300">
        Meus Pedidos
    </a>
</div>
@endsection

@section('error_details')
Tipo de erro: Página não encontrada | Código: 404 | Tempo: {{ date('d/m/Y H:i:s') }}
@endsection
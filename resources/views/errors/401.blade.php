@extends('errors.layout')

@section('error_title')
401 - Não Autorizado
@endsection

@section('error_icon')
<svg class="error-icon w-20 h-20 text-blue-600 animate-pulse-slow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM5 9h2a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v2a2 2 0 002 2zm0 2H5v2a2 2 0 002 2h2a2 2 0 002-2V9zm6 2h2a2 2 0 002-2M5 13H3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2zm6 2H9a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2zm6-6H17a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2v2a2 2 0 002 2zM7 10h2a2 2 0 002-2M7 14h2a2 2 0 002-2m10 14v2a2 2 0 002-2h2a2 2 0 002 2v-2a2 2 0 00-2-2h-2a2 2 0 00-2 2z" />
</svg>
@endsection

@section('error_code')
401
@endsection

@section('error_title_color')
text-blue-600
@endsection

@section('error_message')
Desculpe! Você precisa estar autenticado para acessar esta página.
@endsection

@section('error_actions')
<div class="flex flex-col gap-3">
    <a href="{{ route('login') }}" class="block w-full py-3 px-6 bg-blue-600 text-white font-semibold rounded-xl hover:bg-blue-700 transition-colors text-center shadow-lg hover:shadow-blue-500/20">
        Fazer Login
    </a>
    <a href="{{ route('services') }}" class="block w-full py-3 px-6 bg-gray-100 text-gray-700 font-semibold rounded-xl hover:bg-gray-200 transition-colors text-center border border-gray-300">
        Voltar ao Início
    </a>
      @if(Auth::guest())
        <p class="text-sm text-gray-500 text-center">
            Não tem conta? <a href="{{ route('register') }}" class="text-blue-600 font-semibold hover:text-blue-700">Cadastre-se aqui</a>
        </p>
    @endif
</div>
@endsection

@section('error_details')
Tipo de erro: Autenticação necessária | Código: 401 | Tempo: {{ date('d/m/Y H:i:s') }}
@endsection
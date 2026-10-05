@extends('errors.layout')

@section('error_title')
403 - Acesso Proibido
@endsection

@section('error_icon')
<svg class="error-icon w-20 h-20 text-purple-600 animate-pulse-slow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
</svg>
@endsection

@section('error_code')
403
@endsection

@section('error_title_color')
text-purple-600
@endsection

@section('error_message')
Desculpe! Você não tem permissão para acessar este recurso. Por favor, verifique suas permissões ou entre em contato com o administrador.
@endsection

@section('error_actions')
<div class="flex flex-col gap-3">
    <a href="{{ route('services') }}" class="block w-full py-3 px-6 bg-purple-600 text-white font-semibold rounded-xl hover:bg-purple-700 transition-colors text-center shadow-lg hover:shadow-purple-500/20">
        Voltar ao Início
    </a>
    @if(Auth::user())
        <a href="{{ route('profile.show') }}" class="block w-full py-3 px-6 bg-gray-100 text-gray-700 font-semibold rounded-xl hover:bg-gray-200 transition-colors text-center border border-gray-300">
            Meu Perfil
        </a>
    @endif
</div>
@endsection

@section('error_details')
Tipo de erro: Acesso não autorizado | Código: 403 | Tempo: {{ date('d/m/Y H:i:s') }}
@endsection
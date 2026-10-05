@extends('errors.layout')

@section('error_title')
500 - Erro Interno do Servidor
@endsection

@section('error_icon')
<svg class="error-icon w-20 h-20 text-red-600 animate-shake" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
</svg>
@endsection

@section('error_code')
500
@endsection

@section('error_title_color')
text-red-600
@endsection

@section('error_message')
Oops! Algo inesperado aconteceu. Nossa equipe foi notificada e estamos trabalhando para resolver isso rapidamente.
@endsection

@section('error_actions')
<div class="flex flex-col gap-3">
    <a href="{{ route('services') }}" class="block w-full py-3 px-6 bg-red-600 text-white font-semibold rounded-xl hover:bg-red-700 transition-colors text-center shadow-lg hover:shadow-red-500/20">
        Voltar ao Início
    </a>
    <a href="{{ route('contact') }}" class="block w-full py-3 px-6 bg-gray-100 text-gray-700 font-semibold rounded-xl hover:bg-gray-200 transition-colors text-center border border-gray-300">
        Contatar Suporte
    </a>
</div>
@endsection

@section('error_details')
Tipo de erro: Erro Interno | Código: 500 | Tempo: {{ date('d/m/Y H:i:s') }}
@endsection
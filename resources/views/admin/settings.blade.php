@extends("layouts.layout")

@section("titulo1", "Configurações Admin")

@section("content")
<div class="max-w-4xl mx-auto space-y-8">
  <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
    <h1 class="text-2xl font-black text-gray-800">Painel de Configurações</h1>
    <p class="text-xs text-gray-500 mt-1">Gerencie chaves de API, taxas de margem de lucro e pixels de rastreamento de anúncios.</p>
  </div>

  @if(session('success'))
    <div class="p-4 bg-green-50 border border-green-200 text-green-700 rounded-2xl text-sm font-medium">
      {{ session('success') }}
    </div>
  @endif

  <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-8">
    @csrf

    <!-- SECTION 1: CONFIGURAÇÕES GERAIS -->
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8 space-y-6">
      <div class="border-b pb-4">
        <h3 class="text-lg font-bold text-gray-800">Configurações Gerais & Integrações</h3>
        <p class="text-xs text-gray-400">Configure chaves de API básicas e regras de faturamento</p>
      </div>
      
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
          <label class="block text-xs font-bold text-gray-700 uppercase mb-2">SMMHub API Key</label>
          <input type="text" name="smm_api_key" value="{{ $smmApiKey }}" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm" placeholder="Chave da API">
        </div>
        <div>
          <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Mercado Pago Access Token</label>
          <input type="text" name="mercadopago_token" value="{{ $mpToken }}" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm" placeholder="APP_USR-...">
        </div>
      </div>

      <div>
        <label class="block text-xs font-bold text-gray-700 uppercase mb-2">WhatsApp do Suporte (Ex: 5511999999999)</label>
        <input type="text" name="support_whatsapp" value="{{ $supportWhatsapp ?? '' }}" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm" placeholder="5511999999999">
        <p class="text-xs text-gray-400 mt-1">Apenas números com DDI e DDD (ex: 5511987654321). Será utilizado no botão de suporte do perfil.</p>
      </div>

      <div>
        <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Margem de Lucro nos Serviços (%)</label>
        <div class="space-y-3">
          <input type="number" step="0.01" min="0" id="profit_margin" name="profit_margin" value="{{ $profitMargin }}" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm" placeholder="Ex: 5">
          <div class="flex gap-2">
            <button type="button" onclick="document.getElementById('profit_margin').value = 2" class="px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold rounded-lg transition-colors">+2%</button>
            <button type="button" onclick="document.getElementById('profit_margin').value = 4" class="px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold rounded-lg transition-colors">+4%</button>
            <button type="button" onclick="document.getElementById('profit_margin').value = 5" class="px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold rounded-lg transition-colors">+5%</button>
            <button type="button" onclick="document.getElementById('profit_margin').value = 10" class="px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold rounded-lg transition-colors">+10%</button>
          </div>
          <p class="text-xs text-gray-400">Adicional cobrado sobre o valor de custo original de todos os serviços.</p>
        </div>
      </div>
    </div>

    <!-- SECTION 2: SISTEMA DE ANÚNCIOS (PIXELS E RASTREAMENTO) -->
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8 space-y-6">
      <div class="border-b pb-4">
        <h3 class="text-lg font-bold text-gray-800">Sistema de Rastreamento de Anúncios</h3>
        <p class="text-xs text-gray-400">Configure pixels e tags de conversão para monitorar suas campanhas pagas</p>
      </div>

      <!-- Google & YouTube -->
      <div class="space-y-4">
        <h4 class="text-sm font-bold text-gray-700 flex items-center gap-2">
          <span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span>
          Google Ads & YouTube
        </h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Google Global Tag / Gtag ID (ou G-XXXX / UA-XXXX)</label>
            <input type="text" name="ad_google_tag_id" value="{{ $adGoogleTagId }}" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm placeholder-gray-300" placeholder="Ex: AW-123456789 ou G-XXXXX">
            <p class="text-xxs text-gray-400 mt-1">Injeta o snippet gtag.js padrão do Google Analytics/Ads.</p>
          </div>
        </div>
      </div>

      <!-- Facebook & Meta -->
      <div class="space-y-4 border-t pt-6">
        <h4 class="text-sm font-bold text-gray-700 flex items-center gap-2">
          <span class="w-2.5 h-2.5 rounded-full bg-indigo-600"></span>
          Facebook & Meta Ads
        </h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Facebook Pixel ID</label>
            <input type="text" name="ad_fb_pixel_id" value="{{ $adFbPixelId }}" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm placeholder-gray-300" placeholder="Ex: 123456789012345">
            <p class="text-xxs text-gray-400 mt-1">Injeta o código do Facebook Pixel no head de todas as páginas.</p>
          </div>
        </div>
      </div>

      <!-- TikTok -->
      <div class="space-y-4 border-t pt-6">
        <h4 class="text-sm font-bold text-gray-700 flex items-center gap-2">
          <span class="w-2.5 h-2.5 rounded-full bg-black"></span>
          TikTok Ads
        </h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">TikTok Pixel ID</label>
            <input type="text" name="ad_tiktok_pixel_id" value="{{ $adTiktokPixelId }}" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm placeholder-gray-300" placeholder="Ex: CTR123456789">
            <p class="text-xxs text-gray-400 mt-1">Injeta o pixel oficial do TikTok de rastreamento de conversão.</p>
          </div>
        </div>
      </div>

      <!-- Scripts Customizados -->
      <div class="space-y-4 border-t pt-6">
        <h4 class="text-sm font-bold text-gray-700 flex items-center gap-2">
          <span class="w-2.5 h-2.5 rounded-full bg-gray-600"></span>
          Outros / Custom HTML Scripts
        </h4>
        <div class="space-y-4">
          <div>
            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Scripts Adicionais no Header (&lt;head&gt;)</label>
            <textarea name="ad_custom_head_script" rows="4" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-mono placeholder-gray-300" placeholder="<!-- Insira seus scripts que devem ir no <head> aqui -->">{{ $adCustomHeadScript }}</textarea>
            <p class="text-xxs text-gray-400 mt-1">Insira tags inteiras &lt;script&gt; ou estilos css personalizados.</p>
          </div>
          <div>
            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Scripts Adicionais no Início do Body (&lt;body&gt;)</label>
            <textarea name="ad_custom_body_script" rows="4" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-mono placeholder-gray-300" placeholder="<!-- Insira seus scripts que devem ir no início do <body> aqui -->">{{ $adCustomBodyScript }}</textarea>
            <p class="text-xxs text-gray-400 mt-1">Insira snippets adicionais de carregamento no body.</p>
          </div>
        </div>
      </div>
    </div>

    <!-- SECTION 3: SEO E OTIMIZAÇÃO PARA BUSCADORES -->
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8 space-y-6">
      <div class="border-b pb-4">
        <h3 class="text-lg font-bold text-gray-800">Configurações de SEO</h3>
        <p class="text-xs text-gray-400">Otimize o site para mecanismos de busca (Google, Bing) e redes sociais</p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
          <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Título do Site (Meta Title)</label>
          <input type="text" name="seo_title" value="{{ $seoTitle }}" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm" placeholder="Ex: AuraSMM - Painel SMM">
        </div>
        <div>
          <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Palavras-Chave (Meta Keywords)</label>
          <input type="text" name="seo_keywords" value="{{ $seoKeywords }}" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm" placeholder="painel smm, seguidores, curtidas">
        </div>
      </div>

      <div>
        <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Descrição do Site (Meta Description)</label>
        <textarea name="seo_description" rows="3" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-xs" placeholder="Breve resumo sobre a plataforma...">{{ $seoDescription }}</textarea>
      </div>

      <div>
        <label class="block text-xs font-bold text-gray-700 uppercase mb-2">URL da Imagem de Compartilhamento (Open Graph / OG Image)</label>
        <input type="url" name="seo_og_image" value="{{ $seoOgImage }}" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm" placeholder="https://seudominio.com/img/share.jpg">
      </div>
    </div>

    <!-- Botão Salvar Geral -->
    <div class="flex justify-end">
      <button type="submit" class="w-full md:w-auto px-8 py-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-2xl transition-all shadow-lg shadow-blue-500/20">
        Salvar Todas as Configurações
      </button>
    </div>
  </form>
</div>
@endsection

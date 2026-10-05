<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('titulo1') - AuraSMM</title>
  
  <!-- SEO Meta Tags -->
  <meta name="description" content="{{ \App\Models\Setting::get('seo_description', 'Painel SMM de revenda de seguidores, curtidas e visualizações.') }}">
  <meta name="keywords" content="{{ \App\Models\Setting::get('seo_keywords', 'painel smm, seguidores, instagram, tiktok') }}">
  <meta property="og:title" content="{{ \App\Models\Setting::get('seo_title', 'AuraSMM - Painel SMM') }}">
  <meta property="og:description" content="{{ \App\Models\Setting::get('seo_description', 'Painel SMM de revenda de seguidores, curtidas e visualizações.') }}">
  @if($ogImage = \App\Models\Setting::get('seo_og_image'))
    <meta property="og:image" content="{{ $ogImage }}">
  @endif

  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

  <!-- Google Global Site Tag (gtag.js) -->
  @if($googleTagId = \App\Models\Setting::get('ad_google_tag_id'))
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $googleTagId }}"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', '{{ $googleTagId }}');
    </script>
  @endif

  <!-- Facebook / Meta Pixel -->
  @if($fbPixelId = \App\Models\Setting::get('ad_fb_pixel_id'))
    <script>
      !function(f,b,e,v,n,t,s)
      {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
      n.callMethod.apply(n,arguments):n.queue.push(arguments)};
      if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
      n.queue=[];t=b.createElement(e);t.async=!0;
      t.src=v;s=b.getElementsByTagName(e)[0];
      s.parentNode.insertBefore(t,s)}(window, document,'script',
      'https://connect.facebook.net/en_US/fbevents.js');
      fbq('init', '{{ $fbPixelId }}');
      fbq('track', 'PageView');
    </script>
    <noscript>
      <img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id={{ $fbPixelId }}&ev=PageView&noscript=1"/>
    </noscript>
  @endif

  <!-- TikTok Pixel -->
  @if($tiktokPixelId = \App\Models\Setting::get('ad_tiktok_pixel_id'))
    <script>
      !function (w, d, t) {
        w.TiktokAnalyticsObject=t;var ttq=w[t]=w[t]||[];ttq.methods=["page","track","identify","instances","debug","on","off","once","ready","alias","group","enableCookie","disableCookie","holdConsent","revokeConsent","grantConsent"],ttq.setAndDefer=function(t,e){t[e]=function(){t.push([e].concat(Array.prototype.slice.call(arguments,0)))}};for(var i=0;i<ttq.methods.length;i++)ttq.setAndDefer(ttq,ttq.methods[i]);ttq.instance=function(t){for(var e=ttq[t]||[],n=0;n<ttq.methods.length;n++)ttq.setAndDefer(e,ttq.methods[n]);return e},ttq.load=function(e,n){var o="https://analytics.tiktok.com/i18n/pixel/events.js";ttq._i=ttq._i||{},ttq._i[e]=[],ttq._i[e]._u=o,ttq._t=ttq._t||+new Date,ttq._o=ttq._o||w,ttq._o[ttq.AnalyticsObject]=ttq._o[ttq.AnalyticsObject]||ttq._o;var c=null;if(d.cookie&&w.sessionStorage&&w.localStorage){var a=d.cookie.match(/(^|;)\s*_tt_enable_cookie\s*=\s*([^;]+)/);if(a&&"1"===a[2]){c="https://analytics.tiktok.com/i18n/pixel/enable_cookie.js"}}var s=d.createElement("script");s.type="text/javascript",s.async=!0,s.src=c||o;var p=d.getElementsByTagName("script")[0];p.parentNode.insertBefore(s,p)};
        ttq.load('{{ $tiktokPixelId }}');
        ttq.page();
      }(window, document, 'ttq');
    </script>
  @endif

  <!-- Custom Head Scripts -->
  @if($customHead = \App\Models\Setting::get('ad_custom_head_script'))
    {!! $customHead !!}
  @endif
</head>
<body class="bg-gray-50 text-gray-800 font-sans min-h-screen flex flex-col">
  <!-- Custom Body Scripts -->
  @if($customBody = \App\Models\Setting::get('ad_custom_body_script'))
    {!! $customBody !!}
  @endif

  <!-- Header Navigation -->
  @auth
    <header class="bg-white border-b border-gray-100 shadow-sm sticky top-0 z-50">
      <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
          <div class="flex items-center gap-2">
            <a href="{{ route('services') }}" class="flex items-center gap-2">
              <span class="p-2 bg-blue-600 text-white rounded-xl shadow-md shadow-blue-500/20">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
              </span>
              <span class="font-black text-xl bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">AuraSMM</span>
            </a>
          </div>

          <!-- Navigation Menu -->
          <nav class="hidden md:flex items-center gap-6">
            <a href="{{ route('services') }}" class="text-sm font-semibold hover:text-blue-600 transition-colors {{ Route::is('services') ? 'text-blue-600' : 'text-gray-600' }}">Novo Pedido</a>
            <a href="{{ route('orders.index') }}" class="text-sm font-semibold hover:text-blue-600 transition-colors {{ Route::is('orders.index') ? 'text-blue-600' : 'text-gray-600' }}">Meus Pedidos</a>
            <a href="{{ route('deposit.show') }}" class="text-sm font-semibold hover:text-blue-600 transition-colors {{ Route::is('deposit.show') ? 'text-blue-600' : 'text-gray-600' }}">Adicionar Saldo</a>
            <a href="{{ route('profile.show') }}" class="text-sm font-semibold hover:text-blue-600 transition-colors {{ Route::is('profile.show') ? 'text-blue-600' : 'text-gray-600' }}">Meu Perfil</a>
            @if(Auth::user()->is_admin)
              <a href="{{ route('admin.dashboard') }}" class="text-sm font-semibold text-rose-600 hover:text-rose-700 transition-colors">Admin Dashboard</a>
            @endif
          </nav>

          <!-- User Quick Stats & Actions -->
          <div class="flex items-center gap-4">
            <!-- Balance Badge -->
            <a href="{{ route('deposit.show') }}" class="px-4 py-1.5 bg-green-50 border border-green-200 text-green-700 text-xs font-bold rounded-xl flex items-center gap-1.5 shadow-sm hover:bg-green-100 transition-colors">
              <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
              R$ {{ number_format(Auth::user()->balance, 2, ',', '.') }}
            </a>

            <!-- Logout -->
            <form action="{{ route('logout') }}" method="POST" class="inline">
              @csrf
              <button type="submit" class="p-2 text-gray-400 hover:text-red-500 rounded-xl hover:bg-red-50 transition-all" title="Sair">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
              </button>
            </form>
          </div>
        </div>
      </div>

      <!-- Mobile menu strip -->
      <div class="md:hidden flex justify-around py-3 bg-gray-50 border-t border-gray-100 text-xs font-bold">
        <a href="{{ route('services') }}" class="{{ Route::is('services') ? 'text-blue-600' : 'text-gray-600' }}">Novo</a>
        <a href="{{ route('orders.index') }}" class="{{ Route::is('orders.index') ? 'text-blue-600' : 'text-gray-600' }}">Pedidos</a>
        <a href="{{ route('deposit.show') }}" class="{{ Route::is('deposit.show') ? 'text-blue-600' : 'text-gray-600' }}">Saldo</a>
        <a href="{{ route('profile.show') }}" class="{{ Route::is('profile.show') ? 'text-blue-600' : 'text-gray-600' }}">Perfil</a>
        @if(Auth::user()->is_admin)
          <a href="{{ route('admin.dashboard') }}" class="text-rose-600">Admin</a>
        @endif
      </div>
    </header>
  @endauth

  <!-- Main Content -->
  <main class="flex-grow py-6">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
      @yield('content')
    </div>
  </main>

  <!-- Footer -->
  <footer class="bg-white border-t border-gray-100 py-6 mt-12">
    <div class="max-w-6xl mx-auto px-4 text-center text-xs text-gray-400">
      <p>&copy; {{ date('Y') }} AuraSMM - Todos os direitos reservados.</p>
    </div>
  </footer>

</body>
</html>

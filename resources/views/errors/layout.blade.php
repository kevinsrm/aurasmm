<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('error_title') - AuraSMM</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }
        .animate-fade-in { animation: fadeIn 0.8s ease-out forwards; }
        .animate-pulse-slow { animation: pulse 3s ease-in-out infinite; }
        .animate-shake { animation: shake 0.5s ease-in-out; }
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .error-icon {
            filter: drop-shadow(0 4px 6px rgba(0, 0, 0, 0.1));
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 font-sans min-h-screen flex flex-col items-center justify-center">
    <div class="w-full max-w-md px-4">
        <div class="bg-white rounded-2xl shadow-2xl p-8 border border-gray-200 animate-fade-in">
            <div class="text-center mb-6">
                @yield('error_icon')
            </div>
            <h1 class="text-4xl font-bold text-center mb-4 @yield('error_title_color')">
                @yield('error_code')
            </h1>
            <h2 class="text-xl font-semibold text-center text-gray-600 mb-6">
                @yield('error_message')
            </h2>
            <div class="space-y-3">
                @yield('error_actions')
            </div>
        </div>
        <div class="mt-8 text-center text-sm text-gray-500">
            <p>AuraSMM - {{ date('Y') }}</p>
            <p class="mt-1">@yield('error_details')</p>
        </div>
    </div>
</body>
</html>
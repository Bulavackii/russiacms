<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $meta_title ?? ($title ?? 'RU CMS') }}</title>
    @if (!empty($meta_description)) <meta name="description" content="{{ $meta_description }}"> @endif
    @if (!empty($meta_keywords))    <meta name="keywords"    content="{{ $meta_keywords }}">    @endif
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url()->current() }}">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

    {{-- OG/Twitter --}}
    <meta property="og:title" content="{{ $meta_title ?? ($title ?? 'RU CMS') }}">
    @if (!empty($meta_description)) <meta property="og:description" content="{{ $meta_description }}"> @endif
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="article">
    <meta property="og:locale" content="ru_RU">
    <meta name="twitter:card" content="summary">
    <meta name="twitter:title" content="{{ $meta_title ?? ($title ?? 'RU CMS') }}">
    @if (!empty($meta_description)) <meta name="twitter:description" content="{{ $meta_description }}"> @endif

    @stack('styles')

    {{-- Prism/Swiper/Tailwind/FA (FA оставим как фолбэк) --}}
    <link href="https://cdn.jsdelivr.net/npm/prismjs/themes/prism-tomorrow.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/prismjs/prism.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/prismjs/components/prism-markup.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/prismjs/components/prism-html.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/prismjs/components/prism-css.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/prismjs/components/prism-javascript.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/prismjs/components/prism-php.min.js"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    {{-- Фолбэк-иконки --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer"/>
    @vite('resources/css/app.css')

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @php
        // ==== ТЕМА ====
        $tokens = $activeTheme->tokens ?? [];
        $config = $activeTheme->config ?? [];

        $fontBase = data_get($tokens, 'font.base',  'Inter, system-ui, sans-serif');
        $radiusMd = data_get($tokens, 'radius.md', '12px');

        $cBg      = data_get($tokens, 'colors.bg',      '#ffffff');
        $cText    = data_get($tokens, 'colors.text',    '#111827');
        $cPrimary = data_get($tokens, 'colors.primary', '#2563eb');
        $cAccent  = data_get($tokens, 'colors.accent',  '#10b981');
        $cHeader  = data_get($tokens, 'colors.header',  '#ffffff');
        $cFooter  = data_get($tokens, 'colors.footer',  '#ffffff');

        $bgImage = data_get($config, 'background_url')
            ?? data_get($config, 'bg_url')
            ?? data_get($config, 'pattern_url')
            ?? data_get($config, 'bg_image')
            ?? null;

        $iconMode = data_get($config, 'icon_mode', 'fa');

        $fontProvider = data_get($config, 'font_provider'); // 'google' | 'bunny' | null
        $fontName     = trim((string) data_get($config, 'font_name', ''));
    @endphp

    {{-- Онлайн-шрифт --}}
    @if($fontProvider === 'google' && $fontName !== '')
        <link href="https://fonts.googleapis.com/css2?family={{ urlencode($fontName) }}:wght@400;500;600;700&display=swap" rel="stylesheet">
    @elseif($fontProvider === 'bunny' && $fontName !== '')
        <link href="https://fonts.bunny.net/css?family={{ urlencode(str_replace(' ', '-', $fontName)) }}:400,500,600,700" rel="stylesheet">
    @endif

    {{-- Иконки по режиму --}}
    @if($iconMode === 'bootstrap')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    @elseif($iconMode === 'remix')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon@3.7.0/fonts/remixicon.css">
    @elseif($iconMode === 'tabler')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
    @elseif($iconMode === 'lucide')
        <script src="https://cdn.jsdelivr.net/npm/lucide@latest"></script>
    @endif

    {{-- CSS-переменные темы + единый bg-image --}}
    <style id="theme-vars">
        :root{
            --font-base: {{ $fontBase }};
            --radius-md: {{ $radiusMd }};
            --color-bg: {{ $cBg }};
            --color-text: {{ $cText }};
            --color-primary: {{ $cPrimary }};
            --color-accent: {{ $cAccent }};
            --color-header: {{ $cHeader }};
            --color-footer: {{ $cFooter }};
            --bg-image: url('{{ $bgImage ?: asset('images/fon.jpg') }}');
        }
        .text-theme       { color: var(--color-text) }
        .bg-theme         { background: var(--color-bg) }
        .bg-header-theme  { background: var(--color-header) }
        .bg-footer-theme  { background: var(--color-footer) }
        .btn-theme        { background: var(--color-primary); color:#fff }
        .rounded-theme    { border-radius: var(--radius-md) }
        .rounded,.rounded-md,.rounded-lg,.rounded-xl,.rounded-2xl { border-radius: var(--radius-md) !important; }
        button,input,.card { border-radius: var(--radius-md) }
    </style>

    <style>
        #wrapper { transition: filter 0.3s ease; }
        .accessibility-button, .scroll-to-top { position: fixed; z-index: 9999; }
        .accessibility-button { bottom: 1.5rem; left: 1.5rem; filter:none !important; isolation:isolate; }
        .scroll-to-top { bottom: 1.5rem; right: 1.5rem; }
        .scroll-to-top-container { position: fixed; bottom: 1.5rem; right: 1.5rem; z-index: 9999; filter:none!important; backdrop-filter:none!important; isolation:isolate; }
    </style>
</head>

<body class="relative text-gray-800 min-h-screen flex flex-col border-l border-r border-black overflow-x-hidden"
      style="background: var(--color-bg,#ffffff); color: var(--color-text,#111827); font-family: var(--font-base, Inter, system-ui, sans-serif)">

    {{-- ЕДИНЫЙ фон-паттерн из темы --}}
    <div class="absolute inset-0 z-0 opacity-10 pointer-events-none"
         style="background-image: var(--bg-image); background-repeat:repeat; background-size:auto"></div>

    <div id="wrapper" class="relative z-10 flex flex-col min-h-screen">
        @include('layouts.partials.header')
        <x-frontend-notifications />

        <main class="flex-grow py-10">
            <div class="container mx-auto px-4">
                @yield('content')
            </div>
        </main>

        @include('layouts.partials.footer')
    </div>

    @if (!empty($accessibility) && $accessibility->enabled)
        @include('Accessibility::frontend.widget', ['settings' => $accessibility])
    @endif

    <div class="scroll-to-top-container">
        @includeIf('components.scroll-to-top')
    </div>

    @stack('scripts')
    <script src="{{ asset('js/accessibility.js') }}"></script>

    @if($iconMode === 'lucide')
        <script>document.addEventListener('DOMContentLoaded', () => window.lucide && window.lucide.createIcons());</script>
    @endif
</body>
</html>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $meta_title ?? ($title ?? 'RU CMS') }}</title>

    @if (!empty($meta_description))
        <meta name="description" content="{{ $meta_description }}">
    @endif
    @if (!empty($meta_keywords))
        <meta name="keywords" content="{{ $meta_keywords }}">
    @endif

    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url()->current() }}">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

    {{-- Open Graph --}}
    <meta property="og:title" content="{{ $meta_title ?? ($title ?? 'RU CMS') }}">
    @if (!empty($meta_description))
        <meta property="og:description" content="{{ $meta_description }}">
    @endif
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="article">
    <meta property="og:locale" content="ru_RU">

    {{-- Twitter --}}
    <meta name="twitter:card" content="summary">
    <meta name="twitter:title" content="{{ $meta_title ?? ($title ?? 'RU CMS') }}">
    @if (!empty($meta_description))
        <meta name="twitter:description" content="{{ $meta_description }}">
    @endif

    @stack('styles')

    {{-- Prism.js --}}
    <link href="https://cdn.jsdelivr.net/npm/prismjs/themes/prism-tomorrow.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/prismjs/prism.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/prismjs/components/prism-markup.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/prismjs/components/prism-html.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/prismjs/components/prism-css.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/prismjs/components/prism-javascript.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/prismjs/components/prism-php.min.js"></script>

    {{-- UI libs --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer"/>
    @vite('resources/css/app.css')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @php
        // Безопасное извлечение данных из активной темы
        $theme   = $activeTheme ?? null;
        $tokens  = data_get($theme, 'tokens', []);
        $config  = data_get($theme, 'config', []);

        // На случай, если в БД лежит JSON-строка
        if (is_string($tokens)) { $tokens = json_decode($tokens, true) ?: []; }
        if (is_string($config)) { $config = json_decode($config, true) ?: []; }

        $colorBg     = data_get($tokens, 'colors.bg',      '#ffffff');
        $colorText   = data_get($tokens, 'colors.text',    '#111827');
        $colorPrimary= data_get($tokens, 'colors.primary', '#2563eb');
        $colorAccent = data_get($tokens, 'colors.accent',  '#10b981');
        $colorHeader = data_get($tokens, 'colors.header',  '#ffffff');
        $colorFooter = data_get($tokens, 'colors.footer',  '#ffffff');

        $fontBase    = data_get($tokens, 'font.base', 'Inter, system-ui, sans-serif');
        $radiusMd    = data_get($tokens, 'radius.md', '12px');

        $bgImage     = data_get($config, 'background_url');   // <-- ВАЖНО: теперь определена
        $fontProvider= data_get($config, 'font_provider');    // google | bunny | null
        $fontName    = trim((string) data_get($config, 'font_name', '')); // напр. "Inter"
        $extraCss    = (string) data_get($config, 'css', '');
    @endphp>

    {{-- Подключение онлайн-шрифта, если задан --}}
    @if($fontProvider === 'google' && $fontName !== '')
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family={{ urlencode(str_replace(' ', '+', $fontName)) }}:wght@400;500;600;700&display=swap" rel="stylesheet">
        <style>:root{ --font-base: '{{ $fontName }}', system-ui, sans-serif; }</style>
    @elseif($fontProvider === 'bunny' && $fontName !== '')
        <link href="https://fonts.bunny.net/css?family={{ urlencode(str_replace(' ', '+', $fontName)) }}:400,500,600,700&display=swap" rel="stylesheet">
        <style>:root{ --font-base: '{{ $fontName }}', system-ui, sans-serif; }</style>
    @endif

    {{-- Токены темы в :root (+ запасные значения) --}}
    <style>
        :root{
            --font-base: {{ $fontBase }};
            --radius-md: {{ $radiusMd }};
            --colors-bg: {{ $colorBg }};
            --colors-text: {{ $colorText }};
            --colors-primary: {{ $colorPrimary }};
            --colors-accent: {{ $colorAccent }};
            --colors-header: {{ $colorHeader }};
            --colors-footer: {{ $colorFooter }};
        }

        #wrapper { transition: filter 0.3s ease; }

        .accessibility-button,
        .scroll-to-top { position: fixed; z-index: 9999; }

        .accessibility-button { bottom: 1.5rem; left: 1.5rem; filter: none !important; isolation: isolate; }
        .scroll-to-top        { bottom: 1.5rem; right: 1.5rem; }

        .scroll-to-top-container{
            position: fixed; bottom: 1.5rem; right: 1.5rem; z-index: 9999;
            filter: none !important; backdrop-filter: none !important; isolation: isolate;
        }

        /* Дополнительный CSS из темы (если указан админом) */
        {!! $extraCss !!}
    </style>
</head>

<body class="relative text-gray-800 min-h-screen flex flex-col border-l border-r border-black overflow-x-hidden"
      style="font-family: var(--font-base, Inter, system-ui, sans-serif)">

    {{-- Фон: паттерн из темы или стандартный --}}
    <div class="absolute inset-0 z-0 opacity-10 pointer-events-none"
         style="background-image:url('{{ $bgImage ? e($bgImage) : asset('images/fon.jpg') }}');background-repeat:repeat;background-size:auto"></div>

    {{-- Контент в wrapper для фильтров --}}
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

    {{-- Виджет доступности (вне wrapper, чтобы не фильтровался) --}}
    @if (!empty($accessibility) && $accessibility->enabled)
        @include('Accessibility::frontend.widget', ['settings' => $accessibility])
    @endif

    {{-- Кнопка "наверх" (вне wrapper) --}}
    <div class="scroll-to-top-container">
        @includeIf('components.scroll-to-top')
    </div>

    @stack('scripts')
    <script src="{{ asset('js/accessibility.js') }}"></script>
</body>
</html>

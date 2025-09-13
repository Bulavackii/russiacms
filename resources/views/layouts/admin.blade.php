<!-- resources/views/layouts/admin.blade.php -->
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Панель управления')</title>

  <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer"/>
  <style>[x-cloak]{display:none!important}</style>
</head>

<body class="bg-gray-100 text-gray-800">
  {{-- фиксированный сайдбар --}}
  @include('layouts.admin.sidebar')

  {{-- ВАЖНО: каркас с липким футером --}}
  <div id="admin-wrap" class="min-h-screen flex flex-col pl-16 lg:pl-64 transition-all duration-300">
    @include('layouts.admin.navbar')
    @include('layouts.admin.header')

    {{-- растягиваем контент на всю доступную высоту --}}
    <main class="flex-1 p-6">
      @include('layouts.partials.flash')
      @yield('content')
    </main>

    {{-- обычный футер (без fixed/absolute) --}}
    @include('layouts.admin.footer')
  </div>

  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
  @stack('scripts')

  <!-- Необязательно: авто-подгон левого отступа под ширину сайдбара -->
  <script>
    (function () {
      const sb = document.querySelector('aside');
      const wrap = document.getElementById('admin-wrap');
      function apply() {
        if (!sb || !wrap) return;
        const w = Math.round(sb.getBoundingClientRect().width);
        wrap.style.paddingLeft = w ? (w + 'px') : '';
      }
      if (window.ResizeObserver && sb) new ResizeObserver(apply).observe(sb);
      window.addEventListener('resize', apply, { passive: true });
      document.addEventListener('DOMContentLoaded', apply);
    })();
  </script>
</body>
</html>

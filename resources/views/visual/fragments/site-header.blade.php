@props(['user' => auth()->user()])

<header class="relative text-sm leading-tight z-10"
        style="font-family: var(--font-base, Figtree, system-ui, -apple-system, Segoe UI, Roboto, Ubuntu, Cantarell, 'Helvetica Neue', Arial, 'Noto Sans', 'Liberation Sans', sans-serif); color: var(--color-header-text, var(--colors-header-text, #111827));">
    {{-- 🖼️ Фон --}}
    <div class="absolute inset-0 z-[-10] opacity-10"
         style="background-image: url('{{ asset('images/fon.jpg') }}'); background-repeat: repeat; background-size: auto;">
    </div>

    {{-- 🔷 Основной контейнер --}}
    <div class="relative z-[999] backdrop-blur-md shadow border-b dark:border-gray-700"
         style="
            --_primary:      var(--color-primary, var(--colors-primary, #2563eb));
            --_border:       var(--color-border, var(--colors-border, #e5e7eb));
            --_bg-header:    var(--color-header, var(--colors-header, #ffffff));
            --_text-header:  var(--color-header-text, var(--colors-header-text, #111827));
            background-color: var(--_bg-header);
            color:            var(--_text-header);
            border-color:     var(--_border);
         ">

        {{-- 🔝 Верхняя панель --}}
        <div class="max-w-screen-xl mx-auto px-4 py-4 flex flex-col sm:flex-row items-center justify-between gap-4">

            {{-- 🔖 Логотип + описание --}}
            <div class="flex items-center gap-3">
                <a href="/"
                   class="flex items-center gap-2 text-2xl font-extrabold transition hover:opacity-90"
                   style="color: var(--_primary);">
                    <div class="text-white font-bold w-8 h-8 flex items-center justify-center rounded-md shadow-inner text-sm tracking-wide"
                         style="background-color: var(--_primary);">RU</div>
                    <span class="hidden sm:inline">CMS</span>
                </a>
                <span class="text-xs opacity-70 hidden sm:inline">Контент & Управление</span>
            </div>

            {{-- 🛒 / 👤 Навигация --}}
            @php
                use Modules\News\Models\News;
                $cart = session('cart', []);
                $cartCount = array_sum(array_column($cart, 'qty'));
                $hasProducts = News::where('template', 'products')->exists();
            @endphp

            <div class="flex flex-wrap justify-center sm:justify-end items-center gap-3 text-sm">
                {{-- Корзина --}}
                @if ($hasProducts)
                    <a href="{{ route('cart.index') }}" class="relative transition hover:opacity-80" title="Корзина" style="color: var(--_primary);">
                        <i class="fas fa-shopping-cart text-lg"></i>
                        <span id="cart-count"
                              class="absolute -top-2 -right-2 text-white text-xs w-5 h-5 rounded-full flex items-center justify-center {{ $cartCount == 0 ? 'hidden' : '' }}"
                              style="background-color: var(--color-accent, var(--colors-accent, #ef4444));">
                            {{ $cartCount }}
                        </span>
                    </a>
                @endif

                {{-- Профиль / Вход / Выход --}}
                @auth
                    <a href="{{ route('dashboard') }}" class="transition hover:opacity-80" title="Личный кабинет" style="color: var(--_primary);">
                        <i class="fas fa-user"></i> Кабинет
                    </a>
                    @if ($user->is_admin ?? false)
                        <a href="{{ url('/admin/modules') }}" class="transition hover:opacity-80" title="Панель администратора" style="color: var(--_primary);">
                            <i class="fas fa-cogs"></i> Админка
                        </a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="transition hover:opacity-80"
                                title="Выйти"
                                style="color: var(--color-danger, #dc2626);">
                            <i class="fas fa-sign-out-alt"></i> Выйти
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="transition hover:opacity-80" style="color: var(--_primary);">
                        <i class="fas fa-sign-in-alt"></i> Войти
                    </a>
                    <a href="{{ route('register') }}" class="transition hover:opacity-80" style="color: var(--_primary);">
                        <i class="fas fa-user-plus"></i> Регистрация
                    </a>
                @endauth
            </div>
        </div>

        {{-- 📍 Меню + поиск --}}
        <div class="border-t"
             style="background-color: var(--_bg-header); border-color: var(--_border);">
            <div class="max-w-screen-xl mx-auto px-4 py-3 flex flex-col md:flex-row items-center justify-between gap-4">

                {{-- Навигация --}}
                <nav class="flex flex-wrap justify-center md:justify-start items-center gap-4 text-sm font-medium">
                    @foreach ([['/', 'home', 'Главная'], ['/about', 'book', 'О нас'], ['/faq', 'question-circle', 'Вопросы'], ['/contacts', 'phone-alt', 'Контакты']] as [$url, $icon, $title])
                        @php $isActive = request()->is(ltrim($url, '/')); @endphp
                        <a href="{{ $url }}"
                           class="px-1 py-0.5 rounded transition hover:opacity-80 {{ $isActive ? 'font-semibold' : '' }}"
                           style="{{ $isActive ? 'color: var(--_primary);' : '' }}">
                            <i class="fas fa-{{ $icon }}"></i> {{ $title }}
                        </a>
                    @endforeach
                </nav>

                {{-- Поиск --}}
                <form method="GET" action="{{ route('frontend.search') }}" class="flex items-center gap-2 w-full md:w-auto">
                    <input type="text" name="q" value="{{ request('q') }}"
                           class="px-3 py-1.5 rounded-md shadow-sm text-sm w-full md:w-64 focus:outline-none focus:ring-2"
                           style="
                              border: 1px solid var(--_border);
                              background: var(--color-input-bg, #ffffff);
                              color: var(--_text-header);
                              box-shadow: none;
                              --tw-ring-color: var(--_primary);
                           "
                           placeholder="Поиск...">
                    <button type="submit" class="text-xl transition hover:opacity-80" title="Поиск"
                            style="color: var(--_primary);">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>
        </div>

        {{-- 🧩 Динамическое меню из модуля --}}
        @php
            $menu = Modules\Menu\Models\Menu::with([
                'items' => fn($q) => $q->whereNull('parent_id')->orderBy('order')->with('children.children'),
            ])->where('position', 'header')->where('active', true)->first();

            $icons = [
                'url' => '🔗',
                'page' => '<i class="fas fa-file-alt"></i>',
                'category' => '<i class="fas fa-tags"></i>',
            ];

            function renderMenuItem($item, $icons)
            {
                $link = match ($item->type) {
                    'url' => $item->url,
                    'page' => $item->linkedPage ? route('frontend.pages.show', ['slug' => $item->linkedPage->slug]) : '#',
                    'category' => url('/?category=' . $item->linked_id),
                    default => '#',
                };

                $icon = $icons[$item->type] ?? '<i class="fas fa-link"></i>';
                $hasChildren = $item->children->isNotEmpty();
                $toggleId = 'submenu-' . $item->id;

                $html  = '<div class="relative group inline-block">';
                $html .= '<div class="flex items-center gap-2">';

                if ($hasChildren) {
                    $html .= '<button type="button" class="toggle-btn text-xs opacity-70 hover:opacity-100 transition" data-target="'.$toggleId.'">▼</button>';
                }

                $html .= '<a href="'.e($link).'" class="text-sm px-3 py-1 whitespace-nowrap rounded transition hover:opacity-80" style="color: inherit;">';
                $html .= $icon.' '.e($item->title).'</a>';

                $html .= '</div>';

                if ($hasChildren) {
                    $html .= '<div id="'.$toggleId.'" class="absolute left-0 top-full mt-2 min-w-[12rem] hidden rounded shadow z-[1000] p-2"
                                   style="background: var(--_bg-header); border: 1px solid var(--_border);">';
                    foreach ($item->children as $child) {
                        $html .= renderMenuItem($child, $icons);
                    }
                    $html .= '</div>';
                }

                $html .= '</div>';
                return $html;
            }
        @endphp

        @if ($menu && $menu->items->count())
            <div class="border-t relative"
                 style="background: var(--_bg-header); border-color: var(--_border); z-index: 999;">
                <div class="max-w-screen-xl mx-auto px-4 py-3 flex flex-wrap gap-4">
                    <nav class="relative text-sm font-medium flex flex-wrap gap-4" style="color: var(--_text-header);">
                        @foreach ($menu->items as $item)
                            {!! renderMenuItem($item, $icons) !!}
                        @endforeach
                    </nav>
                </div>
            </div>
        @endif
    </div>
</header>

{{-- 📜 JS: подменю раскрытие --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.toggle-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const submenu = document.getElementById(this.dataset.target);
                if (submenu) {
                    submenu.classList.toggle('hidden');
                    this.innerHTML = submenu.classList.contains('hidden') ? '▼' : '▲';
                }
            });
        });
    });
</script>

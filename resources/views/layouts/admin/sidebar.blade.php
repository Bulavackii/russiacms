<aside
    x-data="{
        collapsed: false,
        init() {
            const saved = localStorage.getItem('admin_sidebar_collapsed');
            this.collapsed = saved !== null ? JSON.parse(saved) : (window.innerWidth < 1024);
            this.$watch('collapsed', v => {
                localStorage.setItem('admin_sidebar_collapsed', JSON.stringify(v));
                // мягкая подсказка для обёртки: ширина изменилась
                this.$nextTick(() => window.dispatchEvent(new Event('resize')));
            });
            window.addEventListener('resize', () => {
                // на очень узких экранах всегда держим свернутым
                if (window.innerWidth < 768) this.collapsed = true;
            }, { passive: true });
        }
    }"
    x-cloak
    :class="collapsed ? 'w-16' : 'w-72'"
    class="fixed top-0 left-0 h-screen bg-white dark:bg-gray-900 border-r border-gray-200 dark:border-gray-800 shadow-lg flex flex-col z-40 transition-[width] duration-300 ease-in-out"
>
    {{-- ── Верхняя панель --}}
    <div class="px-3 py-3 border-b border-gray-200 dark:border-gray-800 bg-gray-900">
        <div class="flex items-center justify-between gap-2">
            <a href="/admin" class="flex items-center gap-2 text-white">
                <span class="inline-flex w-8 h-8 rounded-lg bg-blue-600 items-center justify-center font-bold text-sm">RU</span>
                <span x-show="!collapsed" class="font-semibold tracking-tight">CMS · Панель управления</span>
            </a>
            <button
                @click="collapsed = !collapsed"
                :aria-expanded="(!collapsed).toString()"
                aria-label="Переключить меню"
                class="shrink-0 w-9 h-9 rounded-lg bg-white/10 hover:bg-white/20 text-white grid place-items-center transition"
                title="Свернуть/развернуть меню"
            >
                {{-- заменили «двойные стрелки» на нормальный chevron --}}
                <i class="fa-solid" :class="collapsed ? 'fa-chevron-right' : 'fa-chevron-left'"></i>
            </button>
        </div>
    </div>

    @php
        // Конфиг ссылок
        $contentLinks = [
            ['route'=>route('admin.menus.index'),      'route_name'=>'admin.menus.*',      'icon'=>'fa-bars',      'label'=>'Меню'],
            ['route'=>route('admin.news.index'),       'route_name'=>'admin.news.*',       'icon'=>'fa-newspaper', 'label'=>'Новости'],
            ['route'=>route('admin.pages.index'),      'route_name'=>'admin.pages.*',      'icon'=>'fa-file-alt',  'label'=>'Страницы'],
            ['route'=>route('admin.categories.index'), 'route_name'=>'admin.categories.*', 'icon'=>'fa-tags',      'label'=>'Категории'],
            ['route'=>route('admin.slideshow.index'),  'route_name'=>'admin.slideshow.*',  'icon'=>'fa-images',    'label'=>'Слайдшоу'],
            ['route'=>route('admin.files.index'),      'route_name'=>'admin.files.*',      'icon'=>'fa-folder',    'label'=>'Файлы'],
            ['route'=>route('admin.newsio.index'),     'route_name'=>'admin.newsio.*',     'icon'=>'fa-file-export','label'=>'Импорт/Экспорт'],
        ];

        $systemLinks = array_values(array_filter([
            ['url'=>'/admin/modules',                      'check'=>request()->is('admin/modules'),                  'icon'=>'fa-cubes',    'label'=>'Модули'],
            ['url'=>'/admin/users',                        'check'=>request()->is('admin/users'),                    'icon'=>'fa-users',    'label'=>'Пользователи'],
            ['url'=>'/admin/search',                       'check'=>request()->is('admin/search'),                   'icon'=>'fa-search',   'label'=>'Поиск'],
            ['url'=>route('admin.notifications.index'),    'check'=>request()->routeIs('admin.notifications.*'),     'icon'=>'fa-bell',     'label'=>'Уведомления'],
            Route::has('admin.visual.themes.index')
                ? ['url'=>route('admin.visual.themes.index'),     'check'=>request()->routeIs('admin.visual.themes.*'),    'icon'=>'fa-palette',  'label'=>'Темы'] : null,
            Route::has('admin.visual.fragments.index')
                ? ['url'=>route('admin.visual.fragments.index'),  'check'=>request()->routeIs('admin.visual.fragments.*'), 'icon'=>'fa-puzzle-piece','label'=>'Фрагменты'] : null,
        ]));

        $accessibilityLinks = [
            ['url'=>'/admin/accessibility', 'check'=>request()->is('admin/accessibility*'), 'icon'=>'fa-universal-access', 'label'=>'Спецвозможности'],
        ];

        $paymentLinks = [
            ['url'=>route('admin.payments.index'), 'check'=>request()->routeIs('admin.payments.*'), 'icon'=>'fa-credit-card', 'label'=>'Оплата'],
            ['url'=>route('admin.orders.index'),   'check'=>request()->routeIs('admin.orders.*'),   'icon'=>'fa-box',         'label'=>'Заказы'],
            ['url'=>route('admin.delivery.index'), 'check'=>request()->routeIs('admin.delivery.*'), 'icon'=>'fa-truck',       'label'=>'Доставка'],
        ];

        $base = 'flex items-center gap-3 px-3 py-2 rounded-md transition group';
        $active = 'bg-black text-white font-semibold shadow-md';
        $idle = 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-black dark:hover:text-white';
    @endphp

    {{-- ── Навигация --}}
    <nav class="flex-1 overflow-y-auto px-2 py-4 space-y-6 text-[15px] font-medium">
        {{-- Контент --}}
        <div>
            <p x-show="!collapsed" class="text-[11px] uppercase text-gray-400 dark:text-gray-500 font-semibold px-2 mb-1">Контент</p>
            @foreach ($contentLinks as $link)
                @php $isActive = request()->routeIs($link['route_name']); @endphp
                <a href="{{ $link['route'] }}"
                   class="{{ $base }} {{ $isActive ? $active : $idle }}"
                   aria-current="{{ $isActive ? 'page' : 'false' }}"
                   title="{{ $link['label'] }}">
                    <i class="fas {{ $link['icon'] }} w-5 text-center"></i>
                    <span x-show="!collapsed">{{ $link['label'] }}</span>
                </a>
            @endforeach
        </div>

        {{-- Система --}}
        <div>
            <p x-show="!collapsed" class="text-[11px] uppercase text-gray-400 dark:text-gray-500 font-semibold px-2 mb-1">Система</p>
            @foreach ($systemLinks as $link)
                <a href="{{ $link['url'] }}"
                   class="{{ $base }} {{ $link['check'] ? $active : $idle }}"
                   aria-current="{{ $link['check'] ? 'page' : 'false' }}"
                   title="{{ $link['label'] }}">
                    <i class="fas {{ $link['icon'] }} w-5 text-center"></i>
                    <span x-show="!collapsed">{{ $link['label'] }}</span>
                </a>
            @endforeach
        </div>

        {{-- Доступность --}}
        <div>
            <p x-show="!collapsed" class="text-[11px] uppercase text-gray-400 dark:text-gray-500 font-semibold px-2 mb-1">Доступность</p>
            @foreach ($accessibilityLinks as $link)
                <a href="{{ $link['url'] }}"
                   class="{{ $base }} {{ $link['check'] ? $active : $idle }}"
                   aria-current="{{ $link['check'] ? 'page' : 'false' }}"
                   title="{{ $link['label'] }}">
                    <i class="fas {{ $link['icon'] }} w-5 text-center"></i>
                    <span x-show="!collapsed">{{ $link['label'] }}</span>
                </a>
            @endforeach
        </div>

        {{-- Оплата --}}
        <div>
            <p x-show="!collapsed" class="text-[11px] uppercase text-gray-400 dark:text-gray-500 font-semibold px-2 mb-1">Оплата</p>
            @foreach ($paymentLinks as $link)
                <a href="{{ $link['url'] }}"
                   class="{{ $base }} {{ $link['check'] ? $active : $idle }}"
                   aria-current="{{ $link['check'] ? 'page' : 'false' }}"
                   title="{{ $link['label'] }}">
                    <i class="fas {{ $link['icon'] }} w-5 text-center"></i>
                    <span x-show="!collapsed">{{ $link['label'] }}</span>
                </a>
            @endforeach
        </div>
    </nav>

    {{-- Низ сайдбара --}}
    <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-800 text-xs text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-900">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i class="fa-regular fa-circle-question"></i>
                <span x-show="!collapsed">Версия CMS:</span>
            </div>
            <strong class="text-gray-900 dark:text-white">1.0</strong>
        </div>
    </div>
</aside>

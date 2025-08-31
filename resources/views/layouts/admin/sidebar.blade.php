<aside x-data="{ collapsed: window.innerWidth < 1024 }" x-init="collapsed = window.innerWidth < 1024;
window.addEventListener('resize', () => collapsed = window.innerWidth < 1024)" x-cloak x-bind:class="collapsed ? 'w-16' : 'w-64'"
    class="fixed top-0 left-0 h-screen bg-white dark:bg-gray-900 border-r border-gray-200 dark:border-gray-800 shadow-lg flex flex-col z-40 transition-all duration-300">

    {{-- 🔷 Верхняя панель --}}
    <div class="flex items-center px-4 py-4 border-b border-gray-200 dark:border-gray-800 bg-gray-900">
        <button @click="collapsed = !collapsed"
            class="flex items-center gap-3 text-white text-base font-semibold tracking-tight focus:outline-none w-full">
            <i :class="collapsed ? 'fas fa-angle-double-right' : 'fas fa-angle-double-left'" class="text-xl"></i>
            <span x-show="!collapsed" class="truncate">Панель управления</span>
        </button>
    </div>

    {{-- 📁 Навигация --}}
    <nav class="flex-1 overflow-y-auto px-2 py-4 space-y-6 text-[15px] font-medium">
        {{-- 📂 Контент --}}
        @php
            // активность определяем по именам роутов (routeIs)
            $contentLinks = [
                [
                    'route' => route('admin.menus.index'),
                    'route_name' => 'admin.menus.*',
                    'icon' => 'fa-bars',
                    'label' => 'Меню',
                ],
                [
                    'route' => route('admin.news.index'),
                    'route_name' => 'admin.news.*',
                    'icon' => 'fa-newspaper',
                    'label' => 'Новости',
                ],
                [
                    'route' => route('admin.pages.index'),
                    'route_name' => 'admin.pages.*',
                    'icon' => 'fa-file-alt',
                    'label' => 'Страницы',
                ],
                [
                    'route' => route('admin.categories.index'),
                    'route_name' => 'admin.categories.*',
                    'icon' => 'fa-tags',
                    'label' => 'Категории',
                ],
                [
                    'route' => route('admin.slideshow.index'),
                    'route_name' => 'admin.slideshow.*',
                    'icon' => 'fa-images',
                    'label' => 'Слайдшоу',
                ],
                [
                    'route' => route('admin.files.index'),
                    'route_name' => 'admin.files.*',
                    'icon' => 'fa-folder',
                    'label' => 'Файлы',
                ],
                // 🧩 Импорт/Экспорт новостей (NewsIO)
                [
                    'route' => route('admin.newsio.index'),
                    'route_name' => 'admin.newsio.*',
                    'icon' => 'fa-file-export',
                    'label' => 'Импорт/Экспорт',
                ],
            ];

            $baseLinkCls = 'flex items-center gap-3 px-3 py-2 rounded-md transition group';
            $activeLinkCls = 'bg-black text-white font-semibold shadow-md';
            $idleLinkCls =
                'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-black dark:hover:text-white';
        @endphp

        <div>
            <p x-show="!collapsed"
                class="text-[11px] uppercase text-gray-400 dark:text-gray-500 font-semibold px-2 mb-1">Контент</p>
            @foreach ($contentLinks as $link)
                @php $isActive = request()->routeIs($link['route_name']); @endphp
                <a href="{{ $link['route'] }}"
                    class="{{ $baseLinkCls }} {{ $isActive ? $activeLinkCls : $idleLinkCls }}">
                    <i class="fas {{ $link['icon'] }} w-5 text-center"></i>
                    <span x-show="!collapsed">{{ $link['label'] }}</span>
                </a>
            @endforeach
        </div>

        {{-- ⚙️ Система --}}
        @php
            $systemLinks = [
                [
                    'url' => '/admin/modules',
                    'check' => request()->is('admin/modules'),
                    'icon' => 'fa-cubes',
                    'label' => 'Модули',
                ],
                [
                    'url' => '/admin/users',
                    'check' => request()->is('admin/users'),
                    'icon' => 'fa-users',
                    'label' => 'Пользователи',
                ],
                [
                    'url' => '/admin/search',
                    'check' => request()->is('admin/search'),
                    'icon' => 'fa-search',
                    'label' => 'Поиск',
                ],
                [
                    'url' => route('admin.notifications.index'),
                    'check' => request()->routeIs('admin.notifications.*'),
                    'icon' => 'fa-bell',
                    'label' => 'Уведомления',
                ],
                // 🎨 Темы (Visual Themes)
                Route::has('admin.visual.themes.index')
                    ? [
                        'url' => route('admin.visual.themes.index'),
                        'check' => request()->routeIs('admin.visual.themes.*'),
                        'icon' => 'fa-palette',
                        'label' => 'Темы',
                    ]
                    : null,
                // 🧩 Фрагменты (Visual Fragments)
                Route::has('admin.visual.fragments.index')
                    ? [
                        'url' => route('admin.visual.fragments.index'),
                        'check' => request()->routeIs('admin.visual.fragments.*'),
                        'icon' => 'fa-puzzle-piece',
                        'label' => 'Фрагменты',
                    ]
                    : null,
            ];

            // убрать null, если роутов ещё нет
            $systemLinks = array_values(array_filter($systemLinks));
        @endphp


        <div>
            <p x-show="!collapsed"
                class="text-[11px] uppercase text-gray-400 dark:text-gray-500 font-semibold px-2 mb-1">Система</p>
            @foreach ($systemLinks as $link)
                <a href="{{ $link['url'] }}"
                    class="{{ $baseLinkCls }} {{ $link['check'] ? $activeLinkCls : $idleLinkCls }}">
                    <i class="fas {{ $link['icon'] }} w-5 text-center"></i>
                    <span x-show="!collapsed">{{ $link['label'] }}</span>
                </a>
            @endforeach
        </div>

        {{-- 🧩 Доступность --}}
        @php
            $accessibilityLinks = [
                [
                    'url' => '/admin/accessibility',
                    'check' => request()->is('admin/accessibility*'),
                    'icon' => 'fa-universal-access',
                    'label' => 'Спецвозможности',
                ],
            ];
        @endphp
        <div>
            <p x-show="!collapsed"
                class="text-[11px] uppercase text-gray-400 dark:text-gray-500 font-semibold px-2 mb-1">Доступность</p>
            @foreach ($accessibilityLinks as $link)
                <a href="{{ $link['url'] }}"
                    class="{{ $baseLinkCls }} {{ $link['check'] ? $activeLinkCls : $idleLinkCls }}">
                    <i class="fas {{ $link['icon'] }} w-5 text-center"></i>
                    <span x-show="!collapsed">{{ $link['label'] }}</span>
                </a>
            @endforeach
        </div>

        {{-- 💳 Оплата --}}
        @php
            $paymentLinks = [
                [
                    'url' => route('admin.payments.index'),
                    'check' => request()->routeIs('admin.payments.*'),
                    'icon' => 'fa-credit-card',
                    'label' => 'Оплата',
                ],
                [
                    'url' => route('admin.orders.index'),
                    'check' => request()->routeIs('admin.orders.*'),
                    'icon' => 'fa-box',
                    'label' => 'Заказы',
                ],
                [
                    'url' => route('admin.delivery.index'),
                    'check' => request()->routeIs('admin.delivery.*'),
                    'icon' => 'fa-truck',
                    'label' => 'Доставка',
                ],
            ];
        @endphp

        <div>
            <p x-show="!collapsed"
                class="text-[11px] uppercase text-gray-400 dark:text-gray-500 font-semibold px-2 mb-1">Оплата</p>
            @foreach ($paymentLinks as $link)
                <a href="{{ $link['url'] }}"
                    class="{{ $baseLinkCls }} {{ $link['check'] ? $activeLinkCls : $idleLinkCls }}">
                    <i class="fas {{ $link['icon'] }} w-5 text-center"></i>
                    <span x-show="!collapsed">{{ $link['label'] }}</span>
                </a>
            @endforeach
        </div>
    </nav>

    {{-- 💡 Совет дня --}}
    <div x-show="!collapsed"
        class="px-5 py-3 text-xs text-gray-500 dark:text-gray-400 italic bg-gray-50 dark:bg-gray-800 border-t border-b border-gray-200 dark:border-gray-700">
        @php
            $tips = [
                '🧠 Хорошая структура — залог масштабируемости.',
                '🔐 Никогда не игнорируй безопасность.',
                '⚙️ Меньше — лучше. Убирай лишнее.',
                '📊 Анализируй поведение пользователей.',
            ];
            $tip = $tips[array_rand($tips)];
        @endphp
        {{ $tip }}
    </div>

    {{-- 📌 Подвал --}}
    <div class="px-6 py-4 border-t text-xs text-gray-500 dark:text-gray-500 bg-white dark:bg-gray-900">
        <span x-show="!collapsed">Версия CMS:</span>
        <strong class="text-black dark:text-white">1.0</strong>
    </div>
</aside>

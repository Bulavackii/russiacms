<header
    x-data="{
        searchOpen: false,
        q: '',
        toggleTheme(){
            const html = document.documentElement;
            html.classList.toggle('dark');
            localStorage.setItem('theme', html.classList.contains('dark') ? 'dark' : 'light');
        }
    }"
    @keydown.window.prevent.ctrl.k="searchOpen = !searchOpen; $nextTick(()=> $refs.search?.focus())"
    class="sticky top-0 z-30 w-full bg-white/80 dark:bg-gray-900/80 backdrop-blur border-b shadow text-sm text-gray-700 dark:text-gray-300"
>
    <div class="max-w-screen-2xl mx-auto px-4 py-3 flex items-center gap-3">

        {{-- Хлебные крошки + бейдж окружения + «Создать» --}}
        @php
            $route = request()->route()?->getName() ?? '';
            $map = [
                'admin.menus.'              => 'Меню',
                'admin.news.'               => 'Новости',
                'admin.pages.'              => 'Страницы',
                'admin.categories.'         => 'Категории',
                'admin.slideshow.'          => 'Слайдшоу',
                'admin.files.'              => 'Файлы',
                'admin.search.'             => 'Поиск',
                'admin.notifications.'      => 'Уведомления',
                'admin.visual.themes.'      => 'Темы',
                'admin.visual.fragments.'   => 'Фрагменты',
                'admin.users.'              => 'Пользователи',
                'admin.payments.'           => 'Оплата',
                'admin.orders.'             => 'Заказы',
                'admin.delivery.'           => 'Доставка',
            ];
            $section = null;
            foreach ($map as $prefix => $label) {
                if (str_starts_with($route, $prefix)) { $section = $label; break; }
            }

            $env = app()->environment();
            $envCls = match ($env) {
                'production' => 'bg-emerald-600',
                'staging'    => 'bg-amber-500',
                'testing'    => 'bg-blue-600',
                default      => 'bg-rose-600',
            };
        @endphp

        <nav class="hidden md:flex items-center gap-2 text-[12px] text-gray-500 dark:text-gray-400">
            <a href="{{ url('/admin/news') }}" class="hover:text-gray-800 dark:hover:text-gray-200">
                <i class="fa-solid fa-gauge-high mr-1"></i> Панель
            </a>
            @if($section)
                <span class="text-gray-300">/</span>
                <span class="text-gray-800 dark:text-gray-200 font-medium">{{ $section }}</span>
            @endif>

            {{-- Бейдж окружения --}}
            <span class="ml-2 px-2 py-0.5 rounded-full text-[11px] text-white {{ $envCls }}">
                {{ strtoupper($env) }}
            </span>

            {{-- Быстро создать --}}
            <div x-data="{open:false}" class="relative">
                <button type="button" @click="open=!open"
                        class="ml-2 inline-flex items-center gap-1 px-2.5 py-1 rounded-md border border-gray-200 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-800">
                    <i class="fa-solid fa-plus"></i><span>Создать</span>
                </button>
                <div x-cloak x-show="open" @click.outside="open=false"
                     class="absolute left-0 mt-2 w-56 rounded-md border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 shadow-lg p-1 z-10">
                    @if(Route::has('admin.news.create'))
                        <a class="flex items-center gap-2 px-3 py-2 rounded hover:bg-gray-100 dark:hover:bg-gray-800"
                           href="{{ route('admin.news.create') }}"><i class="fa-solid fa-newspaper w-4 text-center"></i> Новость</a>
                    @endif
                    @if(Route::has('admin.pages.create'))
                        <a class="flex items-center gap-2 px-3 py-2 rounded hover:bg-gray-100 dark:hover:bg-gray-800"
                           href="{{ route('admin.pages.create') }}"><i class="fa-solid fa-file-lines w-4 text-center"></i> Страницу</a>
                    @endif
                    @if(Route::has('admin.categories.create'))
                        <a class="flex items-center gap-2 px-3 py-2 rounded hover:bg-gray-100 dark:hover:bg-gray-800"
                           href="{{ route('admin.categories.create') }}"><i class="fa-solid fa-tags w-4 text-center"></i> Категорию</a>
                    @endif
                    @if(Route::has('admin.slideshow.create'))
                        <a class="flex items-center gap-2 px-3 py-2 rounded hover:bg-gray-100 dark:hover:bg-gray-800"
                           href="{{ route('admin.slideshow.create') }}"><i class="fa-solid fa-images w-4 text-center"></i> Слайдшоу</a>
                    @endif
                    @if(Route::has('admin.visual.fragments.create'))
                        <a class="flex items-center gap-2 px-3 py-2 rounded hover:bg-gray-100 dark:hover:bg-gray-800"
                           href="{{ route('admin.visual.fragments.create') }}"><i class="fa-solid fa-puzzle-piece w-4 text-center"></i> Фрагмент</a>
                    @endif
                </div>
            </div>
        </nav>

        {{-- Центр: поиск (Ctrl+K) --}}
        <div class="ml-auto md:mx-auto flex-1 max-w-2xl">
            <form method="GET" action="{{ route('admin.search.index') }}"
                  :class="searchOpen ? '' : 'hidden md:block'"
                  class="relative">
                <input
                    x-ref="search"
                    type="text"
                    name="q"
                    x-model="q"
                    placeholder="Поиск… (Ctrl + K)"
                    class="w-full rounded-md border border-gray-300 dark:border-gray-700 bg-white/70 dark:bg-gray-800/70 px-3 py-2 pl-9 shadow-sm outline-none focus:ring-2 focus:ring-blue-500"
                />
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <button type="button"
                        @click="searchOpen = false"
                        class="md:hidden absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-700 dark:hover:text-gray-200">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </form>
        </div>

        {{-- Кнопка открытия поиска на мобилке --}}
        <button class="md:hidden ml-1 w-9 h-9 grid place-items-center rounded-lg border border-gray-200 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-800"
                @click="searchOpen = true"
                aria-label="Открыть поиск">
            <i class="fa-solid fa-magnifying-glass"></i>
        </button>

        {{-- Тогглер темы --}}
        <button
            class="ml-1 w-9 h-9 grid place-items-center rounded-lg border border-gray-200 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-800"
            @click="toggleTheme()"
            :title="document.documentElement.classList.contains('dark') ? 'Светлая тема' : 'Тёмная тема'"
            aria-label="Переключить тему"
        >
            <i class="fa-solid"
               :class="document.documentElement.classList.contains('dark') ? 'fa-sun' : 'fa-moon'"></i>
        </button>

        {{-- Иконки: уведомления / заказы / сообщения --}}
        @php
            $unread = 0; $newOrders = 0; $unreadMessages = 0;
            try { if (class_exists(\Modules\Notifications\Models\Notification::class)) $unread = \Modules\Notifications\Models\Notification::where('enabled',1)->count(); } catch (\Throwable $e) {}
            try { if (class_exists(\Modules\Payments\Models\Order::class))         $newOrders = \Modules\Payments\Models\Order::where('is_new',true)->count(); } catch (\Throwable $e) {}
            try { if (class_exists(\Modules\Messages\Models\Message::class))       $unreadMessages = \Modules\Messages\Models\Message::where('is_read',false)->count(); } catch (\Throwable $e) {}
            $badge = 'absolute -top-1.5 -right-2 min-w-[18px] px-1 py-0.5 leading-none text-[11px] text-white rounded-full shadow ring-1 ring-black/5 text-center';
            $btn   = 'relative w-9 h-9 grid place-items-center rounded-lg border border-gray-200 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-800 transition';
        @endphp

        <a href="{{ route('admin.notifications.index') }}" class="{{ $btn }}" title="Уведомления" aria-label="Уведомления">
            <i class="fa-solid fa-bell"></i>
            @if($unread>0)<span class="{{ $badge }} bg-red-500 animate-pulse">{{ $unread }}</span>@endif
        </a>

        <a href="{{ route('admin.orders.index') }}" class="{{ $btn }}" title="Новые заказы" aria-label="Новые заказы">
            <i class="fa-solid fa-box"></i>
            @if($newOrders>0)<span class="{{ $badge }} bg-green-600">{{ $newOrders }}</span>@endif
        </a>

        <a href="{{ route('admin.messages.index') }}" class="{{ $btn }}" title="Сообщения" aria-label="Сообщения">
            <i class="fa-solid fa-envelope"></i>
            @if($unreadMessages>0)<span class="{{ $badge }} bg-indigo-600">{{ $unreadMessages }}</span>@endif
        </a>

        {{-- Профиль --}}
        <div class="ml-1">
            <x-user-dropdown />
        </div>
    </div>

    <script>
        // Применяем сохранённую тему
        (function(){
            if (localStorage.getItem('theme') === 'dark') {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>
</header>

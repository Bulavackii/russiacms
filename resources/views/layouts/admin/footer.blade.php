<footer
    class="mt-auto border-t bg-white/95 dark:bg-gray-900/95 backdrop-blur text-sm text-gray-600 dark:text-gray-400"
>
    @php
        use Illuminate\Support\Facades\Schema;

        $env = app()->environment();
        $envCls = match ($env) {
            'production' => 'bg-emerald-600',
            'staging'    => 'bg-amber-500',
            'testing'    => 'bg-blue-600',
            default      => 'bg-rose-600',
        };

        // Небольшая сводка (всё в try/catch, чтобы ничего не «ронять»)
        $stats = [];
        try { if (class_exists(\Modules\System\Models\Module::class) && Schema::hasTable('modules')) {
            $stats['modules'] = \Modules\System\Models\Module::where('active', 1)->count();
        }} catch (\Throwable $e) {}

        try { if (class_exists(\App\Models\User::class) && Schema::hasTable('users')) {
            $stats['users'] = \App\Models\User::count();
        }} catch (\Throwable $e) {}

        try { if (class_exists(\Modules\News\Models\News::class) && Schema::hasTable('news')) {
            $stats['news'] = \Modules\News\Models\News::count();
        }} catch (\Throwable $e) {}

        $drivers = [
            'DB'    => config('database.default'),
            'Cache' => config('cache.default'),
            'Queue' => config('queue.default'),
        ];
    @endphp

    <div class="max-w-screen-2xl mx-auto px-4 py-6 grid gap-8 md:grid-cols-3 items-center">

        {{-- 1) Системная сводка (вместо логотипа) --}}
        <section class="space-y-2 text-center md:text-left">
            <div class="flex flex-wrap items-center justify-center md:justify-start gap-2">
                <span class="px-2 py-0.5 rounded-full text-[11px] text-white {{ $envCls }}">{{ strtoupper($env) }}</span>
                <span class="text-xs">v1.0.0</span>
                <span class="text-xs">· PHP {{ PHP_VERSION }}</span>
                <span class="text-xs">· Laravel {{ app()->version() }}</span>
            </div>

            <ul class="grid gap-x-6 gap-y-1 sm:grid-cols-2 text-xs justify-items-center md:justify-items-start">
                <li class="flex items-center gap-2">
                    <i class="fa-solid fa-cubes w-4 text-center"></i>
                    Модулей: <strong>{{ $stats['modules'] ?? '—' }}</strong>
                </li>
                <li class="flex items-center gap-2">
                    <i class="fa-solid fa-users w-4 text-center"></i>
                    Пользователей: <strong>{{ $stats['users'] ?? '—' }}</strong>
                </li>
                <li class="flex items-center gap-2">
                    <i class="fa-regular fa-newspaper w-4 text-center"></i>
                    Новостей: <strong>{{ $stats['news'] ?? '—' }}</strong>
                </li>
                <li class="flex items-center gap-2">
                    <i class="fa-solid fa-database w-4 text-center"></i>
                    {{ $drivers['DB'] ?? 'db' }}
                </li>
            </ul>

            <div class="text-xs text-gray-500" id="admin-footer-time">
                Обновлено: <span class="font-mono">—</span>
            </div>
        </section>

        {{-- 2) Быстрые ссылки + подсказка по хоткеям --}}
        <section class="text-center">
            <div
                class="inline-flex items-center gap-2 px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-white/70 dark:bg-gray-800/70 shadow-sm">
                <i class="fa-regular fa-keyboard"></i>
                <span class="text-xs">Горячие клавиши:</span>
                <kbd class="px-1.5 py-0.5 rounded border text-[11px] bg-gray-50 dark:bg-gray-900">Ctrl</kbd>
                <span class="text-[11px]">+</span>
                <kbd class="px-1.5 py-0.5 rounded border text-[11px] bg-gray-50 dark:bg-gray-900">K</kbd>
                <span class="text-xs">— поиск</span>
            </div>

            <nav class="mt-3 flex flex-wrap justify-center gap-4">
                <a href="/terms" class="hover:text-blue-600 transition inline-flex items-center gap-2">
                    <i class="fa-solid fa-file-contract w-4 text-center"></i> Условия использования
                </a>
                <a href="https://github.com/Bulavackii/Ru-CMS" target="_blank"
                   class="hover:text-blue-600 transition inline-flex items-center gap-2">
                    <i class="fa-brands fa-github w-4 text-center"></i> GitHub проекта
                </a>
                <a href="/admin/help" class="hover:text-blue-600 transition inline-flex items-center gap-2">
                    <i class="fa-solid fa-circle-question w-4 text-center"></i> Поддержка и помощь
                </a>
            </nav>
        </section>

        {{-- 3) Соцсети + «Наверх» --}}
        <section class="flex flex-col items-center md:items-end gap-2">
            <div class="flex items-center gap-3 text-xl">
                <a href="https://t.me/ru_cms" target="_blank" aria-label="Telegram"
                   class="hover:text-sky-500 transition"><i class="fab fa-telegram-plane"></i></a>
                <a href="https://vk.com/ru_cms" target="_blank" aria-label="VK"
                   class="hover:text-blue-700 transition"><i class="fab fa-vk"></i></a>
                <a href="https://github.com/Bulavackii/Ru-CMS" target="_blank" aria-label="GitHub"
                   class="hover:text-gray-800 dark:hover:text-gray-200 transition"><i class="fab fa-github"></i></a>
            </div>

            <button type="button"
                    onclick="window.scrollTo({top:0,behavior:'smooth'})"
                    class="inline-flex items-center gap-2 px-3 py-1.5 rounded-md border border-gray-200 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-800 transition"
                    title="Наверх">
                <i class="fa-solid fa-arrow-up"></i>
                <span class="text-xs">Вверх</span>
            </button>
        </section>
    </div>

    {{-- Время обновления (локально) --}}
    <script>
        (function () {
            const span = document.querySelector('#admin-footer-time span');
            if (span) {
                const now = new Date();
                try {
                    span.textContent = now.toLocaleString('ru-RU', { dateStyle: 'medium', timeStyle: 'short' });
                } catch (_) {
                    span.textContent = now.toISOString().slice(0,16).replace('T',' ');
                }
            }
            // Если в системе где-то уже сохраняют тему в localStorage — уважаем её,
            // но сам переключатель тем из футера убран по просьбе.
            if (localStorage.getItem('theme') === 'dark') {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>
</footer>

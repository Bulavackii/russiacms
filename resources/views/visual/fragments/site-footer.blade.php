<footer class="relative text-sm mt-10"
        style="font-family: var(--font-base, Figtree, system-ui, -apple-system, Segoe UI, Roboto, Ubuntu, Cantarell, 'Helvetica Neue', Arial, 'Noto Sans', 'Liberation Sans', sans-serif); color: var(--color-footer-text, var(--colors-footer-text, #6b7280));">
    {{-- 🖼️ Фон-узор --}}
    <div class="absolute inset-0 z-0 opacity-10 pointer-events-none"
         style="background-image: url('{{ asset('images/fon.jpg') }}'); background-repeat: repeat; background-size: auto;">
    </div>

    {{-- 🌫️ Основной контейнер --}}
    <div class="relative z-10 backdrop-blur-md border-t shadow-inner"
         style="
            --_primary:     var(--color-primary, var(--colors-primary, #2563eb));
            --_border:      var(--color-border, var(--colors-border, #e5e7eb));
            --_bg-footer:   var(--color-footer, var(--colors-footer, #ffffff));
            --_text-footer: var(--color-footer-text, var(--colors-footer-text, #6b7280));
            background-color: var(--_bg-footer);
            color:            var(--_text-footer);
            border-color:     var(--_border);
         ">

        {{-- 🔝 Верхняя часть --}}
        <div class="max-w-screen-xl mx-auto px-4 py-10 grid grid-cols-1 md:grid-cols-3 gap-10">

            {{-- 🛠️ Инфо о CMS --}}
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <div class="text-white font-bold w-8 h-8 flex items-center justify-center rounded-md shadow-inner text-sm tracking-wide"
                         style="background-color: var(--_primary);">RU</div>
                    <h3 class="text-lg font-bold" style="color: var(--color-footer-heading, #111827);">CMS</h3>
                </div>
                <p class="text-xs opacity-70">Разработчик — Булавацкий Д.О.</p>
                <p class="text-xs opacity-60 mt-2">Laravel {{ app()->version() }}</p>
            </div>

            {{-- 🌐 Навигация --}}
            <div>
                <h3 class="text-base font-semibold mb-4 text-center" style="color: var(--color-footer-heading, #111827);">Навигация</h3>
                <div class="grid grid-cols-2 gap-2 text-sm justify-items-center">
                    @php
                        $navLinks = [
                            ['url' => '/terms', 'icon' => 'file-contract', 'text' => 'Соглашение'],
                            ['url' => '/partnership', 'icon' => 'handshake', 'text' => 'Сотрудничество'],
                            ['url' => '/developers', 'icon' => 'code', 'text' => 'Разработчикам'],
                            ['url' => '/concept', 'icon' => 'lightbulb', 'text' => 'Концепция'],
                            ['url' => '/sitemap', 'icon' => 'sitemap', 'text' => 'Карта сайта'],
                            ['url' => '/donate', 'icon' => 'donate', 'text' => 'Пожертвовать'],
                        ];
                    @endphp
                    @foreach ($navLinks as $link)
                        <a href="{{ url($link['url']) }}"
                           class="flex items-center gap-1 px-2 py-1 rounded transition hover:opacity-80 text-[13px]"
                           style="color: inherit;">
                            <i class="fas fa-{{ $link['icon'] }}"></i> {{ $link['text'] }}
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- 🌍 Социальные сети --}}
            <div>
                <h3 class="text-base font-semibold mb-4 text-center" style="color: var(--color-footer-heading, #111827);">Мы в соцсетях</h3>
                <div class="flex justify-center flex-wrap gap-3 text-[18px]">
                    <a href="https://vk.com/ru_cms" target="_blank" class="transition transform hover:scale-110" aria-label="VK" style="color: var(--_primary);">
                        <i class="fab fa-vk"></i>
                    </a>
                    <a href="https://t.me/ru_cms" target="_blank" class="transition transform hover:scale-110" aria-label="Telegram" style="color: var(--_primary);">
                        <i class="fab fa-telegram-plane"></i>
                    </a>
                    <a href="https://wa.me/79856204400" target="_blank" class="transition transform hover:scale-110" aria-label="WhatsApp" style="color: var(--color-success, #16a34a);">
                        <i class="fab fa-whatsapp"></i>
                    </a>
                    <a href="https://github.com/Bulavackii/Ru-CMS" class="transition transform hover:scale-110" aria-label="GitHub" style="color: var(--color-muted, #6b7280);">
                        <i class="fab fa-github"></i>
                    </a>
                    <a href="#" class="transition transform hover:scale-110" aria-label="YouTube" style="color: var(--color-danger, #dc2626);">
                        <i class="fab fa-youtube"></i>
                    </a>
                </div>
            </div>
        </div>

        {{-- 🔻 Нижняя часть --}}
        <div class="px-4 py-6 backdrop-blur-sm border-t"
             style="background-color: var(--_bg-footer); border-color: var(--_border);">
            <div class="max-w-screen-xl mx-auto flex flex-col md:flex-row justify-between items-center gap-6 text-xs">

                {{-- ✉️ Подписка --}}
                <form method="POST" action="#"
                      class="flex flex-col md:flex-row md:items-center gap-3 text-sm w-full md:w-auto">
                    @csrf
                    <input type="email" name="email" placeholder="Ваш email"
                           class="px-4 py-2 rounded-md focus:outline-none focus:ring-2"
                           style="border: 1px solid var(--_border); background: #fff; color: #111827; --tw-ring-color: var(--_primary);"
                           required>
                    <button type="submit"
                            class="text-white px-4 py-2 rounded-md transition font-semibold hover:opacity-90"
                            style="background-color: var(--_primary);">
                        Подписаться
                    </button>
                </form>

                {{-- 🌍 Язык и копирайт --}}
                <div class="flex flex-col md:flex-row md:items-center gap-4">
                    <form method="POST" action="#" class="flex items-center gap-2">
                        @csrf
                        <label for="lang" class="font-medium" style="color: var(--color-footer-heading, #111827);">Язык:</label>
                        <select name="locale" id="lang"
                                class="w-40 px-4 py-2 rounded-md shadow-sm focus:outline-none focus:ring-2"
                                style="border: 1px solid var(--_border); background: #fff; color: #111827; --tw-ring-color: var(--_primary);">
                            <option value="ru" selected>🇷🇺 Русский</option>
                            <option value="en">🇬🇧 English</option>
                        </select>
                    </form>
                    <span class="text-center md:text-left">© {{ date('Y') }} Все права защищены</span>
                </div>
            </div>
        </div>
    </div>

    {{-- 🔝 Кнопка "Наверх" --}}
    <button id="backToTopBtn"
            onclick="window.scrollTo({ top: 0, behavior: 'smooth' })"
            class="fixed bottom-6 right-6 z-50 p-3 rounded-full shadow-md text-white transition transform hover:scale-105 hidden"
            title="Наверх"
            style="background-color: var(--_primary);">
        <i class="fas fa-arrow-up"></i>
    </button>
</footer>

{{-- 🔧 JS + Анимация --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const btn = document.getElementById('backToTopBtn');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 200) {
                btn.classList.remove('hidden');
                btn.classList.add('animate-fade-in');
            } else {
                btn.classList.add('hidden');
            }
        });
    });
</script>

<style>
    @keyframes fade-in {
        from { opacity: 0; transform: translateY(20px); }
        to   { opacity: 1; transform: translateY(0);    }
    }
    .animate-fade-in { animation: fade-in 0.4s ease-out; }
</style>

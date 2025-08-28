@extends('layouts.frontend')

@section('title', 'Концепция Ru CMS')

@section('content')
<section class="max-w-4xl mx-auto bg-white border border-gray-300 rounded-2xl shadow-xl p-8 md:p-12 text-[15px] leading-relaxed text-gray-800 space-y-8">
    <h1 class="text-3xl font-extrabold text-blue-800 text-center">📄 Концепция Ru-CMS</h1>

    {{-- 💡 Миссия --}}
    <div class="space-y-4">
        <h2 class="text-xl font-semibold text-blue-700">🎯 Наша миссия</h2>
        <p>
            Мы создали <strong>Ru CMS</strong>, чтобы каждый — от индивидуального предпринимателя до государственной организации — мог легко запускать сайты, управлять контентом и масштабировать инфраструктуру <span class="text-blue-700 font-medium">без сложных фреймворков и чужих ограничений</span>.
        </p>
    </div>

    {{-- 🚀 Основные принципы --}}
    <div class="space-y-4">
        <h2 class="text-xl font-semibold text-blue-700">🚀 Принципы разработки</h2>
        <ul class="list-disc pl-6 space-y-2">
            <li>🔓 <strong>Открытость</strong> — исходный код доступен всем, вы можете изменять, адаптировать и развивать платформу</li>
            <li>⚡ <strong>Производительность</strong> — быстрая работа даже на минимальных конфигурациях хостинга</li>
            <li>🧩 <strong>Модульность</strong> — система устроена как конструктор, из которого можно собрать всё: от лендинга до каталога продукции</li>
            <li>👥 <strong>Прозрачность</strong> — никакой слежки, сбора скрытых данных и обязательных внешних API</li>
            <li>🛠️ <strong>Простота доработки</strong> — понятная архитектура на Laravel с чётким разделением модулей</li>
        </ul>
    </div>

    {{-- 🌍 Модель распространения --}}
    <div class="space-y-4">
        <h2 class="text-xl font-semibold text-blue-700">🌍 Модель распространения</h2>
        <p>
            Ru-CMS распространяется по <strong>условно-бесплатной модели</strong>:
        </p>
        <ul class="list-disc pl-6 space-y-1">
            <li>✅ <strong>Базовая версия</strong> полностью бесплатна — вы можете скачать, установить и использовать её без ограничений</li>
            <li>🌟 <strong>Премиум-модули</strong> (опционально) включают: SEO-анализ, аналитика, отчёты, платёжные системы и т.д.</li>
            <li>🤝 <strong>Пожертвования и поддержка</strong> — вы можете внести вклад в развитие проекта через <a href="{{ url('/donate') }}" class="text-blue-600 hover:underline">страницу пожертвований</a></li>
            <li>🧠 <strong>Поддержка и консалтинг</strong> — для команд, организаций и госструктур мы предоставляем помощь по установке, адаптации и сопровождению</li>
        </ul>
    </div>

    {{-- 💼 Кому подходит --}}
    <div class="space-y-4">
        <h2 class="text-xl font-semibold text-blue-700">💼 Для кого мы создаём Ru-CMS</h2>
        <ul class="list-disc pl-6 space-y-2">
            <li>📦 Малый и средний бизнес — сайт компании, каталог товаров, форма обратной связи</li>
            <li>🏛️ Бюджетные и муниципальные организации — информационные порталы и внутренние системы</li>
            <li>📸 Креативные студии — управление контентом, портфолио, лендинги</li>
            <li>👨‍💻 Разработчики — API, гибкие шаблоны, кастомизация без боли</li>
        </ul>
    </div>

    {{-- 💬 Поддержка сообщества --}}
    <div class="space-y-4">
        <h2 class="text-xl font-semibold text-blue-700">💬 Поддержка и сообщество</h2>
        <p>
            Мы верим в силу открытого сообщества. Подключайтесь к нам:
        </p>
        <ul class="list-disc pl-6 space-y-1">
            <li>📢 Telegram: <a href="https://t.me/DBulav" target="_blank" class="text-blue-600 hover:underline">@DBulav</a></li>
            <li>🌐 GitHub: <a href="#" class="text-blue-600 hover:underline">Скоро будет</a></li>
        </ul>
    </div>

    {{-- 🔙 Назад --}}
    <div class="text-center mt-10">
        <a href="/" class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg shadow hover:bg-blue-700 transition">
            ← На главную
        </a>
    </div>
</section>
@endsection

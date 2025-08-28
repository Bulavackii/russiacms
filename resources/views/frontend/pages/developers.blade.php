@extends('layouts.frontend')

@section('title', 'Разработчикам')

@section('content')
<section class="max-w-4xl mx-auto bg-white border border-gray-300 rounded-2xl shadow-xl p-8 md:p-12 text-[15px] leading-relaxed text-gray-800 space-y-8">
    <h1 class="text-3xl font-extrabold text-blue-800 text-center">💻 Разработчикам</h1>

    {{-- 💬 Введение --}}
    <p>
        <strong>Ru CMS</strong> — это CMS нового поколения, построенная на Laravel 12 и PHP 8.4, с чистой архитектурой HMVC и мощной системой модулей. Этот раздел предназначен для разработчиков, желающих интегрировать, кастомизировать и расширять систему под собственные задачи.
    </p>

    {{-- 📦 Технологии --}}
    <div>
        <h2 class="text-xl font-semibold text-blue-700 mb-2">🧠 Стек технологий</h2>
        <ul class="list-disc pl-6 space-y-1">
            <li><strong>Laravel 12.10</strong> — надёжный и гибкий backend</li>
            <li><strong>Blade</strong> + Tailwind CSS — для адаптивного и читаемого фронтенда</li>
            <li><strong>Modular HMVC</strong> — каждая сущность изолирована по принципу "модуль как плагин"</li>
            <li><strong>TinyMCE 7</strong> — мощный редактор для контента с загрузкой изображений</li>
            <li><strong>JWT + bcrypt</strong> — безопасная аутентификация</li>
            <li><strong>API Ready</strong> — можно подключать мобильные приложения, CRM, ERP и др.</li>
        </ul>
    </div>

    {{-- 🛠 Модули и расширения --}}
    <div>
        <h2 class="text-xl font-semibold text-blue-700 mb-2">🔧 Создание модулей</h2>
        <p>Модули размещаются в <code>/modules/</code> и полностью автономны. Структура похожа на Laravel-пакет:</p>
        <ul class="list-disc pl-6 space-y-1">
            <li>Контроллеры, маршруты, миграции, модели, представления — в пределах одного модуля</li>
            <li>Манифест <code>module.json</code> описывает название, статус и версию</li>
            <li>Регистрация происходит через <code>ModuleServiceProvider</code></li>
        </ul>
    </div>

    {{-- 📁 Примеры --}}
    <div>
        <h2 class="text-xl font-semibold text-blue-700 mb-2">📁 Примеры модулей</h2>
        <ul class="list-disc pl-6 space-y-1">
            <li><strong>News</strong> — управление новостями с шаблонами и категориями</li>
            <li><strong>Search</strong> — глобальный поиск по сайту</li>
            <li><strong>Menu</strong> — drag & drop редактор меню</li>
            <li><strong>Files</strong> — менеджер медиафайлов</li>
        </ul>
    </div>

    {{-- 🤝 Подключение и вклад --}}
    <div>
        <h2 class="text-xl font-semibold text-blue-700 mb-2">🤝 Участие в разработке</h2>
        <p>
            Мы открыты к предложениям, pull-реквестам и совместной разработке. Репозиторий доступен на GitHub. Вы можете создавать issue, расширять модули или предлагать свои.
        </p>
        <div class="text-center mt-4">
            <a href="#" target="_blank" class="inline-block bg-gray-800 text-white px-5 py-2 rounded-lg hover:bg-black transition">
                <i class="fab fa-github mr-2"></i> Перейти в GitHub
            </a>
        </div>
    </div>

    {{-- 📚 Документация --}}
    <div>
        <h2 class="text-xl font-semibold text-blue-700 mb-2">📚 Документация</h2>
        <p>
            Мы ведём базу знаний с примерами кода, архитектурой и часто задаваемыми вопросами. Документация размещена внутри CMS и доступна для просмотра в панели администратора.
        </p>
    </div>

    <p class="text-center text-sm text-gray-500">Последнее обновление: {{ date('d.m.Y') }}</p>

    <div class="text-center mt-8">
        <a href="/" class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg shadow hover:bg-blue-700 transition">
            ← На главную
        </a>
    </div>
</section>
@endsection

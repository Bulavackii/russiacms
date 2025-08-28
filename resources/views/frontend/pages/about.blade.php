@extends('layouts.frontend')

@section('title', 'О Ru-CMS')

@section('content')
    <div class="max-w-4xl mx-auto bg-white p-8 rounded-2xl shadow-lg border border-gray-200 space-y-8">
        <h1 class="text-4xl font-extrabold text-blue-800 text-center">🛡️ Что такое Ru-CMS?</h1>

        <div class="text-gray-700 text-[15px] leading-relaxed space-y-6">
            {{-- 💬 Введение --}}
            <p>
                <strong>Ru-CMS</strong> — это <span class="text-blue-700 font-semibold">безопасная, лёгкая и модульная система управления сайтом</span>, созданная с нуля без привязки к WordPress или Laravel Breeze. Она подходит для бизнеса, разработчиков, дизайнеров и всех, кто хочет <strong>контролировать контент и функционал сайта</strong> без лишней сложности.
            </p>

            {{-- 🚀 Преимущества --}}
            <h2 class="text-xl font-bold text-blue-700 mt-4">🚀 Почему выбирают Ru-CMS:</h2>
            <ul class="list-disc pl-6 space-y-2 text-sm">
                <li>🧩 <strong>Модульная архитектура</strong> — включайте, отключайте, архивируйте и создавайте модули без перезагрузки системы</li>
                <li>✍️ <strong>Встроенный визуальный редактор</strong> TinyMCE с загрузкой изображений, HTML-контента и переключением языков</li>
                <li>🎨 <strong>Гибкие шаблоны</strong> — реализуйте страницы типа "Товары", "Отзывы", "Контакты", "FAQ", "Слайдшоу", "Галерея" и др.</li>
                <li>🔐 <strong>JWT, bcrypt, роли и доступы</strong> — надёжная аутентификация и разграничение пользователей</li>
                <li>📦 <strong>API-готовность</strong> — возможность подключать внешние сервисы и приложения</li>
                <li>🗺️ <strong>Автогенерация sitemap.xml и robots.txt</strong> — с учётом шаблонов, SEO и фильтрации</li>
                <li>🔎 <strong>Поиск, уведомления, файловый менеджер, личный кабинет</strong> и многое другое</li>
                <li>📈 <strong>Отдельная админка</strong> — адаптирована под мобильные устройства и поддерживает тёмную тему</li>
            </ul>

            {{-- 👥 Целевая аудитория --}}
            <h2 class="text-xl font-bold text-blue-700 mt-4">🎯 Кому подойдёт Ru-CMS:</h2>
            <ul class="list-disc pl-6 text-sm space-y-2">
                <li>👨‍💼 Бизнесу — для создания корпоративных сайтов и витрин товаров</li>
                <li>🎨 Дизайнерам и студиям — для кастомных шаблонов и лендингов</li>
                <li>👩‍💻 Разработчикам — для гибкой архитектуры и самостоятельного контроля</li>
                <li>📚 Образовательным проектам и сообществам — благодаря модулям новостей, сообщений, галерей и личных кабинетов</li>
            </ul>

            {{-- 🔧 Open Source --}}
            <h2 class="text-xl font-bold text-blue-700 mt-4">💻 Open Source и свобода изменений</h2>
            <p>
                Проект распространяется свободно и открыт для модификаций. Вы можете использовать Ru-CMS как есть, или развивать под свои задачи. Весь исходный код доступен на GitHub.
            </p>

            <div class="text-center mt-4">
                <a href="#" target="_blank" class="inline-flex items-center bg-blue-600 text-white px-5 py-2 rounded-lg shadow hover:bg-blue-700 transition-all">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24"><path d="M12 .5C5.9.5.5 5.9.5 12c0 5.1 3.3 9.4 7.9 10.9.6.1.8-.2.8-.5v-2.1c-3.2.7-3.8-1.6-3.8-1.6-.5-1.3-1.2-1.6-1.2-1.6-1-.6.1-.6.1-.6 1.1.1 1.6 1.1 1.6 1.1.9 1.6 2.5 1.1 3.1.8.1-.6.3-1.1.6-1.3-2.5-.3-5.1-1.3-5.1-5.8 0-1.3.5-2.3 1.1-3.2-.1-.3-.5-1.6.1-3.3 0 0 .9-.3 3.3 1.2a11.4 11.4 0 016 0c2.4-1.5 3.3-1.2 3.3-1.2.6 1.7.2 3 .1 3.3.7.9 1.1 1.9 1.1 3.2 0 4.5-2.6 5.5-5.1 5.8.4.3.7.9.7 1.9v2.8c0 .3.2.6.8.5a11.5 11.5 0 007.9-10.9c0-6.1-5.4-11.5-12.4-11.5z"/></svg>
                    Перейти на GitHub
                </a>
            </div>

            <p class="font-semibold text-center text-green-700 mt-6">
                🔓 Ru-CMS — открытая, быстрая и безопасная система управления, которая работает на вас.
            </p>
        </div>

        {{-- 🔙 Кнопка назад --}}
        <div class="mt-10 text-center">
            <a href="/" class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg shadow transition">
                ← Назад
            </a>
        </div>
    </div>
@endsection

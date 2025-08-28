@extends('layouts.frontend')

@section('title', 'Политика конфиденциальности')

@section('content')
<section class="max-w-4xl mx-auto bg-white border border-gray-300 rounded-2xl shadow-xl p-8 md:p-12 text-[15px] leading-relaxed text-gray-800 space-y-8">
    <h1 class="text-3xl font-extrabold text-blue-800 text-center">🔐 Политика конфиденциальности</h1>

    <p>
        Настоящая Политика конфиденциальности описывает, как Ru-CMS собирает, использует и защищает ваши персональные данные при использовании сайта.
    </p>

    <div class="space-y-4">
        <h2 class="text-xl font-semibold text-blue-700">1. Какие данные мы собираем</h2>
        <ul class="list-disc pl-6 space-y-1">
            <li><strong>Технические данные</strong>: IP-адрес, информация о браузере, устройстве, операционной системе и посещённых страницах.</li>
            <li><strong>Файлы cookie</strong>: используются для хранения пользовательских предпочтений, состояния входа, аналитики и безопасности.</li>
            <li><strong>Контактные данные</strong>: имя, адрес электронной почты, номер телефона — только при заполнении форм на сайте.</li>
            <li><strong>Платёжные данные</strong>: не хранятся на сайте, а обрабатываются через защищённые платёжные шлюзы (если используется функционал заказов).</li>
        </ul>
    </div>

    <div class="space-y-4">
        <h2 class="text-xl font-semibold text-blue-700">2. Зачем мы собираем данные</h2>
        <ul class="list-disc pl-6 space-y-1">
            <li>Для предоставления доступа к функционалу сайта</li>
            <li>Для улучшения работы сайта и пользовательского опыта</li>
            <li>Для поддержки обратной связи и связи с пользователем</li>
            <li>Для аналитики и статистики</li>
            <li>Для обеспечения безопасности и предотвращения мошенничества</li>
        </ul>
    </div>

    <div class="space-y-4">
        <h2 class="text-xl font-semibold text-blue-700">3. Использование файлов cookie</h2>
        <p>
            Мы используем cookie для аутентификации пользователей, сохранения их настроек, аналитики посещений и персонализации. Продолжая использовать сайт, вы соглашаетесь на использование cookie. Подробнее см. <a href="{{ url('/privacy') }}" class="text-blue-600 hover:underline">Политику конфиденциальности</a>.
        </p>
    </div>

    <div class="space-y-4">
        <h2 class="text-xl font-semibold text-blue-700">4. Кто имеет доступ к данным</h2>
        <p>
            Доступ к данным имеют только администраторы Ru-CMS и технические специалисты, обслуживающие сервер. Данные не передаются третьим лицам без согласия пользователя, за исключением случаев, предусмотренных законом.
        </p>
    </div>

    <div class="space-y-4">
        <h2 class="text-xl font-semibold text-blue-700">5. Хранение данных</h2>
        <p>
            Данные хранятся только в необходимый срок для реализации целей их обработки и затем удаляются. Вы можете запросить удаление ваших персональных данных, отправив запрос через форму на странице <a href="{{ url('/contacts') }}" class="text-blue-600 hover:underline">Контакты</a>.
        </p>
    </div>

    <div class="space-y-4">
        <h2 class="text-xl font-semibold text-blue-700">6. Ваши права</h2>
        <p>Вы имеете право:</p>
        <ul class="list-disc pl-6 space-y-1">
            <li>знать, какие данные о вас хранятся;</li>
            <li>требовать исправления или удаления данных;</li>
            <li>отозвать согласие на обработку данных.</li>
        </ul>
    </div>

    <div class="space-y-4">
        <h2 class="text-xl font-semibold text-blue-700">7. Изменения в политике</h2>
        <p>
            Мы оставляем за собой право вносить изменения в настоящую политику. Все изменения будут опубликованы на этой странице.
        </p>
    </div>

    <p class="text-center text-gray-500 text-sm mt-8">
        Обновлено: {{ date('d.m.Y') }}
    </p>

    <div class="text-center mt-10">
        <a href="{{ url('/') }}"
           class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg shadow hover:bg-blue-700 transition">
            ← На главную
        </a>
    </div>
</section>
@endsection

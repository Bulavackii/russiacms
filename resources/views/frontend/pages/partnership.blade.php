@extends('layouts.frontend')

@section('title', 'Сотрудничество')

@section('content')
<section class="max-w-4xl mx-auto bg-white border border-gray-300 rounded-2xl shadow-xl p-8 md:p-12 text-[15px] leading-relaxed text-gray-800 space-y-8">
    <h1 class="text-3xl font-extrabold text-blue-800 text-center">🤝 Сотрудничество с Ru-CMS</h1>

    {{-- 💬 Введение --}}
    <p>
        Мы открыты к различным форматам партнёрства и взаимодействия. <strong>Ru-CMS</strong> развивается благодаря сообществу, обратной связи и совместным проектам. Если вы хотите стать частью нашей экосистемы — мы будем рады сотрудничеству.
    </p>

    {{-- 📌 Возможные форматы --}}
    <div>
        <h2 class="text-xl font-semibold text-blue-700 mb-2">📌 Что мы предлагаем:</h2>
        <ul class="list-disc pl-6 space-y-1">
            <li>💼 Совместная разработка модулей и шаблонов</li>
            <li>🎓 Интеграция с образовательными и обучающими платформами</li>
            <li>🌐 Размещение на маркетплейсах или платформах SaaS</li>
            <li>📣 Публикации и статьи о вашем опыте работы с Ru-CMS</li>
            <li>📦 White label-решения под бренды клиентов</li>
        </ul>
    </div>

    {{-- 📬 Как начать сотрудничество --}}
    <div>
        <h2 class="text-xl font-semibold text-blue-700 mb-2">📬 Как подать предложение:</h2>
        <p>
            Напишите нам через <a href="/contacts" class="text-blue-600 hover:underline">форму обратной связи</a> или по электронной почте: <a href="mailto:partners@example.com" class="text-blue-600 hover:underline">partners@example.com</a> — расскажите о себе, своей идее и форме сотрудничества.
        </p>
    </div>

    {{-- 🤔 Не уверены? --}}
    <div>
        <h2 class="text-xl font-semibold text-blue-700 mb-2">🤔 У вас есть идея, но вы не уверены?</h2>
        <p>
            Напишите нам. Даже если вы не уверены, как это реализовать, мы поможем вам сформировать предложение, подобрать формат взаимодействия и технические решения.
        </p>
    </div>

    {{-- 🧩 Поддерживаемое развитие --}}
    <div>
        <h2 class="text-xl font-semibold text-blue-700 mb-2">🌱 Поддержка проекта</h2>
        <p>
            Также вы можете <a href="/donate" class="text-blue-600 hover:underline">поддержать Ru-CMS финансово</a> или информационно — каждый вклад помогает развивать открытый и безопасный продукт.
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

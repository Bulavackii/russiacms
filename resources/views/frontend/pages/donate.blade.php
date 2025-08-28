@extends('layouts.frontend')

@section('title', 'Поддержка и пожертвования')

@section('content')
<section class="max-w-4xl mx-auto bg-white border border-gray-300 rounded-2xl shadow-xl p-8 md:p-12 text-[15px] leading-relaxed text-gray-800 space-y-8">
    <h1 class="text-3xl font-extrabold text-blue-800 text-center">💙 Поддержка и пожертвования</h1>

    <div class="space-y-6">
        <p>
            <strong>Ru CMS</strong> — проект с открытым исходным кодом, который развивается благодаря усилиям сообщества и добровольной поддержке. Мы не продаём лицензии и не навязываем платные подписки. Но нам важно ваше участие, чтобы продолжать развивать систему, добавлять модули и обновления.
        </p>

        {{-- 🔧 Как вы можете помочь --}}
        <h2 class="text-xl font-bold text-blue-700">🤝 Как вы можете помочь:</h2>
        <ul class="list-disc pl-6 space-y-2 text-sm">
            <li>📣 Расскажите о Ru-CMS в соцсетях, форумах и среди коллег</li>
            <li>💬 Присылайте идеи, предложения и багрепорты через <a href="#" target="_blank" class="text-blue-600 hover:underline">GitHub Issues</a></li>
            <li>🛠️ Участвуйте в разработке — форкните репозиторий и предложите pull request</li>
            <li>📥 Поддержите проект финансово, чтобы ускорить развитие</li>
        </ul>

        {{-- 💳 Формы поддержки --}}
        <h2 class="text-xl font-bold text-blue-700">💳 Формы поддержки:</h2>
        <div class="grid sm:grid-cols-2 gap-6 text-sm">
            <div class="bg-gray-50 border rounded-lg p-4 space-y-2 shadow-sm">
                <h3 class="font-semibold text-blue-700">☕ Купить автору кофе</h3>
                <p>Небольшая благодарность — большая мотивация!</p>
                <a href="#" class="text-blue-600 hover:underline">Поддержать на Boosty / Donatty</a>
            </div>

            <div class="bg-gray-50 border rounded-lg p-4 space-y-2 shadow-sm">
                <h3 class="font-semibold text-blue-700">💸 Реквизиты для перевода</h3>
                <p>Вы можете сделать прямой перевод:</p>
                <ul class="text-gray-600 text-sm list-disc pl-4 space-y-1">
                    <li>📱 СБП / Телефон: <strong>+7 (985) 620-44-00</strong></li>
                    <li>🧾 По запросу: карта или счёт (свяжитесь через <a href="/contacts" class="text-blue-600 hover:underline">Контакты</a>)</li>
                </ul>
            </div>
        </div>

        {{-- 💚 Благодарность --}}
        <div class="text-center text-green-700 font-semibold text-sm mt-6">
            Каждая ваша поддержка — вклад в развитие независимого, безопасного и удобного инструмента для всех.
        </div>
    </div>

    {{-- 🔙 Кнопка назад --}}
    <div class="text-center mt-10">
        <a href="/" class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg shadow hover:bg-blue-700 transition">
            ← На главную
        </a>
    </div>
</section>
@endsection

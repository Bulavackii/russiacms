@extends('layouts.frontend')

@section('title', 'FAQ — Часто задаваемые вопросы')

@section('content')
<article class="max-w-4xl mx-auto px-4">
  {{-- Заголовок --}}
  <header class="rounded-xl p-6 md:p-8 border shadow-sm mb-6"
          style="background:#fff; border-color: color-mix(in oklab, var(--color-text,#111827) 10%, #e5e7eb)">
    <div class="flex items-start md:items-center gap-4 md:gap-6 flex-col md:flex-row">
      <div class="inline-flex items-center justify-center w-12 h-12 rounded-lg"
           style="background: color-mix(in oklab, var(--color-primary,#2563eb) 12%, #fff);">
        @themeIcon('help-circle','w-5 h-5 opacity-80')
      </div>
      <div class="flex-1">
        <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight" style="color: var(--color-text,#111827)">
          FAQ — Часто задаваемые вопросы
        </h1>
        <p class="mt-2 text-sm md:text-base opacity-80" style="color: var(--color-text,#111827)">
          Быстрые ответы по регистрации, доступам, модулям, шаблонам, медиа и настройкам RU-CMS.
        </p>
      </div>
    </div>

    {{-- Поиск по вопросам --}}
    <div class="mt-5 relative">
      <input id="faqSearch" type="search" placeholder="Поиск по вопросам…"
             class="w-full border rounded-lg pl-10 pr-3 py-2.5 text-sm focus:outline-none focus:ring-2"
             style="border-color: color-mix(in oklab, var(--color-text,#111827) 12%, #e5e7eb);">
      <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none opacity-60">
        @themeIcon('search','w-4 h-4')
      </div>
    </div>
  </header>

  {{-- Секция вопросов --}}
  <section class="rounded-xl p-4 md:p-6 border shadow-sm"
           style="background:#fff; border-color: color-mix(in oklab, var(--color-text,#111827) 10%, #e5e7eb)">

    <div id="faqList" class="space-y-2 md:space-y-3 text-[15px]">

      {{-- 1. Регистрация --}}
      <details class="group rounded-lg border overflow-hidden"
               style="border-color: color-mix(in oklab, var(--color-text,#111827) 12%, #e5e7eb)">
        <summary class="cursor-pointer select-none list-none px-4 py-3 md:py-3.5 flex items-center gap-3 font-semibold"
                 style="color: var(--color-text,#111827)">
          @themeIcon('user-plus','w-4 h-4 opacity-70')
          <span>Как зарегистрироваться на сайте?</span>
          <span class="ml-auto transition-transform group-open:rotate-180 opacity-60">@themeIcon('chevron-down','w-4 h-4')</span>
        </summary>
        <div class="px-4 pb-4 -mt-1 text-sm opacity-90">
          Нажмите <a href="{{ route('register') }}" class="underline" style="color:var(--color-primary,#2563eb)">«Регистрация»</a>
          в верхнем меню, заполните форму и подтвердите e-mail.
        </div>
      </details>

      {{-- 2. Восстановление пароля --}}
      <details class="group rounded-lg border overflow-hidden"
               style="border-color: color-mix(in oklab, var(--color-text,#111827) 12%, #e5e7eb)">
        <summary class="cursor-pointer select-none list-none px-4 py-3 md:py-3.5 flex items-center gap-3 font-semibold">
          @themeIcon('key','w-4 h-4 opacity-70')
          <span>Забыл(а) пароль — что делать?</span>
          <span class="ml-auto transition-transform group-open:rotate-180 opacity-60">@themeIcon('chevron-down','w-4 h-4')</span>
        </summary>
        <div class="px-4 pb-4 -mt-1 text-sm opacity-90">
          Перейдите на <a href="{{ route('password.request') }}" class="underline" style="color:var(--color-primary,#2563eb)">страницу восстановления</a>,
          укажите e-mail — инструкция придёт на почту.
        </div>
      </details>

      {{-- 3. Организация --}}
      <details class="group rounded-lg border overflow-hidden"
               style="border-color: color-mix(in oklab, var(--color-text,#111827) 12%, #e5e7eb)">
        <summary class="cursor-pointer select-none list-none px-4 py-3 md:py-3.5 flex items-center gap-3 font-semibold">
          @themeIcon('building','w-4 h-4 opacity-70')
          <span>Можно зарегистрироваться как организация?</span>
          <span class="ml-auto transition-transform group-open:rotate-180 opacity-60">@themeIcon('chevron-down','w-4 h-4')</span>
        </summary>
        <div class="px-4 pb-4 -mt-1 text-sm opacity-90">
          Да. В форме регистрации выберите тип «Юридическое лицо» — появятся поля для ИНН, ОГРН и адреса.
        </div>
      </details>

      {{-- 4. Модули --}}
      <details class="group rounded-lg border overflow-hidden"
               style="border-color: color-mix(in oklab, var(--color-text,#111827) 12%, #e5e7eb)">
        <summary class="cursor-pointer select-none list-none px-4 py-3 md:py-3.5 flex items-center gap-3 font-semibold">
          @themeIcon('boxes','w-4 h-4 opacity-70')
          <span>Где управлять модулями?</span>
          <span class="ml-auto transition-transform group-open:rotate-180 opacity-60">@themeIcon('chevron-down','w-4 h-4')</span>
        </summary>
        <div class="px-4 pb-4 -mt-1 text-sm opacity-90">
          В админке на странице <a href="{{ url('/admin/modules') }}" class="underline" style="color:var(--color-primary,#2563eb)">Модули</a>
          можно включать/выключать, архивировать и скачивать ZIP-архивы модулей.
        </div>
      </details>

      {{-- 5. Шаблоны --}}
      <details class="group rounded-lg border overflow-hidden"
               style="border-color: color-mix(in oklab, var(--color-text,#111827) 12%, #e5e7eb)">
        <summary class="cursor-pointer select-none list-none px-4 py-3 md:py-3.5 flex items-center gap-3 font-semibold">
          @themeIcon('layout','w-4 h-4 opacity-70')
          <span>Как подключить свой шаблон?</span>
          <span class="ml-auto transition-transform group-open:rotate-180 opacity-60">@themeIcon('chevron-down','w-4 h-4')</span>
        </summary>
        <div class="px-4 pb-4 -mt-1 text-sm opacity-90 space-y-2">
          Создайте файл в <code class="px-1 rounded bg-gray-100 text-xs">resources/views/frontend/templates/название.blade.php</code> —
          он появится при создании новости. Пример полного гайда — в разделе ниже.
        </div>
      </details>

      {{-- 6. Медиа --}}
      <details class="group rounded-lg border overflow-hidden"
               style="border-color: color-mix(in oklab, var(--color-text,#111827) 12%, #e5e7eb)">
        <summary class="cursor-pointer select-none list-none px-4 py-3 md:py-3.5 flex items-center gap-3 font-semibold">
          @themeIcon('image','w-4 h-4 opacity-70')
          <span>Можно использовать видео и изображения?</span>
          <span class="ml-auto transition-transform group-open:rotate-180 opacity-60">@themeIcon('chevron-down','w-4 h-4')</span>
        </summary>
        <div class="px-4 pb-4 -mt-1 text-sm opacity-90">
          Да. Загружайте прямо в TinyMCE или через
          <a href="{{ url('/admin/files') }}" class="underline" style="color:var(--color-primary,#2563eb)">менеджер файлов</a>.
        </div>
      </details>

      {{-- 7. Безопасность --}}
      <details class="group rounded-lg border overflow-hidden"
               style="border-color: color-mix(in oklab, var(--color-text,#111827) 12%, #e5e7eb)">
        <summary class="cursor-pointer select-none list-none px-4 py-3 md:py-3.5 flex items-center gap-3 font-semibold">
          @themeIcon('lock','w-4 h-4 opacity-70')
          <span>Насколько безопасна RU-CMS?</span>
          <span class="ml-auto transition-transform group-open:rotate-180 opacity-60">@themeIcon('chevron-down','w-4 h-4')</span>
        </summary>
        <div class="px-4 pb-4 -mt-1 text-sm opacity-90">
          Используются <strong>bcrypt</strong> для паролей, <strong>JWT</strong> для API и ролевая модель доступа.
        </div>
      </details>

      {{-- 8. Профиль --}}
      <details class="group rounded-lg border overflow-hidden"
               style="border-color: color-mix(in oklab, var(--color-text,#111827) 12%, #e5e7eb)">
        <summary class="cursor-pointer select-none list-none px-4 py-3 md:py-3.5 flex items-center gap-3 font-semibold">
          @themeIcon('user','w-4 h-4 opacity-70')
          <span>Как обновить информацию о себе?</span>
          <span class="ml-auto transition-transform group-open:rotate-180 opacity-60">@themeIcon('chevron-down','w-4 h-4')</span>
        </summary>
        <div class="px-4 pb-4 -mt-1 text-sm opacity-90">
          Зайдите в <a href="{{ route('dashboard.edit') }}" class="underline" style="color:var(--color-primary,#2563eb)">личный кабинет</a>
          и измените имя, e-mail, пароль и др.
        </div>
      </details>

      {{-- 9. Поддержка --}}
      <details class="group rounded-lg border overflow-hidden"
               style="border-color: color-mix(in oklab, var(--color-text,#111827) 12%, #e5e7eb)">
        <summary class="cursor-pointer select-none list-none px-4 py-3 md:py-3.5 flex items-center gap-3 font-semibold">
          @themeIcon('life-buoy','w-4 h-4 opacity-70')
          <span>Как обратиться в поддержку?</span>
          <span class="ml-auto transition-transform group-open:rotate-180 opacity-60">@themeIcon('chevron-down','w-4 h-4')</span>
        </summary>
        <div class="px-4 pb-4 -mt-1 text-sm opacity-90">
          Заполните форму на <a href="{{ url('/contacts') }}" class="underline" style="color:var(--color-primary,#2563eb)">странице «Контакты»</a>
          или напишите через модуль «Сообщения» в админке.
        </div>
      </details>
    </div>
  </section>

  {{-- Разворачиваемые подробные инструкции --}}
  <section class="mt-6 grid gap-6">
    {{-- Кастомный шаблон: разворачиваемый блок --}}
    <details class="rounded-xl p-5 md:p-6 border shadow-sm bg-white"
             style="border-color: color-mix(in oklab, var(--color-text,#111827) 10%, #e5e7eb)">
      <summary class="cursor-pointer select-none list-none font-semibold flex items-center gap-3">
        @themeIcon('wrench','w-4 h-4 opacity-70')
        <span>Как создать собственный шаблон и подключить его?</span>
        <span class="ml-auto opacity-60">@themeIcon('chevron-down','w-4 h-4')</span>
      </summary>
      <div class="mt-4 text-sm opacity-90 space-y-3">
        <ol class="list-decimal list-inside space-y-2">
          <li>Создайте Blade-файл:
            <code class="bg-gray-100 px-2 py-0.5 rounded text-xs block mt-1">resources/views/frontend/templates/custom.blade.php</code>
          </li>
          <li>Внутри используйте:
            <code class="bg-gray-100 px-2 py-0.5 rounded text-xs">$templates['custom']</code>
            для вывода записей.
          </li>
          <li>Добавьте ключ <code class="text-xs">custom</code> в список шаблонов, чтобы он появился при создании новости.</li>
          <li>На странице подключайте так:
            <pre class="bg-gray-100 p-3 rounded text-xs overflow-x-auto mt-2">
&#64;includeIf('frontend.templates.custom', ['templates' => ['custom' => $templates['custom'] ?? collect()]])</pre>
          </li>
        </ol>
        <p class="text-xs opacity-70">
          Имя файла должно совпадать со значением <code>template</code> у новости. Аналогично можно сделать «Отзывы»,
          «Галерею», «Контакты» и др.
        </p>
      </div>
    </details>

    {{-- Установка через веб-интерфейс: разворачиваемый блок --}}
    <details class="rounded-xl p-5 md:p-6 border shadow-sm bg-white"
             style="border-color: color-mix(in oklab, var(--color-text,#111827) 10%, #e5e7eb)">
      <summary class="cursor-pointer select-none list-none font-semibold flex items-center gap-3">
        @themeIcon('server','w-4 h-4 opacity-70')
        <span>Полная установка RU-CMS через веб-интерфейс</span>
        <span class="ml-auto opacity-60">@themeIcon('chevron-down','w-4 h-4')</span>
      </summary>
      <div class="mt-4 text-sm opacity-90 space-y-3">
        <ol class="list-decimal list-inside space-y-2">
          <li>Разместите проект (папку <code class="text-xs">Ru-CMS</code>) в директории сайта,
            <code class="bg-gray-100 px-2 py-0.5 rounded text-xs block mt-1">/var/www/html/Ru-CMS/public</code></li>
          <li>Установите зависимости:
            <pre class="bg-gray-100 p-3 rounded text-xs overflow-x-auto mt-2">composer install
npm install
npm run build</pre>
          </li>
          <li>Создайте <code class="text-xs">.env</code> из <code class="text-xs">.env backup</code> и укажите БД.</li>
          <li>Откройте <code class="bg-gray-100 px-2 py-0.5 rounded text-xs">/install</code> — мастер всё сделает сам.</li>
          <li>Проверьте права на запись:
            <pre class="bg-gray-100 p-3 rounded text-xs overflow-x-auto mt-2">chmod -R 775 storage bootstrap/cache</pre>
          </li>
        </ol>
      </div>
    </details>

    {{-- База знаний --}}
    <div class="rounded-xl p-5 md:p-6 border shadow-sm bg-white"
         style="border-color: color-mix(in oklab, var(--color-text,#111827) 10%, #e5e7eb)">
      <h3 class="font-semibold flex items-center gap-2">
        @themeIcon('book-open','w-4 h-4 opacity-70')
        <span>База знаний</span>
      </h3>
      <ul class="mt-3 text-sm opacity-90 space-y-1">
        <li><a href="{{ url('/about') }}" class="underline" style="color:var(--color-primary,#2563eb)">Что такое RU-CMS?</a></li>
        <li><a href="{{ url('/faq') }}" class="underline" style="color:var(--color-primary,#2563eb)">Шаблоны, блоки и категории</a></li>
        <li><a href="{{ url('/contacts') }}" class="underline" style="color:var(--color-primary,#2563eb)">Поддержка</a></li>
      </ul>
    </div>

    {{-- Назад --}}
    <div class="text-center">
      <a href="{{ url('/') }}"
         class="inline-flex items-center gap-2 px-5 py-2.5 rounded-md text-white"
         style="background: var(--color-primary,#2563eb)">
        @themeIcon('arrow-left','w-4 h-4')
        <span class="font-semibold">На главную</span>
      </a>
    </div>
  </section>
</article>

{{-- Поиск по вопросам --}}
<script>
  (function () {
    const input = document.getElementById('faqSearch');
    const items = Array.from(document.querySelectorAll('#faqList details'));

    function normalize(s){ return (s || '').toLowerCase().trim(); }

    input?.addEventListener('input', () => {
      const q = normalize(input.value);
      items.forEach(d => {
        const text = normalize(d.innerText);
        const match = !q || text.includes(q);
        d.style.display = match ? '' : 'none';
        if (q && match) d.open = true; // подсказка: раскрываем подходящее
      });
    });
  })();
</script>
@endsection

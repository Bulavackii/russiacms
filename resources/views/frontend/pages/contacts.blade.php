@extends('layouts.frontend')

@section('title', 'Контакты Ru-CMS')

@section('content')
@php
  // Пробуем найти публичный роут модуля сообщений (если есть)
  $formAction = null;
  if (\Illuminate\Support\Facades\Route::has('messages.public.store')) {
      $formAction = route('messages.public.store');
  } elseif (\Illuminate\Support\Facades\Route::has('messages.store')) {
      $formAction = route('messages.store');
  }
@endphp

{{-- Локальные стили страницы (аккуратные переносы и грид для соц-ссылок) --}}
<style>
  /* чтобы iOS не увеличивал произвольно шрифты */
  #contacts-root { -webkit-text-size-adjust: 100%; text-size-adjust: 100%; }

  /* универсальные карточки/кнопки */
  .card{background:#fff;border:1px solid rgba(0,0,0,.1);border-radius:16px;box-shadow:0 1px 2px rgba(0,0,0,.03)}
  .btn{display:inline-flex;align-items:center;gap:.5rem;padding:.6rem 1rem;border-radius:10px;font-weight:600}
  .btn-primary{color:#fff;background:var(--color-primary,#2563eb)}
  .btn-primary:hover{filter:brightness(.95)}

  /* читаемые лейблы в подписях */
  .kv-label{font-size:.75rem;letter-spacing:.06em;text-transform:uppercase;color:#6b7280}

  /* корректные переносы и длинные слова/ссылки */
  .hyphens{
    hyphens:auto; -webkit-hyphens:auto;
    overflow-wrap:anywhere; word-break:break-word;
  }
  /* иногда темы подсовывают курсивный системный шрифт — выключаем */
  .no-italic{font-style:normal !important}

  /* --- соц-кнопки: аккуратная сетка и выравнивание --- */
  .social-list{
    display:grid;
    grid-template-columns:repeat(2,minmax(0,1fr));
    gap:.5rem;
  }
  @media (min-width:480px){ .social-list{ grid-template-columns:repeat(3,minmax(0,1fr)); } }
  @media (min-width:768px){ .social-list{ grid-template-columns:repeat(5,minmax(0,1fr)); gap:.6rem; } }

  .pill{
    display:flex;align-items:center;justify-content:center;gap:.5rem;
    border:1px solid rgba(0,0,0,.12);border-radius:12px;
    min-height:44px;padding:.625rem .8rem;background:#fff;
    box-sizing:border-box;transition:background .2s,border-color .2s;
  }
  .pill:hover{background:#f8fafc;border-color:rgba(0,0,0,.18)}
  .pill>svg,.pill>i{width:1rem;height:1rem;flex:0 0 1rem;line-height:1}
  .pill .fa,.pill .fab,.pill .fas{width:1rem;height:1rem;text-align:center}
</style>

<article id="contacts-root" class="max-w-4xl mx-auto px-4 hyphens">
  {{-- Заголовок --}}
  <header class="card p-6 md:p-8 mb-6">
    <div class="flex items-start md:items-center gap-4 md:gap-6 flex-col md:flex-row">
      <div class="inline-flex items-center justify-center w-12 h-12 rounded-lg"
           style="background: rgba(37,99,235,0.08);">
        @themeIcon('phone','w-5 h-5 text-blue-600')
      </div>
      <div class="flex-1">
        <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight no-italic"
            style="color: var(--color-text,#111827)">Свяжитесь с командой Ru-CMS</h1>
        <p class="mt-2 text-sm md:text-base text-gray-600 no-italic">
          Поможем с установкой, интеграциями и кастомизацией. Обычно отвечаем в рабочее время.
        </p>
      </div>
    </div>
  </header>

  {{-- Карточки контактов --}}
  <section class="grid sm:grid-cols-2 gap-4 md:gap-6 mb-6">
    {{-- Адрес --}}
    <div class="card p-5 flex gap-3">
      <div class="shrink-0 mt-0.5">
        @themeIcon('map-pin','w-5 h-5 text-blue-600')
      </div>
      <div>
        <div class="kv-label no-italic">Офис</div>
        <div class="font-medium text-gray-800 no-italic">
          Москва, ул. Примерная, д. 123, офис 45
        </div>
      </div>
    </div>

    {{-- E-mail --}}
    <div class="card p-5 flex items-start gap-3">
      <div class="shrink-0 mt-0.5">
        @themeIcon('mail','w-5 h-5 text-blue-600')
      </div>
      <div class="flex-1">
        <div class="kv-label no-italic">E-mail</div>
        <div class="font-medium text-gray-800 no-italic">
          <a href="mailto:support@russiacms.ru" class="underline underline-offset-2 hover:text-blue-700 break-all">
            support@russiacms.ru
          </a>
        </div>
        <div class="text-xs text-gray-500 mt-1 no-italic">Отвечаем в течение рабочего дня</div>
      </div>
      <button type="button"
              data-copy="support@russiacms.ru"
              class="ml-2 inline-flex items-center gap-1 text-xs px-2 py-1 border rounded hover:bg-gray-50"
              title="Скопировать e-mail">
        @themeIcon('copy','w-4 h-4') <span class="no-italic">Копировать</span>
      </button>
    </div>

    {{-- Телефон --}}
    <div class="card p-5 flex items-start gap-3">
      <div class="shrink-0 mt-0.5">
        @themeIcon('phone','w-5 h-5 text-blue-600')
      </div>
      <div class="flex-1">
        <div class="kv-label no-italic">Телефон</div>
        <div class="font-medium text-gray-800 no-italic">
          <a href="tel:+74951234567" class="hover:text-blue-700 break-words">+7 (495) 123-45-67</a>
        </div>
        <div class="text-xs text-gray-500 mt-1 no-italic">Пн–Пт: 09:00–18:00 (МСК)</div>
      </div>
      <button type="button"
              data-copy="+74951234567"
              class="ml-2 inline-flex items-center gap-1 text-xs px-2 py-1 border rounded hover:bg-gray-50"
              title="Скопировать номер">
        @themeIcon('copy','w-4 h-4') <span class="no-italic">Копировать</span>
      </button>
    </div>

    {{-- График --}}
    <div class="card p-5 flex gap-3">
      <div class="shrink-0 mt-0.5">
        @themeIcon('clock','w-5 h-5 text-blue-600')
      </div>
      <div>
        <div class="kv-label no-italic">График работы</div>
        <div class="font-medium text-gray-800 no-italic">Пн–Пт: 09:00–18:00</div>
        <div class="text-xs text-gray-500 no-italic">Сб, Вс — выходные</div>
      </div>
    </div>
  </section>

  {{-- Мы в интернете (фиксированная сетка) --}}
  <section class="card p-5 md:p-6 mb-6">
    <h2 class="kv-label no-italic mb-3">Мы в интернете</h2>
    <div class="social-list">
      <a href="https://github.com/Bulavackii/Ru-CMS" target="_blank" class="pill">
        @themeIcon('github','w-4 h-4')
        <span class="text-sm no-italic">GitHub проекта</span>
      </a>
      <a href="{{ url('/faq') }}" class="pill">
        @themeIcon('help-circle','w-4 h-4')
        <span class="text-sm no-italic">FAQ</span>
      </a>
      <a href="{{ url('/about') }}" class="pill">
        @themeIcon('info','w-4 h-4')
        <span class="text-sm no-italic">О Ru-CMS</span>
      </a>
      <a href="https://t.me/ru_cms" target="_blank" class="pill">
        @themeIcon('send','w-4 h-4')
        <span class="text-sm no-italic">Telegram</span>
      </a>
      <a href="https://vk.com/ru_cms" target="_blank" class="pill">
        @themeIcon('brand-vk','w-4 h-4')
        <span class="text-sm no-italic">VK</span>
      </a>
    </div>
  </section>

  {{-- Форма / Mailto --}}
  <section class="card p-5 md:p-6">
    <h2 class="text-lg font-semibold mb-3 no-italic" style="color: var(--color-text,#111827)">Написать нам</h2>

    @if($formAction)
      <form action="{{ $formAction }}" method="POST" class="grid gap-3 md:gap-4">
        @csrf
        <div class="grid md:grid-cols-2 gap-3">
          <label class="block text-sm no-italic">
            <span class="text-gray-600">Ваше имя</span>
            <input type="text" name="name" required
                   class="mt-1 w-full border rounded px-3 py-2 focus:outline-none focus:ring-2"
                   placeholder="Иван Иванов">
          </label>
          <label class="block text-sm no-italic">
            <span class="text-gray-600">E-mail</span>
            <input type="email" name="email" required
                   class="mt-1 w-full border rounded px-3 py-2 focus:outline-none focus:ring-2"
                   placeholder="you@example.com">
          </label>
        </div>

        <label class="block text-sm no-italic">
          <span class="text-gray-600">Сообщение</span>
          <textarea name="message" rows="5" required
                    class="mt-1 w-full border rounded px-3 py-2 focus:outline-none focus:ring-2"
                    placeholder="Кратко опишите вопрос или задачу"></textarea>
        </label>

        <div class="flex flex-wrap items-center gap-3">
          <button class="btn btn-primary">
            @themeIcon('send','w-4 h-4') <span class="no-italic">Отправить</span>
          </button>
          <span class="text-xs text-gray-500 no-italic">Нажимая «Отправить», вы соглашаетесь с обработкой данных.</span>
        </div>
      </form>
    @else
      <div class="rounded-lg border p-4 text-sm text-gray-700 no-italic">
        Публичная форма сообщений пока не подключена. Напишите нам на
        <a href="mailto:support@russiacms.ru" class="underline text-blue-700 break-all">support@russiacms.ru</a>
        или в <a href="https://t.me/ru_cms" target="_blank" class="underline text-blue-700">Telegram</a>.
      </div>
    @endif
  </section>

  {{-- Назад --}}
  <div class="text-center mt-8">
    <a href="{{ url('/') }}" class="btn btn-primary">
      @themeIcon('arrow-left','w-4 h-4')
      <span class="no-italic">На главную</span>
    </a>
  </div>
</article>

{{-- Копирование контактов в буфер обмена --}}
<script>
  document.addEventListener('click', (e) => {
    const btn = e.target.closest('[data-copy]');
    if (!btn) return;
    const val = btn.getAttribute('data-copy') || '';
    navigator.clipboard?.writeText(val).then(() => {
      const old = btn.innerHTML;
      btn.innerHTML = `<svg class="w-4 h-4 inline-block" viewBox="0 0 24 24" fill="none"><path d="M5 13l4 4L19 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg> <span class="no-italic">Скопировано</span>`;
      btn.classList.add('bg-green-50','border-green-200','text-green-700');
      setTimeout(() => { btn.innerHTML = old; btn.classList.remove('bg-green-50','border-green-200','text-green-700'); }, 1300);
    });
  });
</script>
@endsection

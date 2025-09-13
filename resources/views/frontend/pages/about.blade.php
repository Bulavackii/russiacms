@extends('layouts.frontend')

@section('title', 'О RU-CMS')

@section('content')
<article class="max-w-4xl mx-auto px-4">
  {{-- Хиро-блок --}}
  <header class="rounded-lg md:rounded-xl p-6 md:p-8 border shadow-sm mb-8"
          style="border-color: color-mix(in oklab, var(--color-text,#111827) 10%, #e5e7eb); background: #fff">
    <div class="flex items-start md:items-center gap-4 md:gap-6 flex-col md:flex-row">
      <div class="inline-flex items-center justify-center w-12 h-12 rounded-lg"
           style="background: color-mix(in oklab, var(--color-primary,#2563eb) 12%, #fff);">
        @themeIcon('shield','w-5 h-5 opacity-80')
      </div>
      <div class="flex-1">
        <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight"
            style="color: var(--color-text,#111827)">RU-CMS — быстрая, модульная и безопасная</h1>
        <p class="mt-2 text-sm md:text-base opacity-80" style="color: var(--color-text,#111827)">
          Современная CMS для проектов любого масштаба: чистая архитектура, гибкая настройка интерфейса,
          удобные инструменты контента и спокойствие за безопасность.
        </p>
      </div>
    </div>
  </header>

  {{-- Основной блок содержимого --}}
  <section class="rounded-lg md:rounded-xl p-6 md:p-8 border shadow-sm space-y-6"
           style="border-color: color-mix(in oklab, var(--color-text,#111827) 10%, #e5e7eb); background:#fff">

    {{-- Преимущества (минималистичная сетка) --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 md:gap-6">
      <div class="p-4 rounded-lg border"
           style="border-color: color-mix(in oklab, var(--color-text,#111827) 10%, #e5e7eb)">
        <div class="flex items-start gap-3">
          <span class="inline-flex w-8 h-8 items-center justify-center rounded-md"
                style="background: color-mix(in oklab, var(--color-primary,#2563eb) 10%, #fff);">
            @themeIcon('layers','w-4 h-4 opacity-80')
          </span>
          <div>
            <h3 class="font-semibold" style="color: var(--color-text,#111827)">Модульная архитектура</h3>
            <p class="text-sm opacity-80 mt-1">Подключайте и развивайте модули без лишней сложности.</p>
          </div>
        </div>
      </div>

      <div class="p-4 rounded-lg border"
           style="border-color: color-mix(in oklab, var(--color-text,#111827) 10%, #e5e7eb)">
        <div class="flex items-start gap-3">
          <span class="inline-flex w-8 h-8 items-center justify-center rounded-md"
                style="background: color-mix(in oklab, var(--color-primary,#2563eb) 10%, #fff);">
            @themeIcon('lock','w-4 h-4 opacity-80')
          </span>
          <div>
            <h3 class="font-semibold" style="color: var(--color-text,#111827)">Безопасность</h3>
            <p class="text-sm opacity-80 mt-1">JWT, bcrypt, роли и права — из коробки.</p>
          </div>
        </div>
      </div>

      <div class="p-4 rounded-lg border"
           style="border-color: color-mix(in oklab, var(--color-text,#111827) 10%, #e5e7eb)">
        <div class="flex items-start gap-3">
          <span class="inline-flex w-8 h-8 items-center justify-center rounded-md"
                style="background: color-mix(in oklab, var(--color-primary,#2563eb) 10%, #fff);">
            @themeIcon('type','w-4 h-4 opacity-80')
          </span>
          <div>
            <h3 class="font-semibold" style="color: var(--color-text,#111827)">Редактор фрагментов</h3>
            <p class="text-sm opacity-80 mt-1">TinyMCE 7 с предпросмотром, быстрыми вставками и локальными черновиками.</p>
          </div>
        </div>
      </div>

      <div class="p-4 rounded-lg border"
           style="border-color: color-mix(in oklab, var(--color-text,#111827) 10%, #e5e7eb)">
        <div class="flex items-start gap-3">
          <span class="inline-flex w-8 h-8 items-center justify-center rounded-md"
                style="background: color-mix(in oklab, var(--color-primary,#2563eb) 10%, #fff);">
            @themeIcon('palette','w-4 h-4 opacity-80')
          </span>
          <div>
            <h3 class="font-semibold" style="color: var(--color-text,#111827)">Редактор тем</h3>
            <p class="text-sm opacity-80 mt-1">Цвета, шрифты, радиусы, иконки и фон — на живых переменных темы.</p>
          </div>
        </div>
      </div>

      <div class="p-4 rounded-lg border sm:col-span-2"
           style="border-color: color-mix(in oklab, var(--color-text,#111827) 10%, #e5e7eb)">
        <div class="flex items-start gap-3">
          <span class="inline-flex w-8 h-8 items-center justify-center rounded-md"
                style="background: color-mix(in oklab, var(--color-primary,#2563eb) 10%, #fff);">
            @themeIcon('arrows-left-right','w-4 h-4 opacity-80')
          </span>
          <div class="flex-1">
            <h3 class="font-semibold" style="color: var(--color-text,#111827)">Массовый импорт/экспорт новостей (NewsIO)</h3>
            <p class="text-sm opacity-80 mt-1">
              Импортируйте и выгружайте контент пакетно: ускоряйте миграции, интеграции и бэкапы.
            </p>
          </div>
        </div>
      </div>
    </div>

    {{-- Небольшой блок фактов/статистики --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 md:gap-6">
      <div class="rounded-md border p-4 text-center"
           style="border-color: color-mix(in oklab, var(--color-text,#111827) 10%, #e5e7eb)">
        <div class="text-2xl font-extrabold" style="color: var(--color-text,#111827)">⚡</div>
        <div class="mt-1 text-sm opacity-80">Лёгкая и быстрая</div>
      </div>
      <div class="rounded-md border p-4 text-center"
           style="border-color: color-mix(in oklab, var(--color-text,#111827) 10%, #e5e7eb)">
        <div class="text-2xl font-extrabold" style="color: var(--color-text,#111827)">🧩</div>
        <div class="mt-1 text-sm opacity-80">Модули и шаблоны</div>
      </div>
      <div class="rounded-md border p-4 text-center"
           style="border-color: color-mix(in oklab, var(--color-text,#111827) 10%, #e5e7eb)">
        <div class="text-2xl font-extrabold" style="color: var(--color-text,#111827)">🔐</div>
        <div class="mt-1 text-sm opacity-80">Готовая безопасность</div>
      </div>
    </div>

    {{-- CTA --}}
    <div class="flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between pt-2">
      <a href="https://github.com/Bulavackii/Ru-CMS"
         target="_blank"
         class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-md text-white"
         style="background: var(--color-primary,#2563eb)">
        @themeIcon('github','w-4 h-4')
        <span class="font-semibold">Исходники на GitHub</span>
      </a>

      <a href="{{ url('/') }}"
         class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-md border"
         style="border-color: color-mix(in oklab, var(--color-text,#111827) 12%, #e5e7eb)">
        @themeIcon('arrow-left','w-4 h-4')
        <span>Вернуться на главную</span>
      </a>
    </div>
  </section>
</article>
@endsection

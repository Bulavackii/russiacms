@extends('layouts.admin')
@section('title', $fragment->exists ? 'Редактировать фрагмент' : 'Создать фрагмент')

@section('content')
    <h1 class="text-2xl font-bold mb-6">
        {{ $fragment->exists ? '🧩 Редактировать фрагмент' : '🧩 Создать фрагмент' }}
    </h1>

    <form method="POST"
        action="{{ $fragment->exists
            ? route('admin.visual.fragments.update', $fragment)
            : route('admin.visual.fragments.store') }}"
        class="space-y-6">
        @csrf
        @if ($fragment->exists)
            @method('PUT')
        @endif

        @php
            $isSystem = in_array($fragment->slug, ['site-header', 'site-footer'], true);
        @endphp

        {{-- Название --}}
        <div>
            <label class="block text-sm mb-1">Название</label>
            <input type="text" name="title" value="{{ old('title', $fragment->title) }}"
                class="border rounded px-3 py-2 w-full" required>
        </div>

        {{-- Slug --}}
        <div>
            <label class="block text-sm mb-1">Slug</label>
            <input type="text" name="slug" value="{{ old('slug', $fragment->slug) }}"
                class="border rounded px-3 py-2 w-full" {{ $isSystem ? 'readonly' : '' }} required>
            @if ($isSystem)
                <p class="text-xs text-gray-500 mt-1">Системный фрагмент — slug изменять нельзя.</p>
            @endif
        </div>

        {{-- Зона --}}
        <div>
            <label class="block text-sm mb-1">Зона</label>
            <select name="zone" class="border rounded px-3 py-2 w-full" {{ $isSystem ? 'disabled' : '' }}>
                <option value="">—</option>
                <option value="header" @selected(old('zone', $fragment->zone) === 'header')>Header</option>
                <option value="footer" @selected(old('zone', $fragment->zone) === 'footer')>Footer</option>
                <option value="custom" @selected(old('zone', $fragment->zone) === 'custom')>Custom</option>
            </select>
            @if ($isSystem)
                <input type="hidden" name="zone" value="{{ $fragment->slug === 'site-header' ? 'header' : 'footer' }}">
            @endif
        </div>

        {{-- Статус --}}
        <label class="inline-flex items-center gap-2">
            <input type="checkbox" name="is_active" value="1" class="rounded border-gray-400"
                @checked(old('is_active', $fragment->is_active ?? true))>
            <span class="text-sm">Активен</span>
        </label>

        {{-- Визуальный редактор (TinyMCE) --}}
        <div>
            <label class="block text-sm mb-1">Содержимое фрагмента</label>
            <textarea id="fragment-editor" name="html_cached" rows="15" class="border rounded px-3 py-2 w-full">
                {{ old('html_cached', $fragment->html_cached) }}
            </textarea>
        </div>

        {{-- Дополнительные JSON поля --}}
        <div>
            <label class="block text-sm mb-1">Schema (JSON)</label>
            <textarea name="schema" rows="6" class="border rounded px-3 py-2 w-full font-mono" placeholder='{}'>{{ old('schema', json_encode($fragment->schema ?? [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) }}</textarea>
        </div>

        <div>
            <label class="block text-sm mb-1">Data (JSON)</label>
            <textarea name="data" rows="10" class="border rounded px-3 py-2 w-full font-mono" placeholder='{}'>{{ old('data', json_encode($fragment->data ?? [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) }}</textarea>
        </div>

        {{-- Кнопки --}}
        <div class="flex gap-3">
            <button class="bg-blue-600 text-white px-4 py-2 rounded">
                {{ $fragment->exists ? 'Сохранить' : 'Создать' }}
            </button>

            @if ($fragment->exists)
                <button formaction="{{ route('admin.visual.fragments.rebuild', $fragment) }}" formmethod="POST"
                    class="px-4 py-2 rounded border">
                    @csrf
                    Пересобрать HTML
                </button>
            @endif
        </div>
    </form>

    {{-- Быстрые пресеты --}}
    @if (!$fragment->exists)
        <div class="mt-6 text-sm text-gray-600">
            Быстро создать:
            <a href="{{ route('admin.visual.fragments.create', ['preset' => 'header']) }}"
                class="text-blue-600 underline">Шапка</a> ·
            <a href="{{ route('admin.visual.fragments.create', ['preset' => 'footer']) }}"
                class="text-blue-600 underline">Подвал</a>
        </div>
    @endif
@endsection

@section('scripts')
    <script src="{{ asset('admin/tinymce/tinymce.min.js') }}"></script>
    <script>
        tinymce.init({
            selector: '#fragment-editor',
            height: 500,
            plugins: 'code link image media table lists advlist fullscreen preview hr anchor charmap emoticons template visualblocks',
            toolbar: `
        undo redo | template blocks fontfamily fontsize |
        bold italic underline forecolor backcolor |
        alignleft aligncenter alignright |
        bullist numlist |
        link image media table |
        code fullscreen preview
    `,
            menubar: 'file edit view insert format tools table help',
            relative_urls: false,
            convert_urls: false,
            branding: false,
            image_caption: true,
            automatic_uploads: true,
            file_picker_types: 'image media',
            images_upload_url: '{{ route('admin.visual.upload.image') }}',

            // 🎨 фирменная палитра цветов
            color_map: [
                "0ea5e9", "Синий (бренд)",
                "f43f5e", "Красный (акцент)",
                "22c55e", "Зелёный (успех)",
                "eab308", "Жёлтый (предупреждение)",
                "1e293b", "Тёмный фон",
                "f8fafc", "Светлый фон"
            ],
            color_cols: 6,

            // 📦 пресеты блоков
            templates: [{
                    title: 'Шапка с логотипом',
                    description: 'Header с логотипом и меню',
                    content: `
                <header class="site-header bg-gray-800 text-white p-4 flex items-center">
                    <img src="/uploads/logo.png" alt="Logo" class="h-10 mr-4">
                    <nav class="space-x-4">
                        <a href="/" class="hover:underline">Главная</a>
                        <a href="/about" class="hover:underline">О нас</a>
                        <a href="/contacts" class="hover:underline">Контакты</a>
                    </nav>
                </header>`
                },
                {
                    title: 'Футер с контактами',
                    description: 'Footer с копирайтом и соцсетями',
                    content: `
                <footer class="site-footer bg-gray-900 text-gray-200 p-6 text-center">
                    <p>© Компания, 2025</p>
                    <div class="space-x-2 mt-2">
                        <a href="#" class="text-blue-400">VK</a>
                        <a href="#" class="text-sky-400">Telegram</a>
                    </div>
                </footer>`
                },
                {
                    title: 'Hero блок',
                    description: 'Крупный заголовок с кнопкой',
                    content: `
                <section class="hero bg-gradient-to-r from-blue-500 to-indigo-600 text-white text-center p-20">
                    <h1 class="text-4xl font-bold mb-4">Добро пожаловать!</h1>
                    <p class="text-lg mb-6">Мы делаем сайты проще</p>
                    <a href="#start" class="bg-white text-blue-600 px-6 py-3 rounded">Начать</a>
                </section>`
                }
            ],

            setup: function(editor) {
                editor.on('change', function() {
                    tinymce.triggerSave();
                });
            }
        });
    </script>
@endsection

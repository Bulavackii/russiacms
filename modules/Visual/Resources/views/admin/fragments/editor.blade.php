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
        class="space-y-4">
        @csrf
        @if ($fragment->exists)
            @method('PUT')
        @endif

        @php
            $isSystem = in_array($fragment->slug, ['site-header', 'site-footer'], true);
        @endphp

        <div>
            <label class="block text-sm mb-1">Название</label>
            <input type="text" name="title" value="{{ old('title', $fragment->title) }}"
                class="border rounded px-3 py-2 w-full" required>
        </div>

        <div>
            <label class="block text-sm mb-1">Slug</label>
            <input type="text" name="slug" value="{{ old('slug', $fragment->slug) }}"
                class="border rounded px-3 py-2 w-full" {{ $isSystem ? 'readonly' : '' }} required>
            @if ($isSystem)
                <p class="text-xs text-gray-500 mt-1">Системный фрагмент — slug изменять нельзя.</p>
            @endif
        </div>

        <div>
            <label class="block text-sm mb-1">Зона</label>
            <select name="zone" class="border rounded px-3 py-2 w-full" {{ $isSystem ? 'disabled' : '' }}>
                <option value="">—</option>
                <option value="header" @selected(old('zone', $fragment->zone) === 'header')>Header</option>
                <option value="footer" @selected(old('zone', $fragment->zone) === 'footer')>Footer</option>
                <option value="custom" @selected(old('zone', $fragment->zone) === 'custom')>Custom</option>
            </select>
            @if ($isSystem)
                {{-- чтобы значение ушло в запрос даже при disabled --}}
                <input type="hidden" name="zone" value="{{ $fragment->slug === 'site-header' ? 'header' : 'footer' }}">

                <div class="mt-2 text-xs">
                    <div
                        class="rounded-md border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/60 px-3 py-2 text-gray-600 dark:text-gray-300 italic">
                        Зона закреплена для системного фрагмента
                        <span class="font-semibold">({{ $fragment->slug === 'site-header' ? 'header' : 'footer' }})</span>
                        и не может быть изменена.
                    </div>
                </div>
            @endif
        </div>

        <label class="inline-flex items-center gap-2">
            <input type="checkbox" name="is_active" value="1" class="rounded border-gray-400"
                @checked(old('is_active', $fragment->is_active ?? true))>
            <span class="text-sm">Активен</span>
        </label>

        <div>
            <label class="block text-sm mb-1">Schema (JSON)</label>
            <textarea name="schema" rows="6" class="border rounded px-3 py-2 w-full font-mono" placeholder='{}'>{{ old('schema', json_encode($fragment->schema ?? [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) }}</textarea>
        </div>

        <div>
            <label class="block text-sm mb-1">Data (JSON)</label>
            <textarea name="data" rows="10" class="border rounded px-3 py-2 w-full font-mono" placeholder='{}'>{{ old('data', json_encode($fragment->data ?? [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) }}</textarea>
        </div>

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

    {{-- быстрые пресеты при создании (необязательно) --}}
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

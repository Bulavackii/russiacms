@extends('layouts.admin')

@section('title', 'Импорт/Экспорт новостей')

@section('content')
    <h1 class="text-2xl font-bold mb-6">🧩 Импорт / Экспорт новостей</h1>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        {{-- EXPORT --}}
        <div class="bg-white dark:bg-gray-900 border rounded-xl p-6 shadow space-y-4">
            <h2 class="text-lg font-semibold">📤 Экспорт</h2>
            <form method="POST" action="{{ route('admin.newsio.export') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium mb-1">Формат</label>
                    <select name="format" class="border rounded px-3 py-2 w-full">
                        <option value="json">JSON (массив)</option>
                        <option value="ndjson">NDJSON (по строке)</option>
                        <option value="csv">CSV</option>
                        <option value="zip">ZIP (manifest.json + media/*)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Категории (фильтр)</label>
                    <select name="category_ids[]" multiple class="border rounded px-3 py-2 w-full h-32">
                        @foreach ($categories as $c)
                            <option value="{{ $c->id }}">{{ $c->title }} (ID: {{ $c->id }})</option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Оставьте пустым — все.</p>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm mb-1">С даты</label>
                        <input type="date" name="date_from" class="border rounded px-3 py-2 w-full">
                    </div>
                    <div>
                        <label class="block text-sm mb-1">По дату</label>
                        <input type="date" name="date_to" class="border rounded px-3 py-2 w-full">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm mb-1">Опубликованные</label>
                        <select name="published" class="border rounded px-3 py-2 w-full">
                            <option value="all">Все</option>
                            <option value="1">Только опубликованные</option>
                            <option value="0">Только черновики</option>
                        </select>
                    </div>
                    <label class="flex items-center gap-2 mt-6">
                        <input type="checkbox" name="with_media" value="1" class="rounded border-gray-400">
                        <span class="text-sm">Вложить обложки (ZIP)</span>
                    </label>
                </div>

                <button class="bg-black text-white px-4 py-2 rounded">Скачать</button>
            </form>
        </div>

        {{-- IMPORT --}}
        <div class="bg-white dark:bg-gray-900 border rounded-xl p-6 shadow space-y-4">
            <h2 class="text-lg font-semibold">📥 Импорт</h2>

            <form method="POST"
                  action="{{ route('admin.newsio.import') }}"
                  enctype="multipart/form-data"
                  class="space-y-4"
                  x-data="{
                    summary: null,
                    loading: false,
                    error: null,
                    async runDryRun(e) {
                        this.error = null; this.summary = null; this.loading = true;
                        const form = e.target.closest('form');
                        const data = new FormData(form);
                        try {
                            const res = await fetch('{{ route('admin.newsio.dryrun') }}', {
                                method: 'POST',
                                body: data,
                                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
                            });
                            if (!res.ok) throw new Error('HTTP '+res.status);
                            const json = await res.json();
                            this.summary = json.preview || json; // на всякий случай
                        } catch (err) {
                            this.error = 'Ошибка проверки: ' + (err?.message || err);
                        } finally {
                            this.loading = false;
                        }
                    }
                  }">
                @csrf

                <input type="file" name="file" accept=".json,.txt,.csv,.zip"
                       class="border rounded px-3 py-2 w-full" required>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div>
                        <label class="block text-sm mb-1">Обновлять по</label>
                        <select name="update_by" class="border rounded px-3 py-2 w-full">
                            <option value="slug">slug</option>
                            <option value="id">id</option>
                            <option value="none">не обновлять (всегда создавать)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm mb-1">Категории сопоставлять по</label>
                        <select name="match_category_by" class="border rounded px-3 py-2 w-full">
                            <option value="id">id</option>
                            <option value="slug">slug</option>
                            <option value="title">title</option>
                        </select>
                    </div>
                    <label class="flex items-center gap-2 mt-6">
                        <input type="checkbox" name="create_missing_cats" value="1" class="rounded border-gray-400">
                        <span class="text-sm">Создавать новые категории</span>
                    </label>
                </div>

                <div class="flex gap-3 items-center">
                    <button type="button"
                            class="px-3 py-2 rounded border"
                            @click="runDryRun($event)"
                            :disabled="loading">
                        <span x-show="!loading">Проверить (dry-run)</span>
                        <span x-show="loading">Проверка…</span>
                    </button>

                    <button class="bg-blue-600 text-white px-4 py-2 rounded"
                            :disabled="loading">
                        Импортировать
                    </button>
                </div>

                {{-- Результат dry-run --}}
                <template x-if="error">
                    <div class="p-3 rounded bg-red-100 text-red-800 text-sm" x-text="error"></div>
                </template>

                <template x-if="summary">
                    <div class="p-3 rounded bg-yellow-50 border text-sm leading-6">
                        <div class="font-semibold mb-1">✅ Проверка завершена. Готово к импорту.</div>
                        <div class="grid grid-cols-2 gap-x-6 gap-y-1">
                            <div>Всего записей: <span class="font-medium" x-text="summary.total"></span></div>
                            <div>С slug: <span class="font-medium" x-text="summary.with_slug"></span></div>
                            <div>С id: <span class="font-medium" x-text="summary.with_id"></span></div>
                            <div>Ссылок на категории: <span class="font-medium" x-text="summary.cats_refs"></span></div>
                            <div>Категории по id: <span class="font-medium" x-text="summary.cats_by_id"></span></div>
                            <div>Категории по slug: <span class="font-medium" x-text="summary.cats_by_slug"></span></div>
                            <div>Категории по title: <span class="font-medium" x-text="summary.cats_by_title"></span></div>
                            <div>Обновлять по: <span class="font-medium" x-text="summary.update_by"></span></div>
                            <div>Сопоставлять категории по: <span class="font-medium" x-text="summary.match_by"></span></div>
                        </div>
                    </div>
                </template>

                <p class="text-xs text-gray-500">
                    Поддерживаемые форматы: JSON / NDJSON / CSV / ZIP (manifest.json + media/*).<br>
                    В JSON объект новости может содержать: id, slug, title, content, template, published, cover, price, stock, is_promo, categories: [{id|slug|title}].<br>
                    В CSV поле <code>categories</code> — список через запятую (slug), например: <code>news,updates</code>.
                </p>
            </form>
        </div>
    </div>
@endsection

@extends('layouts.admin')

@section('title', $theme->exists ? 'Редактировать тему' : 'Создать тему')

@section('content')
  <h1 class="text-2xl font-bold mb-6">
      {{ $theme->exists ? 'Редактировать' : 'Создать' }} тему
  </h1>

  <form method="POST"
        action="{{ $theme->exists
            ? route('admin.visual.themes.update', $theme)
            : route('admin.visual.themes.store') }}"
        class="space-y-4">
    @csrf
    @if($theme->exists) @method('PUT') @endif

    <div class="space-y-4">
      {{-- Название --}}
      <input type="text" name="title"
             value="{{ old('title',$theme->title) }}"
             placeholder="Название"
             class="border rounded px-3 py-2 w-full">

      {{-- Slug --}}
      <input type="text" name="slug"
             value="{{ old('slug',$theme->slug) }}"
             placeholder="Slug"
             class="border rounded px-3 py-2 w-full">

      {{-- Tokens JSON --}}
      <textarea name="tokens"
                placeholder="Tokens JSON"
                class="border rounded px-3 py-2 w-full h-32 font-mono">{{ old('tokens', json_encode($theme->tokens ?? [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) }}</textarea>

      {{-- Config JSON --}}
      <textarea name="config"
                placeholder="Config JSON"
                class="border rounded px-3 py-2 w-full h-32 font-mono">{{ old('config', json_encode($theme->config ?? [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) }}</textarea>
    </div>

    {{-- Кнопки управления --}}
    <div class="flex gap-3 mt-4">
        {{-- Сохранить --}}
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">
            💾 Сохранить
        </button>

        @if($theme->exists)
            {{-- Применить тему --}}
            <form action="{{ route('admin.visual.themes.apply', $theme) }}"
                  method="POST"
                  onsubmit="return confirm('Сделать эту тему активной?')">
                @csrf
                @method('PATCH')
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">
                    🎨 Применить
                </button>
            </form>

            {{-- Удалить тему --}}
            <form action="{{ route('admin.visual.themes.destroy', $theme) }}"
                  method="POST"
                  onsubmit="return confirm('Удалить тему?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded">
                    🗑 Удалить
                </button>
            </form>
        @endif
    </div>
  </form>
@endsection

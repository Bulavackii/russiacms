@extends('layouts.admin')
@section('title', $theme->exists ? 'Редактировать тему' : 'Создать тему')

@section('content')
<h1 class="text-2xl font-bold mb-6">
  {{ $theme->exists ? 'Редактировать' : 'Создать' }} тему
</h1>

@if ($errors->any())
  <div class="mb-4 p-3 bg-red-50 text-red-700 rounded">
    <ul class="list-disc ml-5">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
  </div>
@endif

<form id="themeForm" method="POST" enctype="multipart/form-data"
      action="{{ $theme->exists ? route('admin.visual.themes.update',$theme) : route('admin.visual.themes.store') }}"
      class="grid grid-cols-1 lg:grid-cols-3 gap-6">
  @csrf
  @if($theme->exists) @method('PUT') @endif

  <div class="lg:col-span-2 space-y-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div>
        <label class="block text-sm font-medium mb-1">Название</label>
        <input type="text" name="title" class="border rounded px-3 py-2 w-full" value="{{ old('title',$theme->title) }}" required>
      </div>
      <div>
        <label class="block text-sm font-medium mb-1">Slug</label>
        <input type="text" name="slug" class="border rounded px-3 py-2 w-full" value="{{ old('slug',$theme->slug) }}" required>
      </div>
    </div>

    @php $t = $theme->tokens ?? []; $cfg = $theme->config ?? []; @endphp

    <div>
      <h3 class="font-semibold mb-2">Цвета</h3>
      <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
        <x-color name="tokens[colors][bg]"      label="Фон сайта"  :value="old('tokens.colors.bg',     data_get($t,'colors.bg','#ffffff'))" />
        <x-color name="tokens[colors][text]"    label="Текст"      :value="old('tokens.colors.text',   data_get($t,'colors.text','#111827'))" />
        <x-color name="tokens[colors][primary]" label="Primary"    :value="old('tokens.colors.primary',data_get($t,'colors.primary','#2563eb'))" />
        <x-color name="tokens[colors][accent]"  label="Accent"     :value="old('tokens.colors.accent', data_get($t,'colors.accent','#10b981'))" />
        <x-color name="tokens[colors][header]"  label="Header фон" :value="old('tokens.colors.header', data_get($t,'colors.header','#ffffff'))" />
        <x-color name="tokens[colors][footer]"  label="Footer фон" :value="old('tokens.colors.footer', data_get($t,'colors.footer','#ffffff'))" />
      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div>
        <label class="block text-sm font-medium mb-1">Шрифт (base, локальный фолбэк)</label>
        @php $font = old('tokens.font.base', data_get($t,'font.base','Inter, system-ui, sans-serif')); @endphp
        <select name="tokens[font][base]" class="border rounded px-3 py-2 w-full" id="fontBase">
          @php
            $presetFonts = [
              'Inter, system-ui, sans-serif' => 'Inter',
              'Roboto, system-ui, sans-serif' => 'Roboto',
              'Open Sans, system-ui, sans-serif' => 'Open Sans',
              'Lato, system-ui, sans-serif' => 'Lato',
              'Montserrat, system-ui, sans-serif' => 'Montserrat',
              'Poppins, system-ui, sans-serif' => 'Poppins',
              'Nunito, system-ui, sans-serif' => 'Nunito',
              'Source Sans 3, system-ui, sans-serif' => 'Source Sans 3',
              'Merriweather, serif' => 'Merriweather',
              'Playfair Display, serif' => 'Playfair Display',
              'PT Sans, system-ui, sans-serif' => 'PT Sans',
              'PT Serif, serif' => 'PT Serif',
              'Ubuntu, system-ui, sans-serif' => 'Ubuntu',
              'Fira Sans, system-ui, sans-serif' => 'Fira Sans',
              'Oswald, system-ui, sans-serif' => 'Oswald',
              'Raleway, system-ui, sans-serif' => 'Raleway',
              'Rubik, system-ui, sans-serif' => 'Rubik',
              'Noto Sans, system-ui, sans-serif' => 'Noto Sans',
              'Noto Serif, serif' => 'Noto Serif',
              'Manrope, system-ui, sans-serif' => 'Manrope',
              'Work Sans, system-ui, sans-serif' => 'Work Sans',
              'Quicksand, system-ui, sans-serif' => 'Quicksand',
              'Anton, system-ui, sans-serif' => 'Anton',
              'Lobster, cursive' => 'Lobster',
              'Comfortaa, system-ui, sans-serif' => 'Comfortaa',
              'Exo 2, system-ui, sans-serif' => 'Exo 2',
              'Jura, system-ui, sans-serif' => 'Jura',
              'Arimo, system-ui, sans-serif' => 'Arimo',
              'Comic Sans MS, cursive, sans-serif' => 'Comic Sans',
              'Tahoma, system-ui, sans-serif' => 'Tahoma',
              'Georgia, serif' => 'Georgia',
            ];
          @endphp
          @foreach($presetFonts as $val => $label)
            <option value="{{ $val }}" @selected($font===$val)>{{ $label }}</option>
          @endforeach
        </select>

        <div class="mt-3 grid grid-cols-1 sm:grid-cols-2 gap-3">
          <div>
            <label class="block text-xs mb-1">Загрузить .woff2 (локально)</label>
            <input type="file" name="font_woff2" accept=".woff2" class="block w-full text-sm">
          </div>
          <div>
            <label class="block text-xs mb-1">Загрузить .ttf</label>
            <input type="file" name="font_ttf" accept=".ttf,.otf" class="block w-full text-sm">
          </div>
        </div>

        <div class="mt-3">
          <label class="block text-sm font-medium mb-1">Онлайн-шрифт (опционально)</label>
          @php
            $provider = old('config.font_provider', data_get($cfg,'font_provider'));
            $fname    = old('config.font_name',     data_get($cfg,'font_name'));
          @endphp
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <select name="config[font_provider]" class="border rounded px-3 py-2 w-full">
              <option value="">— не использовать —</option>
              <option value="google" @selected($provider==='google')>Google Fonts</option>
              <option value="bunny"  @selected($provider==='bunny')>Bunny Fonts</option>
            </select>
            <input type="text" name="config[font_name]" class="border rounded px-3 py-2 w-full"
                   placeholder="Напр. Inter, Roboto…" value="{{ $fname }}">
          </div>
          <p class="text-xs text-gray-500 mt-1">Если указан онлайн-шрифт, он будет подключён и использован поверх локального.</p>
        </div>
      </div>

      <div>
        <label class="block text-sm font-medium mb-1">Скругление (md)</label>
        @php $radius = old('tokens.radius.md', data_get($t,'radius.md','12px')); @endphp
        <input type="range" min="0" max="24" step="1" value="{{ (int) $radius }}" id="radiusSlider" class="w-full">
        <input type="text" name="tokens[radius][md]" id="radiusValue" class="border rounded px-3 py-2 w-full mt-2"
               value="{{ $radius }}" placeholder="напр. 12px">
      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div>
        <label class="block text-sm font-medium mb-1">Логотип</label>
        <input type="file" name="logo" accept="image/*" class="block w-full text-sm">
        @if($url = data_get($cfg,'logo_url'))
          <img src="{{ $url }}" alt="logo" class="mt-2 h-12 object-contain">
        @endif
      </div>

      <div>
        <label class="block text-sm font-medium mb-1">Фон (повторяющийся паттерн)</label>
        <input type="file" name="bg_image" accept="image/*" class="block w-full text-sm">
        @if($url = data_get($cfg,'background_url'))
          <div class="mt-2 text-xs text-gray-500 truncate">{{ $url }}</div>
        @endif
      </div>

      <div>
        <label class="block text-sm font-medium mb-1">Иконки (ZIP с SVG)</label>
        <input type="file" name="icons_zip" accept=".zip" class="block w-full text-sm">
        @if($p = data_get($cfg,'icons_path'))
          <div class="mt-2 text-xs text-gray-500">Распакованы: {{ $p }}</div>
        @endif
      </div>

      <div>
        <label class="block text-sm font-medium mb-1">Режим иконок</label>
        @php $iconMode = old('config.icon_mode', data_get($cfg,'icon_mode','fa')); @endphp
        <select name="config[icon_mode]" class="border rounded px-3 py-2 w-full">
          <option value="fa"        @selected($iconMode==='fa')>Font Awesome (CDN)</option>
          <option value="bootstrap" @selected($iconMode==='bootstrap')>Bootstrap Icons (CDN)</option>
          <option value="tabler"    @selected($iconMode==='tabler')>Tabler Icons (CDN)</option>
          <option value="remix"     @selected($iconMode==='remix')>Remix Icons (CDN)</option>
          <option value="lucide"    @selected($iconMode==='lucide')>Lucide (JS)</option>
          <option value="heroicons" @selected($iconMode==='heroicons')>Heroicons</option>
          <option value="svg"       @selected($iconMode==='svg')>Локальные SVG из ZIP</option>
        </select>
      </div>
    </div>

    <div>
      <label class="block text-sm font-medium mb-1">Дополнительный CSS</label>
      <textarea name="config[css]" class="border rounded px-3 py-2 w-full font-mono h-28"
        placeholder="Необязательно.">{!! old('config.css', data_get($cfg,'css','')) !!}</textarea>
    </div>

    <div class="flex gap-3">
      <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">💾 Сохранить</button>

      @if($theme->exists)
        <button type="button" class="bg-green-600 text-white px-4 py-2 rounded"
                onclick="document.getElementById('applyForm').submit()">🎨 Применить</button>
        <button type="button" class="bg-red-600 text-white px-4 py-2 rounded"
                onclick="if(confirm('Удалить тему?')) document.getElementById('deleteForm').submit()">🗑 Удалить</button>
      @endif
    </div>
  </div>

  {{-- Превью --}}
  <aside class="lg:col-span-1">
    <div class="sticky top-4">
      <h3 class="font-semibold mb-3">Превью</h3>
      <div id="preview" class="rounded-lg border p-4"
           style="background: var(--color-bg,#fff); color: var(--color-text,#111827); font-family: var(--font-base,Inter,system-ui,sans-serif)">
        <div class="flex items-center justify-between py-2 px-3 rounded mb-3" style="background: var(--color-header,#fff)">
          <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-md flex items-center justify-center text-white" style="background: var(--color-primary,#2563eb)">R</div>
            <div class="font-bold">RU CMS</div>
          </div>
          <nav class="text-sm flex gap-3"><span>Главная</span><span>О нас</span><span>Контакты</span></nav>
        </div>
        <h4 class="text-lg font-semibold mb-2">Заголовок</h4>
        <p class="text-sm mb-3 opacity-80">Пример текста в текущей теме. Кнопка ниже использует primary.</p>
        <button class="px-3 py-1 rounded text-white" style="background: var(--color-primary,#2563eb)">Кнопка</button>
        <div class="mt-4 py-2 px-3 rounded" style="background: var(--color-footer,#fff)">Футер</div>
      </div>
    </div>
  </aside>
</form>

@if($theme->exists)
  <form id="applyForm" method="POST" action="{{ route('admin.visual.themes.apply',$theme) }}" class="hidden">@csrf @method('PATCH')</form>
  <form id="deleteForm" method="POST" action="{{ route('admin.visual.themes.destroy',$theme) }}" class="hidden">@csrf @method('DELETE')</form>
@endif

@push('scripts')
<script>
  window.__syncThemeVars = function(){
    const root = document.querySelector('#preview');
    const get = (sel, def) => (document.querySelector(`[name="${sel}"]`)?.value || def);
    const setVar = (name, value) => root.style.setProperty(name, value);

    setVar('--color-bg',     get('tokens[colors][bg]','#ffffff'));
    setVar('--color-text',   get('tokens[colors][text]','#111827'));
    setVar('--color-primary',get('tokens[colors][primary]','#2563eb'));
    setVar('--color-accent', get('tokens[colors][accent]','#10b981'));
    setVar('--color-header', get('tokens[colors][header]','#ffffff'));
    setVar('--color-footer', get('tokens[colors][footer]','#ffffff'));

    setVar('--font-base', document.getElementById('fontBase')?.value || 'Inter, system-ui, sans-serif');

    const r = document.getElementById('radiusValue')?.value || '12px';
    setVar('--radius-md', r);
    root.querySelectorAll('button, .rounded, .rounded-md, .rounded-lg')
        .forEach(el => el.style.borderRadius = r);
  };

  const slider = document.getElementById('radiusSlider');
  const rVal   = document.getElementById('radiusValue');
  if (slider && rVal) {
    slider.addEventListener('input', () => { rVal.value = slider.value + 'px'; window.__syncThemeVars(); });
    rVal.addEventListener('input', () => { const n = parseInt(rVal.value)||0; slider.value = n; window.__syncThemeVars(); });
  }
  document.addEventListener('input', e => {
    if (e.target.matches('input[type="color"], input[type="text"], select')) window.__syncThemeVars();
  });
  window.__syncThemeVars();
</script>
@endpush
@endsection

@extends('layouts.admin')
@section('title','Фрагменты')

@section('content')
  <h1 class="text-2xl font-bold mb-4">🧩 Фрагменты</h1>

  @php
    use Modules\Visual\Models\Fragment;

    // Проверяем, есть ли уже служебные фрагменты
    $existsHeader = Fragment::where('slug','site-header')->exists();
    $existsFooter = Fragment::where('slug','site-footer')->exists();

    // Общие классы для кнопок
    $btnBase      = 'inline-flex items-center justify-center px-4 py-2 rounded font-medium shadow transition';
    $btnPrimary   = 'text-white bg-blue-600 hover:bg-blue-700';
    $btnSecondary = 'text-white bg-indigo-600 hover:bg-indigo-700';
    $btnBorder    = 'border bg-white text-gray-800 hover:bg-gray-50';
    $btnDisabled  = 'cursor-not-allowed bg-gray-300 text-gray-600';
  @endphp

  {{-- Блок быстрых действий --}}
  <div class="mb-6 rounded border bg-white dark:bg-gray-900 p-4 flex flex-wrap gap-3">
    {{-- Создать шапку --}}
    @if(!$existsHeader)
      <a href="{{ route('admin.visual.fragments.create', ['preset'=>'header']) }}"
         class="{{ $btnBase }} {{ $btnPrimary }}">
        Создать шапку (site-header)
      </a>
    @else
      <span class="{{ $btnBase }} {{ $btnDisabled }}" aria-disabled="true" title="Шапка уже создана">
        Создать шапку (site-header)
      </span>
    @endif

    {{-- Создать подвал --}}
    @if(!$existsFooter)
      <a href="{{ route('admin.visual.fragments.create', ['preset'=>'footer']) }}"
         class="{{ $btnBase }} {{ $btnSecondary }}">
        Создать подвал (site-footer)
      </a>
    @else
      <span class="{{ $btnBase }} {{ $btnDisabled }}" aria-disabled="true" title="Подвал уже создан">
        Создать подвал (site-footer)
      </span>
    @endif

    {{-- Обычный новый фрагмент --}}
    <a href="{{ route('admin.visual.fragments.create') }}"
       class="{{ $btnBase }} {{ $btnBorder }}">
      Новый фрагмент
    </a>
  </div>

  {{-- Список фрагментов --}}
  @if($fragments->count())
    <table class="w-full text-sm border rounded overflow-hidden">
      <thead class="bg-gray-50">
        <tr class="border-b">
          <th class="text-left py-2 px-3">Заголовок</th>
          <th class="text-left py-2 px-3">Slug</th>
          <th class="text-left py-2 px-3">Зона</th>
          <th class="text-left py-2 px-3">Статус</th>
          <th class="py-2 px-3 text-right">Действия</th>
        </tr>
      </thead>
      <tbody>
        @foreach($fragments as $f)
          <tr class="border-b hover:bg-gray-50">
            <td class="py-2 px-3">{{ $f->title }}</td>
            <td class="py-2 px-3 font-mono text-xs">{{ $f->slug }}</td>
            <td class="py-2 px-3">{{ $f->zone ?: '—' }}</td>
            <td class="py-2 px-3">
              @if($f->is_active)
                <span class="px-2 py-1 text-xs rounded bg-green-100 text-green-700">Активен</span>
              @else
                <span class="px-2 py-1 text-xs rounded bg-gray-200 text-gray-600">Выключен</span>
              @endif
            </td>
            <td class="py-2 px-3 text-right space-x-2">
              <a href="{{ route('admin.visual.fragments.edit',$f) }}"
                 class="text-blue-600 hover:underline">Редактировать</a>

              {{-- Пересобрать HTML --}}
              <form action="{{ route('admin.visual.fragments.rebuild',$f) }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="text-gray-600 hover:text-black"
                        onclick="return confirm('Пересобрать HTML для {{ $f->title }}?')">
                  🔄
                </button>
              </form>

              {{-- Удалить --}}
              <form action="{{ route('admin.visual.fragments.destroy',$f) }}" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-600 hover:underline"
                        onclick="return confirm('Удалить фрагмент {{ $f->title }}?')">
                  Удалить
                </button>
              </form>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>

    <div class="mt-4">{{ $fragments->links() }}</div>
  @else
    <p class="text-gray-600">Фрагментов пока нет.</p>
  @endif
@endsection

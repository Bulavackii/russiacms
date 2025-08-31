@extends('layouts.admin')
@section('title','Редактор фрагмента')
@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
  <div class="lg:col-span-1 space-y-4">
    {{-- Панель настроек (алпайн или React-контролы) --}}
  </div>
  <div class="lg:col-span-2">
    {{-- Mount-точка визуального редактора (React) --}}
    <div id="visual-editor" data-fragment='@json($fragment)' data-theme='@json($theme)'></div>
  </div>
</div>
@endsection
@push('scripts')
  @vite('resources/js/visual/editor.tsx')
@endpush

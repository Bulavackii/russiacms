{{-- resources/views/frontend/templates/release.blade.php --}}
<div class="my-12 max-w-screen-xl mx-auto px-4">
    {{-- Заголовок секции --}}
    <h2
        class="text-3xl font-extrabold text-center mb-10 text-gray-800 tracking-tight flex items-center justify-center gap-2 select-none">
        <i class="fas fa-rocket text-blue-600"></i>
        {{ $title ?? 'Релизы' }}
    </h2>

    @php
        // Берём коллекцию релизов (как в FAQ-шаблоне — из $templates['release'])
        $releaseList = $releaseList
            ?? ($templates['release'] ?? collect());
    @endphp

    @if ($releaseList->count())
        <div class="flex flex-wrap justify-center gap-8">
            @foreach ($releaseList as $release)
                @php
                    // Обложка / видео / первая картинка из контента / заглушка
                    $mediaSrc = $release->cover
                        ? asset('storage/' . $release->cover)
                        : (
                            preg_match('/<video[^>]*src="([^"]+)"/i', $release->content, $videoMatch)
                                ? $videoMatch[1]
                                : (
                                    preg_match('/<source[^>]*src="([^"]+)"/i', $release->content, $sourceMatch)
                                        ? $sourceMatch[1]
                                        : (
                                            preg_match('/<img[^>]+src="([^">]+)"/i', $release->content, $imgMatch)
                                                ? $imgMatch[1]
                                                : asset('images/no-image.png')
                                        )
                                )
                        );
                    $isVideo = \Illuminate\Support\Str::endsWith($mediaSrc, ['.mp4', '.webm']);
                @endphp

                {{-- Карточка релиза --}}
                <div
                    class="relative flex flex-col p-5 border border-gray-100 hover:border-gray-300 shadow-md hover:shadow-lg transition-all bg-white rounded-2xl max-w-xs w-full">
                    {{-- 🚀 Бейдж RELEASE --}}
                    <div
                        class="absolute -top-3 right-3 z-10 bg-white border-2 border-blue-600 text-blue-600 text-xs font-bold px-3 py-1 rounded-full shadow-md select-none"
                        title="Релиз">
                        🚀 RELEASE
                    </div>

                    {{-- Категории (если есть) --}}
                    @if ($release->categories?->count())
                        <div class="absolute top-3 left-3 z-10 flex flex-wrap gap-1">
                            @foreach ($release->categories as $category)
                                <a href="{{ url('/?category_' . ($release->template ?? 'release') . '=' . $category->id) }}"
                                   class="bg-blue-100 text-blue-800 text-xs font-semibold px-2 py-1 rounded-full hover:underline select-none"
                                   title="{{ $category->title }}">
                                    {{ $category->title }}
                                </a>
                            @endforeach
                        </div>
                    @endif

                    {{-- Медиа --}}
                    <div class="w-full h-48 overflow-hidden mb-4 rounded-xl border border-gray-200 pt-6 relative">
                        @if ($isVideo)
                            <video class="w-full h-full object-cover rounded-xl" muted autoplay loop playsinline controls>
                                <source src="{{ $mediaSrc }}" type="video/mp4">
                                Ваш браузер не поддерживает видео.
                            </video>
                        @else
                            <img src="{{ $mediaSrc }}" alt="{{ $release->title }}"
                                 class="w-full h-full object-cover rounded-xl" loading="lazy" />
                        @endif
                    </div>

                    {{-- Заголовок --}}
                    <h3 class="text-xl font-semibold text-gray-900 mb-1 leading-tight break-words line-clamp-2">
                        <a href="{{ route('news.show', $release->slug) }}"
                           class="hover:text-blue-600 transition" title="{{ $release->title }}">
                            {{ $release->title }}
                        </a>
                    </h3>

                    {{-- Дата --}}
                    <p class="text-sm text-gray-500 mb-2 flex items-center gap-1 select-none" title="Дата релиза">
                        <i class="far fa-calendar-alt"></i> {{ $release->created_at->format('d.m.Y') }}
                    </p>

                    {{-- Краткое описание --}}
                    <div class="text-sm text-gray-600 mb-3 line-clamp-4 break-words" title="Кратко о релизе">
                        {!! \Illuminate\Support\Str::limit(strip_tags($release->content), 220) !!}
                    </div>

                    {{-- Кнопка без цены/корзины --}}
                    <a href="{{ route('news.show', $release->slug) }}"
                       class="mt-auto block text-center text-sm bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 rounded-lg transition shadow select-none"
                       aria-label="Подробнее о релизе {{ $release->title }}">
                        Подробнее →
                    </a>
                </div>
            @endforeach
        </div>

        {{-- Пагинация --}}
        @if ($releaseList->hasPages())
            <div class="mt-10 w-full flex flex-col items-center justify-center gap-2 select-none" aria-label="Пагинация релизов">
                <div class="text-sm text-gray-500">
                    Показано с <span class="font-semibold">{{ $releaseList->firstItem() }}</span>
                    по <span class="font-semibold">{{ $releaseList->lastItem() }}</span>
                    из <span class="font-semibold">{{ $releaseList->total() }}</span> релизов
                </div>

                <nav class="flex items-center space-x-2 rtl:space-x-reverse" role="navigation">
                    @if ($releaseList->onFirstPage())
                        <span class="px-3 py-1.5 bg-gray-200 text-gray-500 rounded-md text-sm cursor-not-allowed">← Назад</span>
                    @else
                        <a href="{{ $releaseList->previousPageUrl() }}"
                           class="px-3 py-1.5 bg-white border border-gray-300 text-gray-700 hover:bg-gray-100 rounded-md text-sm transition"
                           rel="prev">← Назад</a>
                    @endif

                    @foreach ($releaseList->getUrlRange(1, $releaseList->lastPage()) as $page => $url)
                        @if ($page == $releaseList->currentPage())
                            <span class="px-3 py-1.5 bg-blue-600 text-white rounded-md text-sm font-semibold shadow">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}"
                               class="px-3 py-1.5 bg-white border border-gray-300 text-gray-700 hover:bg-gray-100 rounded-md text-sm transition">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach

                    @if ($releaseList->hasMorePages())
                        <a href="{{ $releaseList->nextPageUrl() }}"
                           class="px-3 py-1.5 bg-white border border-gray-300 text-gray-700 hover:bg-gray-100 rounded-md text-sm transition"
                           rel="next">Вперёд →</a>
                    @else
                        <span class="px-3 py-1.5 bg-gray-200 text-gray-500 rounded-md text-sm cursor-not-allowed">Вперёд →</span>
                    @endif
                </nav>
            </div>
        @endif
    @else
        <p class="text-center text-gray-500 select-none">Пока нет релизов.</p>
    @endif
</div>

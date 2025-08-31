Вот короткая «памятка» от А до Я — как работать с темами в твоём модуле Visual.

1) Создаём тему (в админке)

Зайди: Админка → Темы → Создать (/admin/visual/themes/create).

Заполни поля:

Название – любое (например, RussiaCMS Light).

Slug – латиницей и через дефис/нижнее подчёркивание (например, rucms-light).

Tokens JSON – переменные темы. Пример мини-набора:

{
  "colors": {
    "primary": "#2563eb",
    "accent": "#10b981",
    "bg": "#ffffff",
    "text": "#111827"
  },
  "radius": { "md": "12px" },
  "font":   { "base": "Inter, system-ui, sans-serif" }
}


Config JSON – по желанию (можно оставить {}).

Нажми Сохранить. После сохранения ты попадёшь на форму редактирования этой темы.

Примечание
Если оставляешь поля Tokens/Config пустыми — форма сама подставит [] → контроллер приведёт к пустому объекту {}. Это норм.

2) Применяем тему (делаем активной)

Есть два места, где можно «применить»:

А. Список тем (/admin/visual/themes):
рядом с каждой темой добавлены кнопки:

Применить – вызовет POST /admin/visual/themes/{theme}/apply и сделает её is_default = true (все остальные — false).

Удалить – удалит тему (активную удалить нельзя).

Б. На форме редактирования темы (/admin/visual/themes/{id}/edit):
снизу есть секция «Действия»: кнопка Применить тему, которая делает то же самое.

После нажатия «Применить» контроллер:

транзакционно снимает флаг is_default со всех тем,

ставит is_default = true выбранной теме,

очищает cache ключ active_theme (если ты его где-то используешь).

3) Как понять, что тема реально применена?

Сразу три способа:

Способ 1 — смотрим в HTML страницы (frontend)

Открой любую пользовательскую страницу сайта (не админку), смотри в <head>:

Должен появиться блок стилей с id theme-tokens, например:

<style id="theme-tokens">
:root{
  --color-primary:#2563eb;
  --color-accent:#10b981;
  --color-bg:#ffffff;
  --color-text:#111827;
  --radius-md:12px;
  --font-base:Inter, system-ui, sans-serif;
}
</style>


Это рендерится из твоего resources/views/layouts/app.blade.php, куда мы уже добавили вставку темы:

@if(isset($__activeTheme) && $__activeTheme)
  <style id="theme-tokens">
    :root{
      @foreach(data_get($__activeTheme->tokens,'colors',[]) as $k=>$v)
        --color-{{ $k }}: {{ $v }};
      @endforeach
      @foreach(data_get($__activeTheme->tokens,'radius',[]) as $k=>$v)
        --radius-{{ $k }}: {{ $v }};
      @endforeach
      @foreach(data_get($__activeTheme->tokens,'font',[]) as $k=>$v)
        --font-{{ $k }}: {{ $v }};
      @endforeach
    }
  </style>
@endif


А переменная __activeTheme пробрасывается в компоновщик (view composer) из AppServiceProvider, так что ничего дополнительно делать не нужно.

Способ 2 — проверяем БД

В таблице visual_themes у активной темы поле is_default должно быть 1.
(Если удобно — быстро глянуть в tinker или в админке «Темы»: активная идёт первой и обычно помечена стилем/иконкой.)

Способ 3 — пробуем переменные в CSS

Где-то в стиле/вёрстке используй переменные:

.btn-primary { 
  background: var(--color-primary);
  border-radius: var(--radius-md);
}
body {
  color: var(--color-text);
  background: var(--color-bg);
  font-family: var(--font-base);
}


Если цвета/радиусы/шрифты реально меняются при переключении темы — всё применилось.

4) Частые вопросы / нюансы

Не вижу theme-tokens в <head>
Убедись, что рендеришь пользовательские страницы через лэйаут, где стоит вставка темы (resources/views/layouts/app.blade.php). Если у тебя есть другие лэйауты, добавь туда тот же фрагмент.

Кэш
Мы чистим только cache()->forget('active_theme'). Если используешь агрессивный фронтовый кэш (Cloudflare и т.п.) — обнови страницу «жёстко» (Ctrl+F5) и/или почисть кэш.

Удаление активной темы
Нельзя — сначала «Применить» другую, потом удалить ненужную.

Быстрое тестирование токенов
На форме темы поменяй JSON и жми «Сохранить» → «Применить». На фронте обнови страницу и посмотри <style id="theme-tokens">.

<?php

namespace Modules\Visual\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Modules\Visual\Models\Theme;
use ZipArchive;

class ThemesController extends Controller
{
    /** Диск, куда кладём ассеты тем (публичный). */
    protected string $disk = 'public';

    public function index()
    {
        $themes = Theme::orderByDesc('is_default')->orderBy('title')->get();
        return view('Visual::admin.themes.index', compact('themes'));
    }

    public function create()
    {
        return view('Visual::admin.themes.form', ['theme' => new Theme()]);
    }

    public function store(Request $request)
    {
        $data  = $this->validated($request);
        $theme = new Theme($data);
        $theme->save(); // нужен ID для путей хранения

        $this->handleUploads($request, $theme);
        $this->regenerateCss($theme);
        $theme->save();

        // меняли тему — сбросим только id активной (пересчитается на рендере)
        Cache::forget('active_theme_id');

        return redirect()
            ->route('admin.visual.themes.edit', $theme)
            ->with('success', 'Тема сохранена');
    }

    public function edit(Theme $theme)
    {
        return view('Visual::admin.themes.form', compact('theme'));
    }

    public function update(Request $request, Theme $theme)
    {
        $data = $this->validated($request, $theme->id);
        $theme->fill($data);

        $this->handleUploads($request, $theme);
        $this->regenerateCss($theme);
        $theme->save();

        Cache::forget('active_theme_id');

        return back()->with('success', 'Изменения сохранены');
    }

    /** Сделать тему активной (по умолчанию). */
    public function apply(Theme $theme)
    {
        $this->applyTheme($theme);
        return back()->with('success', 'Тема применена');
    }

    /** Удаление темы. */
    public function destroy(Theme $theme)
    {
        if ($theme->is_default) {
            return back()->with('error', 'Нельзя удалить активную тему');
        }

        // подчистим файлы
        Storage::disk($this->disk)->deleteDirectory("themes/{$theme->id}");
        $deletedId = $theme->id;
        $theme->delete();

        // если удалили активную — сбросить кеш id
        if (Cache::get('active_theme_id') == $deletedId) {
            Cache::forget('active_theme_id');
        }

        return redirect()
            ->route('admin.visual.themes.index')
            ->with('success', 'Тема удалена');
    }

    /* --------------------- ВСПОМОГАТЕЛЬНОЕ --------------------- */

    protected function validated(Request $request, ?int $id = null): array
    {
        $rules = [
            'title' => ['required','string','max:255'],
            'slug'  => ['required','string','max:255','alpha_dash', Rule::unique('visual_themes','slug')->ignore($id)],

            'tokens' => ['nullable'], // массив или json
            'config' => ['nullable'],

            // файлы необязательны
            'logo'        => ['nullable','image'],
            'bg_image'    => ['nullable','image'],
            'icons_zip'   => ['nullable','file','mimes:zip'],
            'font_woff2'  => ['nullable','file','mimes:woff2'],
            'font_ttf'    => ['nullable','file','mimes:ttf,otf'],
        ];

        $data = $request->validate($rules);

        foreach (['tokens','config'] as $jsonField) {
            $val = $data[$jsonField] ?? [];
            if (is_string($val) && $val !== '') {
                $decoded = json_decode($val, true);
                $data[$jsonField] = is_array($decoded) ? $decoded : [];
            } elseif (!is_array($val)) {
                $data[$jsonField] = [];
            }
        }

        unset($data['is_default']); // не даём из формы менять активность

        return $data;
    }

    /**
     * Загрузка ассетов в storage/app/public/themes/{id}
     */
    protected function handleUploads(Request $r, Theme $theme): void
    {
        $disk = $this->disk;
        $base = "themes/{$theme->id}";
        $cfg  = $theme->config ?? [];

        // Логотип
        if ($r->hasFile('logo')) {
            $path = $r->file('logo')->store($base, $disk);
            $cfg['logo_url'] = Storage::disk($disk)->url($path);
        }

        // Фоновая картинка
        if ($r->hasFile('bg_image')) {
            $path = $r->file('bg_image')->store($base, $disk);
            $cfg['background_url'] = Storage::disk($disk)->url($path);
        }

        // Локальные шрифты
        if ($r->hasFile('font_woff2')) {
            $path = $r->file('font_woff2')->store($base, $disk);
            $cfg['font_woff2'] = Storage::disk($disk)->url($path);
        }
        if ($r->hasFile('font_ttf')) {
            $path = $r->file('font_ttf')->store($base, $disk);
            $cfg['font_ttf'] = Storage::disk($disk)->url($path);
        }

        // Иконки (ZIP)
        if ($r->hasFile('icons_zip')) {
            $zipPath  = $r->file('icons_zip')->store($base, $disk);               // themes/{id}/icons.zip
            $extract  = Storage::disk($disk)->path("$base/icons");                // физический путь
            @mkdir($extract, 0775, true);

            $zip = new ZipArchive();
            if ($zip->open(Storage::disk($disk)->path($zipPath)) === true) {
                $zip->extractTo($extract);
                $zip->close();
                $cfg['icons_path'] = Storage::disk($disk)->url("$base/icons");    // /storage/themes/{id}/icons
                $cfg['icon_mode']  = 'svg';
            }
        }

        // Провайдер/название шрифта (онлайн)
        $cfg['font_provider'] = $r->input('config.font_provider', $cfg['font_provider'] ?? null);
        $cfg['font_name']     = $r->input('config.font_name',     $cfg['font_name'] ?? null);

        // Режим иконок
        $cfg['icon_mode']     = $r->input('config.icon_mode', $cfg['icon_mode'] ?? 'fa');

        // Пользовательский CSS
        if ($r->filled('config.css')) {
            $cfg['css'] = $r->input('config.css');
        }

        $theme->config = $cfg;
    }

    /**
     * Генерация CSS-переменных из токенов (поддержка вложенных групп).
     * В config['css'] держим максимум один блок :root{...}
     */
    protected function regenerateCss(Theme $theme): void
    {
        $tokens = $theme->tokens ?? [];
        $css = ':root{';

        // colors.*
        foreach ((array) data_get($tokens, 'colors', []) as $k => $v) {
            if ($v !== null && $v !== '') {
                $css .= "--color-{$k}: {$v};";
            }
        }

        // radius.md
        $css .= '--radius-md: ' . (string) data_get($tokens, 'radius.md', '12px') . ';';

        // font.base
        $fontBase = (string) data_get($tokens, 'font.base', 'Inter, system-ui, sans-serif');
        $css .= '--font-base: ' . $fontBase . ';';

        $css .= '}';

        // Заменяем предыдущий :root на новый, чтобы не плодить дубли
        $cfg  = $theme->config ?? [];
        $prev = (string) ($cfg['css'] ?? '');
        $prev = preg_replace('/\:root\s*\{[^}]*\}\s*/m', '', $prev);
        $cfg['css'] = trim($prev . "\n" . $css);

        $theme->config = $cfg;
    }

    /** Атомарное применение темы + обновление кэша с ID. */
    private function applyTheme(Theme $theme): void
    {
        DB::transaction(function () use ($theme) {
            // Снимем флаг со всех КРОМЕ текущей — быстрее и безопаснее
            Theme::where('id', '!=', $theme->id)->where('is_default', true)->update(['is_default' => false]);

            // Если у текущей уже стоит флаг — ничего не трогаем
            if (!$theme->is_default) {
                $theme->is_default = true;
                $this->regenerateCss($theme); // на всякий случай
                $theme->save();
            }
        });

        Cache::forever('active_theme_id', $theme->id);
        // почистим старый ключ, если где-то остался
        Cache::forget('active_theme');
    }
}

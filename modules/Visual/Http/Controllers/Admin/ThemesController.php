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
        $theme->save();                                   // нужен ID для путей хранения

        $this->handleUploads($request, $theme);
        $this->regenerateCss($theme);
        $theme->save();

        Cache::forget('active_theme');

        return redirect()->route('admin.visual.themes.edit', $theme)
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

        Cache::forget('active_theme');

        return back()->with('success', 'Изменения сохранены');
    }

    /**
     * Сделать тему активной (по умолчанию).
     */
    public function apply(Theme $theme)
    {
        DB::transaction(function () use ($theme) {
            Theme::where('is_default', true)->update(['is_default' => false]);
            $theme->is_default = true;

            // Перегенерируем CSS на всякий случай
            $this->regenerateCss($theme);
            $theme->save();
        });

        Cache::forget('active_theme');

        return back()->with('success', 'Тема применена');
    }

    /**
     * Удаление темы.
     */
    public function destroy(Theme $theme)
    {
        if ($theme->is_default) {
            return back()->with('error', 'Нельзя удалить активную тему');
        }

        // подчистим файлы
        Storage::deleteDirectory("public/themes/{$theme->id}");
        $theme->delete();

        Cache::forget('active_theme');

        return redirect()->route('admin.visual.themes.index')
            ->with('success', 'Тема удалена');
    }

    /* --------------------- ВСПОМОГАТЕЛЬНОЕ --------------------- */

    protected function validated(Request $request, ?int $id = null): array
    {
        $rules = [
            'title' => ['required','string','max:255'],
            'slug'  => ['required','string','max:255','alpha_dash', Rule::unique('visual_themes','slug')->ignore($id)],

            // Токены/конфиг могут быть массивом или JSON-строкой
            'tokens' => ['nullable'],
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

        // Флажок is_default из формы игнорируем
        unset($data['is_default']);

        return $data;
    }

    /**
     * Загрузка ассетов в storage/app/public/themes/{id}
     */
    protected function handleUploads(Request $r, Theme $theme): void
    {
        $dir = "public/themes/{$theme->id}";
        $cfg = $theme->config ?? [];

        if ($r->hasFile('logo')) {
            $path = $r->file('logo')->store("$dir", 'local');
            $cfg['logo_url'] = Storage::url($path);
        }

        if ($r->hasFile('bg_image')) {
            $path = $r->file('bg_image')->store("$dir", 'local');
            $cfg['background_url'] = Storage::url($path);
        }

        if ($r->hasFile('font_woff2')) {
            $path = $r->file('font_woff2')->store("$dir", 'local');
            $cfg['font_woff2'] = Storage::url($path);
        }
        if ($r->hasFile('font_ttf')) {
            $path = $r->file('font_ttf')->store("$dir", 'local');
            $cfg['font_ttf'] = Storage::url($path);
        }

        if ($r->hasFile('icons_zip')) {
            $zipPath = $r->file('icons_zip')->store("$dir", 'local');
            $extract = storage_path("app/$dir/icons");
            @mkdir($extract, 0775, true);

            $zip = new ZipArchive();
            if ($zip->open(storage_path("app/$zipPath")) === true) {
                $zip->extractTo($extract);
                $zip->close();
                $cfg['icons_path'] = asset("storage/themes/{$theme->id}/icons");
                $cfg['icon_mode']  = 'svg';
            }
        }

        // Провайдер/название шрифта (для онлайна)
        $cfg['font_provider'] = $r->input('config.font_provider', $cfg['font_provider'] ?? null);
        $cfg['font_name']     = $r->input('config.font_name', $cfg['font_name'] ?? null);

        // Режим иконок
        $cfg['icon_mode']     = $r->input('config.icon_mode', $cfg['icon_mode'] ?? 'fa');

        // Пользовательский CSS
        if ($r->filled('config.css')) {
            $cfg['css'] = $r->input('config.css');
        }

        $theme->config = $cfg;
    }

    /**
     * Генерация CSS-переменных из токенов (поддержка вложенных групп)
     */
    protected function regenerateCss(Theme $theme): void
    {
        $tokens = $theme->tokens ?? [];
        $css = ':root{';

        // colors.*
        foreach ((array) data_get($tokens, 'colors', []) as $k => $v) {
            $css .= "--color-{$k}: {$v};";
        }

        // radius.md
        $css .= '--radius-md: ' . (string) data_get($tokens, 'radius.md', '12px') . ';';

        // font.base
        $fontBase = (string) data_get($tokens, 'font.base', 'Inter, system-ui, sans-serif');
        $css .= '--font-base: ' . $fontBase . ';';

        $css .= '}';

        $cfg = $theme->config ?? [];
        $cfg['css'] = ($cfg['css'] ?? '') . "\n" . $css;
        $theme->config = $cfg;
    }
}

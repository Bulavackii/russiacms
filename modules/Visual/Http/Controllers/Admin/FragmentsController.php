<?php

namespace Modules\Visual\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Modules\Visual\Models\Fragment;

class FragmentsController extends Controller
{
    public function index()
    {
        $fragments = Fragment::latest()->paginate(20);
        return view('Visual::admin.fragments.index', compact('fragments'));
    }

    /**
     * Поддерживает пресеты ?preset=header|footer для быстрого заполнения.
     * Если фрагмент уже создан — сразу открываем его на редактирование.
     */
    public function create(Request $request)
    {
        $preset = (string) $request->query('preset', '');

        if (in_array($preset, ['header', 'footer'], true)) {
            $slug = $preset === 'header' ? 'site-header' : 'site-footer';
            if ($existing = Fragment::where('slug', $slug)->first()) {
                return redirect()
                    ->route('admin.visual.fragments.edit', $existing)
                    ->with('success', 'Уже создан — открыли на редактирование.');
            }
        }

        $fragment = new Fragment([
            'is_active' => true,
            'type'      => 'blade', // дефолт, чтобы форма уже содержала значение
        ]);

        if ($preset === 'header') {
            $fragment->fill([
                'title' => 'Шапка сайта',
                'slug'  => 'site-header',
                'zone'  => 'header',
            ]);
        } elseif ($preset === 'footer') {
            $fragment->fill([
                'title' => 'Подвал сайта',
                'slug'  => 'site-footer',
                'zone'  => 'footer',
            ]);
        }

        return view('Visual::admin.fragments.editor', compact('fragment'));
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $this->applyReservedGuard($data, null);   // фиксируем зону для системных slug
        $data['updated_by'] = Auth::id();

        // гарантируем, что поле type всегда есть (иначе БД ругнётся)
        $data['type'] = $data['type'] ?? 'blade';

        $fragment = Fragment::create($data);
        $this->renderToCache($fragment);
        $fragment->save();

        return redirect()
            ->route('admin.visual.fragments.edit', $fragment)
            ->with('success', 'Фрагмент создан');
    }

    public function edit(Fragment $fragment)
    {
        return view('Visual::admin.fragments.editor', compact('fragment'));
    }

    public function update(Request $request, Fragment $fragment)
    {
        $data = $this->validated($request, $fragment->id);
        $this->applyReservedGuard($data, $fragment); // фиксируем slug и zone для системных
        $data['updated_by'] = Auth::id();

        // не позволяем "забыть" тип
        $data['type'] = $data['type'] ?? ($fragment->type ?: 'blade');

        $fragment->fill($data);
        $this->renderToCache($fragment);
        $fragment->save();

        return back()->with('success', 'Фрагмент обновлён');
    }

    public function destroy(Fragment $fragment)
    {
        $fragment->delete();
        return back()->with('success', 'Фрагмент удалён');
    }

    /**
     * Кнопка «Пересобрать HTML» без изменения данных.
     */
    public function rebuild(Fragment $fragment)
    {
        $this->renderToCache($fragment);
        $fragment->save();

        return back()->with('success', 'HTML фрагмента пересобран');
    }

    /**
     * Компиляция HTML: сначала blade-парциал visual/fragments/{slug}.blade.php,
     * потом html_cached, иначе простой fallback.
     */
    protected function renderToCache(Fragment $fragment): void
    {
        $viewName = 'visual.fragments.' . $fragment->slug;

        if (View::exists($viewName)) {
            $html = view($viewName, ['fragment' => $fragment])->render();
            $fragment->html_cached = $html;
        } else {
            $title = e($fragment->title);
            $fragment->html_cached =
                "<div class=\"visual-fragment\" data-fragment=\"{$fragment->slug}\"><strong>{$title}</strong></div>";
        }
    }

    /**
     * Правила валидации + аккуратное приведение типов.
     */
    protected function validated(Request $request, ?int $id = null): array
    {
        $rules = [
            'title'       => ['required', 'string', 'max:255'],
            'slug'        => ['required', 'string', 'max:255', 'alpha_dash', Rule::unique('visual_fragments', 'slug')->ignore($id)],
            'zone'        => ['nullable', Rule::in(['header', 'footer', 'custom'])],
            'type'        => ['nullable', 'string', 'max:100'],   // может не прийти из формы
            'is_active'   => ['sometimes', 'boolean'],
            // schema/data могут прийти строкой JSON или массивом
            'schema'      => ['nullable'],
            'data'        => ['nullable'],
            // дополнительные поля оставляем «как есть»
            'css_inline'  => ['nullable', 'string'],
            'html_cached' => ['nullable', 'string'],
        ];

        $data = $request->validate($rules);

        // Нормализуем schema/data в массивы
        foreach (['schema', 'data'] as $jsonField) {
            if (!array_key_exists($jsonField, $data)) {
                $data[$jsonField] = [];
                continue;
            }
            if (is_string($data[$jsonField]) && $data[$jsonField] !== '') {
                $decoded = json_decode($data[$jsonField], true);
                $data[$jsonField] = is_array($decoded) ? $decoded : [];
            } elseif (!is_array($data[$jsonField])) {
                $data[$jsonField] = [];
            }
        }

        // По умолчанию включаем фрагмент
        $data['is_active'] = (bool) ($data['is_active'] ?? true);

        return $data;
    }

    /**
     * Закрепляем slug/zone для системных фрагментов.
     *
     * @param array         $data     данные формы (по ссылке)
     * @param Fragment|null $existing текущая модель при update, null при store
     */
    protected function applyReservedGuard(array &$data, ?Fragment $existing = null): void
    {
        // Если редактируем существующий системный — запретим менять slug
        if ($existing && in_array($existing->slug, ['site-header', 'site-footer'], true)) {
            $data['slug'] = $existing->slug;
        }

        // Определим какой slug должен быть закреплён
        $slug = $data['slug'] ?? ($existing?->slug);

        // Для системных слуг всегда правильная зона
        if ($slug === 'site-header') {
            $data['zone'] = 'header';
        } elseif ($slug === 'site-footer') {
            $data['zone'] = 'footer';
        }
    }
}

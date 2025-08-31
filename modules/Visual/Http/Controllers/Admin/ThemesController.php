<?php

namespace Modules\Visual\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Modules\Visual\Models\Theme;

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
        $data = $this->validated($request);
        $theme = Theme::create($data);

        return redirect()->route('admin.visual.themes.edit', $theme);
    }

    public function edit(Theme $theme)
    {
        return view('Visual::admin.themes.form', compact('theme'));
    }

    public function update(Request $request, Theme $theme)
    {
        $data = $this->validated($request, $theme->id);
        $theme->update($data);

        return back();
    }

    /**
     * Сделать тему активной (по умолчанию).
     */
    public function apply(Theme $theme)
    {
        DB::transaction(function () use ($theme) {
            Theme::where('is_default', true)->update(['is_default' => false]);
            $theme->is_default = true;
            $theme->save();
        });

        cache()->forget('active_theme');

        return back();
    }

    /**
     * Удаление темы. Активную удалять нельзя (чтобы сайт не «оголился»).
     */
    public function destroy(Theme $theme)
    {
        if ($theme->is_default) {
            return back();
        }

        $theme->delete();

        return back();
    }

    /* --------------------- ВСПОМОГАТЕЛЬНОЕ --------------------- */

    protected function validated(Request $request, ?int $id = null): array
    {
        $rules = [
            'title'   => ['required','string','max:255'],
            'slug'    => [
                'required','string','max:255','alpha_dash',
                Rule::unique('visual_themes','slug')->ignore($id),
            ],
            'tokens'  => ['nullable'],
            'config'  => ['nullable'],
        ];

        $data = $request->validate($rules);

        foreach (['tokens','config'] as $jsonField) {
            if (isset($data[$jsonField])) {
                if (is_string($data[$jsonField]) && $data[$jsonField] !== '') {
                    $decoded = json_decode($data[$jsonField], true);
                    $data[$jsonField] = is_array($decoded) ? $decoded : [];
                } elseif (!is_array($data[$jsonField])) {
                    $data[$jsonField] = [];
                }
            } else {
                $data[$jsonField] = [];
            }
        }

        unset($data['is_default']);

        return $data;
    }
}

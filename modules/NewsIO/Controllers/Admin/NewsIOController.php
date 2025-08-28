<?php

namespace Modules\NewsIO\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Modules\Categories\Models\Category;
use Modules\NewsIO\Http\Requests\ExportRequest;
use Modules\NewsIO\Http\Requests\ImportRequest;
use Modules\NewsIO\Services\Exporter;
use Modules\NewsIO\Services\Importer;

class NewsIOController extends Controller
{
    public function index()
    {
        // было: ->get(['id','title','slug'])
        $categories = Category::orderBy('title')->get(['id','title']);
        return view('NewsIO::admin.index', compact('categories'));
    }

    public function export(ExportRequest $request, Exporter $exporter)
    {
        $opts = $request->validated();
        $path = $exporter->export($opts); // относительный путь в storage
        return response()->download(Storage::path($path))->deleteFileAfterSend(true);
    }

    public function dryRun(ImportRequest $request, Importer $importer)
    {
        $opts = $request->validated();
        [$preview, $warnings] = $importer->dryRun($opts);
        return response()->json(compact('preview','warnings'));
    }

    public function import(ImportRequest $request, Importer $importer)
    {
        $opts = $request->validated();
        $result = $importer->import($opts);
        return back()->with('success', "Импорт завершён: создано {$result['created']}, обновлено {$result['updated']}");
    }
}

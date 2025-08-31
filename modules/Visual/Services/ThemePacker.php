<?php

namespace Modules\Visual\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Modules\Visual\Models\Theme;
use ZipArchive;

class ThemePacker
{
    public function export(Theme $theme)
    {
        $zip = new ZipArchive();
        $fileName = "themes/{$theme->slug}.zip";
        $path = Storage::path($fileName);

        if ($zip->open($path, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            throw new \RuntimeException('Не удалось создать ZIP');
        }

        $manifest = [
            'theme' => $theme->toArray(),
            'assets' => [], // TODO: подключить файлы при расширении
        ];

        $zip->addFromString('manifest.json', json_encode($manifest, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
        $zip->close();

        return response()->download($path)->deleteFileAfterSend(true);
    }

    public function import(UploadedFile $file)
    {
        $zip = new ZipArchive();
        $zip->open($file->getPathname());

        $manifestStream = $zip->getStream('manifest.json');
        if (!$manifestStream) {
            throw new \RuntimeException('manifest.json not found in ZIP');
        }
        $json = stream_get_contents($manifestStream);
        fclose($manifestStream);
        $data = json_decode($json, true);

        $themeData = $data['theme'];
        Theme::updateOrCreate(
            ['slug' => $themeData['slug']],
            $themeData
        );

        $zip->close();
    }
}

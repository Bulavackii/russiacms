<?php

namespace Modules\NewsIO\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Modules\News\Models\News;

class Exporter
{
    public function export(array $opts): string
    {
        $format     = $opts['format'];
        $withMedia  = (bool)($opts['with_media'] ?? false);
        $chunkSize  = $opts['chunk'] ?? 1000;

        $query = News::query()
            ->with('categories:id,title'); // slug убран

        if (!empty($opts['category_ids'])) {
            $ids = $opts['category_ids'];
            $query->whereHas('categories', fn($q) => $q->whereIn('categories.id', $ids));
        }
        if (!empty($opts['date_from'])) $query->whereDate('created_at', '>=', $opts['date_from']);
        if (!empty($opts['date_to']))   $query->whereDate('created_at', '<=', $opts['date_to']);
        if (($opts['published'] ?? 'all') !== 'all') $query->where('published', (int)$opts['published']);

        $dir  = 'exports/newsio/'.now()->format('Ymd-His');
        Storage::makeDirectory($dir);

        $filename = match ($format) {
            'csv'    => 'news.csv',
            'ndjson' => 'news.ndjson',
            'json'   => 'news.json',
            'zip'    => 'news.zip',
        };
        $path = $dir.'/'.$filename;

        if ($format === 'zip') {
            $manifest = $this->buildJson($query, $chunkSize, true);
            $tmpJson  = $dir.'/manifest.json';
            Storage::put($tmpJson, json_encode($manifest, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));

            $zip = new \ZipArchive();
            $abs = Storage::path($path);
            $zip->open($abs, \ZipArchive::CREATE|\ZipArchive::OVERWRITE);
            $zip->addFile(Storage::path($tmpJson), 'manifest.json');

            if ($withMedia) {
                foreach ($manifest['items'] as $item) {
                    if (!empty($item['cover']) && Storage::disk('public')->exists($item['cover'])) {
                        $zip->addFile(
                            Storage::disk('public')->path($item['cover']),
                            'media/'.basename($item['cover'])
                        );
                    }
                }
            }
            $zip->close();
            Storage::delete($tmpJson);
            return $path;
        }

        if ($format === 'csv') {
            $fh = fopen(Storage::path($path), 'w');
            fputcsv($fh, ['id','slug','title','content','template','published','cover','price','stock','is_promo','categories']);
            $query->orderBy('id')->chunk($chunkSize, function ($items) use ($fh) {
                foreach ($items as $n) {
                    fputcsv($fh, [
                        $n->id,
                        $n->slug,
                        $n->title,
                        $n->content,
                        $n->template,
                        $n->published ? 1 : 0,
                        $n->cover,
                        $n->price,
                        $n->stock,
                        $n->is_promo ? 1 : 0,
                        $n->categories->pluck('id')->implode(','), // вместо slug — id
                    ]);
                }
            });
            fclose($fh);
            return $path;
        }

        if ($format === 'ndjson') {
            $fh = fopen(Storage::path($path), 'w');
            $query->orderBy('id')->chunk($chunkSize, function ($items) use ($fh) {
                foreach ($items as $n) {
                    fwrite($fh, json_encode($this->map($n), JSON_UNESCAPED_UNICODE)."\n");
                }
            });
            fclose($fh);
            return $path;
        }

        $data = $this->buildJson($query, $chunkSize);
        Storage::put($path, json_encode($data, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
        return $path;
    }

    protected function buildJson($query, $chunk, bool $withEnvelope = false): array
    {
        $items = [];
        $query->orderBy('id')->chunk($chunk, function ($page) use (&$items) {
            foreach ($page as $n) $items[] = $this->map($n);
        });
        return $withEnvelope ? ['version' => 1, 'generated_at' => now()->toIso8601String(), 'items' => $items] : $items;
    }

    protected function map(News $n): array
    {
        return [
            'id'         => $n->id,
            'slug'       => $n->slug,
            'title'      => $n->title,
            'content'    => $n->content,
            'template'   => $n->template,
            'published'  => (bool)$n->published,
            'cover'      => $n->cover,
            'price'      => $n->price,
            'stock'      => $n->stock,
            'is_promo'   => (bool)$n->is_promo,
            'created_at' => $n->created_at?->toIso8601String(),
            'updated_at' => $n->updated_at?->toIso8601String(),
            'categories' => $n->categories->map(fn($c) => [
                'id'    => $c->id,
                'title' => $c->title,
            ])->values()->all(),
        ];
    }
}

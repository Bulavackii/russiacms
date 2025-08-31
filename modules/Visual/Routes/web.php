<?php

use Illuminate\Support\Facades\Route;
use Modules\Visual\Http\Controllers\Admin\ThemesController;
use Modules\Visual\Http\Controllers\Admin\FragmentsController;
use Modules\Visual\Http\Controllers\Admin\PreviewController;

Route::middleware(['web', 'auth', 'admin'])
    ->prefix('admin/visual')->name('admin.visual.')
    ->group(function () {

        // Дашборд модуля
        Route::get('/', [ThemesController::class, 'index'])->name('index');

        // --- THEMES ---
        Route::resource('themes', ThemesController::class)->except(['show']);
        Route::post('themes/export', [ThemesController::class, 'export'])->name('themes.export');
        Route::post('themes/import', [ThemesController::class, 'import'])->name('themes.import');

        // ✅ Сделать тему активной (кнопка "Применить")
        Route::patch('themes/{theme}/apply', [ThemesController::class, 'apply'])->name('themes.apply');

        // --- FRAGMENTS ---
        // Пересобрать HTML кеш фрагмента (кнопка в редакторе)
        Route::post('fragments/{fragment}/rebuild', [FragmentsController::class, 'rebuild'])
            ->name('fragments.rebuild');
        Route::resource('fragments', FragmentsController::class)->except(['show']);

        // --- PREVIEW ---
        Route::post('preview/fragment', [PreviewController::class, 'fragment'])->name('preview.fragment');
        Route::post('preview/theme', [PreviewController::class, 'theme'])->name('preview.theme');
    });

<?php

use Illuminate\Support\Facades\Route;
use Modules\Visual\Http\Controllers\Admin\ThemesController;
use Modules\Visual\Http\Controllers\Admin\FragmentsController;
use Modules\Visual\Http\Controllers\Admin\UploadController;

Route::middleware(['web','auth','admin'])
    ->prefix('admin/visual')
    ->name('admin.visual.')
    ->group(function () {

        // ===== ТЕМЫ =====
        Route::prefix('themes')->name('themes.')->group(function () {
            Route::get('/',                 [ThemesController::class,'index'])->name('index');
            Route::get('/create',           [ThemesController::class,'create'])->name('create');
            Route::post('/',                [ThemesController::class,'store'])->name('store');
            Route::get('/{theme}/edit',     [ThemesController::class,'edit'])->name('edit');
            Route::put('/{theme}',          [ThemesController::class,'update'])->name('update');
            Route::patch('/{theme}/apply',  [ThemesController::class,'apply'])->name('apply');
            Route::delete('/{theme}',       [ThemesController::class,'destroy'])->name('destroy');
        });

        // ===== ФРАГМЕНТЫ =====
        Route::prefix('fragments')->name('fragments.')->group(function () {
            Route::get('/',                      [FragmentsController::class,'index'])->name('index');
            Route::get('/create',                [FragmentsController::class,'create'])->name('create');
            Route::post('/',                     [FragmentsController::class,'store'])->name('store');
            Route::get('/{fragment}/edit',       [FragmentsController::class,'edit'])->name('edit');
            Route::put('/{fragment}',            [FragmentsController::class,'update'])->name('update');
            Route::delete('/{fragment}',         [FragmentsController::class,'destroy'])->name('destroy');
            Route::post('/{fragment}/rebuild',   [FragmentsController::class,'rebuild'])->name('rebuild');
        });

        // ===== ЗАГРУЗКИ для TinyMCE =====
        Route::post('/upload/image', [UploadController::class,'image'])->name('upload.image');
    });

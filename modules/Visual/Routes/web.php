<?php

use Illuminate\Support\Facades\Route;
use Modules\Visual\Http\Controllers\Admin\ThemesController;

Route::middleware(['web','auth','admin'])
    ->prefix('admin/visual/themes')
    ->name('admin.visual.themes.')
    ->group(function () {
        Route::get('/',            [ThemesController::class,'index'])->name('index');
        Route::get('/create',      [ThemesController::class,'create'])->name('create');
        Route::post('/',           [ThemesController::class,'store'])->name('store');
        Route::get('/{theme}/edit',[ThemesController::class,'edit'])->name('edit');
        Route::put('/{theme}',     [ThemesController::class,'update'])->name('update');
        Route::patch('/{theme}/apply', [ThemesController::class,'apply'])->name('apply');
        Route::delete('/{theme}',  [ThemesController::class,'destroy'])->name('destroy');
    });

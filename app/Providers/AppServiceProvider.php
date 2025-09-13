<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;

use Modules\System\Models\Module;
use Modules\Notifications\Models\Notification;
use Modules\Notifications\View\Components\Frontend\NotificationsComponent;
use Modules\Accessibility\View\Components\AccessibilityWidget;
use Modules\News\Models\News;
use App\Observers\NewsObserver;
use Modules\Visual\Models\Theme;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Регистрация сервисов при необходимости
    }

    public function boot(): void
    {
        $modulesPath = base_path('modules');

        // ── Установка не завершена → на мастер установки
        View::addNamespace('Install', base_path('modules/Install/Views'));
        $installed = file_exists(storage_path('install.lock'));

        if (!app()->runningInConsole() && !$installed) {
            if (!request()->is('install*')) {
                redirect('/install')->send();
            }
            return; // важно прекратить дальнейшую загрузку
        }

        // ── После установки: синхронизация мета модулей
        if ($installed) {
            $this->syncModuleMetadata();
        }

        // ── Наблюдатели
        News::observe(NewsObserver::class);

        // ── Автозагрузка активных модулей (если таблица есть)
        if ($installed && class_exists(Module::class) && Schema::hasTable('modules')) {
            $activeModules = Module::where('active', true)->pluck('name');

            foreach ($activeModules as $moduleName) {
                $base = $modulesPath . '/' . $moduleName;

                if (!is_dir($base)) {
                    // запись есть, папки нет — подчистим
                    Module::where('name', $moduleName)->delete();
                    continue;
                }

                // 1) маршруты
                if (is_file("{$base}/Routes/web.php")) {
                    $this->loadRoutesFrom("{$base}/Routes/web.php");
                }

                // 2) вьюхи (оба расположения)
                foreach (["{$base}/Views", "{$base}/Resources/views"] as $dir) {
                    if (is_dir($dir)) {
                        $this->loadViewsFrom($dir, $moduleName);
                    }
                }

                // 3) миграции (оба расположения)
                foreach (["{$base}/Migrations", "{$base}/Database/Migrations"] as $dir) {
                    if (is_dir($dir)) {
                        $this->loadMigrationsFrom($dir);
                    }
                }

                // 4) переводы (оба расположения)
                foreach (["{$base}/Lang", "{$base}/Resources/lang"] as $dir) {
                    if (is_dir($dir)) {
                        $this->loadTranslationsFrom($dir, $moduleName);
                    }
                }

                // 5) дополнительные провайдеры из module.json
                $moduleJson = "{$base}/module.json";
                if (is_file($moduleJson)) {
                    try {
                        $meta = json_decode(file_get_contents($moduleJson), true) ?: [];
                        if (!empty($meta['providers']) && is_array($meta['providers'])) {
                            foreach ($meta['providers'] as $providerClass) {
                                if (class_exists($providerClass)) {
                                    $this->app->register($providerClass);
                                }
                            }
                        }
                    } catch (\Throwable $e) {
                        // тихо игнорируем
                    }
                }
            }
        }

        // ── Ручная регистрация некоторых модулей
        if (is_file("{$modulesPath}/Users/Routes/web.php")) {
            $this->loadRoutesFrom("{$modulesPath}/Users/Routes/web.php");
            $this->loadViewsFrom("{$modulesPath}/Users/Views", 'users');
        }

        if (is_file("{$modulesPath}/Search/Routes/web.php")) {
            $this->loadRoutesFrom("{$modulesPath}/Search/Routes/web.php");
            $this->loadViewsFrom("{$modulesPath}/Search/Views", 'Search');
        }

        $this->loadViewsFrom("{$modulesPath}/Categories/Views", 'Categories');
        $this->loadViewsFrom("{$modulesPath}/News/Views", 'News');

        if (is_file("{$modulesPath}/Slideshow/Routes/web.php")) {
            $this->loadRoutesFrom("{$modulesPath}/Slideshow/Routes/web.php");
        }
        if (is_dir("{$modulesPath}/Slideshow/Views")) {
            $this->loadViewsFrom("{$modulesPath}/Slideshow/Views", 'Slideshow');
        }
        if (is_dir("{$modulesPath}/Slideshow/Migrations")) {
            $this->loadMigrationsFrom("{$modulesPath}/Slideshow/Migrations");
        }

        if (is_file("{$modulesPath}/Messages/Routes/web.php")) {
            $this->loadRoutesFrom("{$modulesPath}/Messages/Routes/web.php");
        }
        if (is_dir("{$modulesPath}/Messages/Views")) {
            $this->loadViewsFrom("{$modulesPath}/Messages/Views", 'messages');
        }
        if (is_dir("{$modulesPath}/Messages/Migrations")) {
            $this->loadMigrationsFrom("{$modulesPath}/Messages/Migrations");
        }

        if (is_file("{$modulesPath}/Payments/Routes/web.php")) {
            $this->loadRoutesFrom("{$modulesPath}/Payments/Routes/web.php");
        }
        if (is_dir("{$modulesPath}/Payments/Views")) {
            $this->loadViewsFrom("{$modulesPath}/Payments/Views", 'Payments');
        }
        if (is_dir("{$modulesPath}/Payments/Migrations")) {
            $this->loadMigrationsFrom("{$modulesPath}/Payments/Migrations");
        }

        if (is_file("{$modulesPath}/Delivery/Routes/web.php")) {
            $this->loadRoutesFrom("{$modulesPath}/Delivery/Routes/web.php");
        }
        if (is_dir("{$modulesPath}/Delivery/Views")) {
            $this->loadViewsFrom("{$modulesPath}/Delivery/Views", 'Delivery');
        }
        if (is_dir("{$modulesPath}/Delivery/Migrations")) {
            $this->loadMigrationsFrom("{$modulesPath}/Delivery/Migrations");
        }

        if (is_file("{$modulesPath}/Menu/Routes/web.php")) {
            $this->loadRoutesFrom("{$modulesPath}/Menu/Routes/web.php");
        }
        if (is_dir("{$modulesPath}/Menu/Views")) {
            $this->loadViewsFrom("{$modulesPath}/Menu/Views", 'Menu');
        }
        if (is_dir("{$modulesPath}/Menu/Migrations")) {
            $this->loadMigrationsFrom("{$modulesPath}/Menu/Migrations");
        }

        // ── Компоненты
        if (is_dir("{$modulesPath}/Notifications/Resources/views")) {
            $this->loadViewsFrom("{$modulesPath}/Notifications/Resources/views", 'Notifications');
        }
        Blade::component('frontend-notifications', NotificationsComponent::class);

        if (is_file("{$modulesPath}/Accessibility/Routes/web.php")) {
            $this->loadRoutesFrom("{$modulesPath}/Accessibility/Routes/web.php");
        }
        if (is_dir("{$modulesPath}/Accessibility/Views")) {
            $this->loadViewsFrom("{$modulesPath}/Accessibility/Views", 'Accessibility');
        }
        if (is_dir("{$modulesPath}/Accessibility/Migrations")) {
            $this->loadMigrationsFrom("{$modulesPath}/Accessibility/Migrations");
        }
        Blade::component('accessibility-widget', AccessibilityWidget::class);

        // ── NewsIO
        if (is_file("{$modulesPath}/NewsIO/Routes/web.php")) {
            $this->loadRoutesFrom("{$modulesPath}/NewsIO/Routes/web.php");
        }
        if (is_dir("{$modulesPath}/NewsIO/Views")) {
            $this->loadViewsFrom("{$modulesPath}/NewsIO/Views", 'NewsIO');
        } elseif (is_dir("{$modulesPath}/NewsIO/resources/views")) {
            $this->loadViewsFrom("{$modulesPath}/NewsIO/resources/views", 'NewsIO');
        }
        if (is_dir("{$modulesPath}/NewsIO/Migrations")) {
            $this->loadMigrationsFrom("{$modulesPath}/NewsIO/Migrations");
        }

        // ── Общие данные во вьюхи (без множества повторных запросов)
        $notifications = collect();
        $accessibility = null;

        if ($installed) {
            try {
                // Можно кэшировать, чтобы разгрузить БД (по желанию)
                $notifications = Notification::where('enabled', true)->get();
            } catch (\Throwable $e) {
                $notifications = collect();
            }

            try {
                if (class_exists(\Modules\Accessibility\Models\AccessibilitySetting::class)
                    && Schema::hasTable('accessibility_settings')) {
                    $accessibility = \Modules\Accessibility\Models\AccessibilitySetting::settings();
                }
            } catch (\Throwable $e) {
                $accessibility = null;
            }
        }

        View::share('notifications', $notifications);
        View::share('accessibility', $accessibility);

        // ── Активная тема (переменная должна называться $activeTheme)
        $activeTheme = null;
        try {
            $activeTheme = Cache::remember('active_theme', 3600, function () {
                if (!class_exists(Theme::class) || !Schema::hasTable('visual_themes')) {
                    return null;
                }
                return Theme::where('is_default', true)->first();
            });
        } catch (\Throwable $e) {
            $activeTheme = null;
        }

        View::share('activeTheme', $activeTheme);
        View::share('__activeTheme', $activeTheme); // для совместимости со старыми макетами

        // ── Доп. маршруты API (если нужны)
        if (is_file(base_path('routes/api.php'))) {
            $this->loadRoutesFrom(base_path('routes/api.php'));
        }
    }

    /**
     * Синхронизация title/priority модулей из module.json
     */
    protected function syncModuleMetadata(): void
    {
        $moduleDirectories = File::directories(base_path('modules'));

        foreach ($moduleDirectories as $modulePath) {
            $moduleName     = basename($modulePath);
            $moduleJsonPath = $modulePath . DIRECTORY_SEPARATOR . 'module.json';

            if (!File::exists($moduleJsonPath)) {
                continue;
            }

            try {
                $metadata = json_decode(File::get($moduleJsonPath), true);
            } catch (\Throwable $e) {
                continue;
            }

            if (!is_array($metadata) || !isset($metadata['title'], $metadata['priority'])) {
                continue;
            }

            $module = Module::where('name', $moduleName)->first();
            if (!$module) {
                continue;
            }

            $module->title    = $metadata['title'];
            $module->priority = $metadata['priority'];
            $module->save();
        }
    }
}

<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Blade;
use Modules\System\Models\Module;
use Modules\Notifications\Models\Notification;
use Modules\Notifications\View\Components\Frontend\NotificationsComponent;
use Modules\Accessibility\View\Components\AccessibilityWidget;
use Modules\News\Models\News;
use App\Observers\NewsObserver;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Здесь можно регистрировать сервисы, если потребуется
    }

    public function boot(): void
    {
        $modulesPath = base_path('modules');

        // 👀 Проверка: если установка не завершена
        \View::addNamespace('Install', base_path('modules/Install/Views'));
        if (!app()->runningInConsole() && !file_exists(storage_path('install.lock'))) {
            if (!request()->is('install*')) {
                redirect('/install')->send(); // принудительный редирект
            }
            return; // ⛔ прекращаем загрузку остальных сервисов до завершения установки
        }

        // ✅ Только если установка завершена:
        if (file_exists(storage_path('install.lock'))) {
            // 📦 Синхронизация мета-данных модулей
            $this->syncModuleMetadata();
        }

        // 🧩 Наблюдатель
        News::observe(NewsObserver::class);

        // 🔁 Загрузка активных модулей (если таблица есть и установка завершена)
        if (
            file_exists(storage_path('install.lock')) &&
            class_exists(Module::class) &&
            Schema::hasTable('modules')
        ) {
            $activeModules = Module::where('active', true)->pluck('name');
            foreach ($activeModules as $moduleName) {
                $base = $modulesPath . '/' . $moduleName;
                if (is_dir($base)) {
                    if (file_exists("{$base}/Routes/web.php")) {
                        $this->loadRoutesFrom("{$base}/Routes/web.php");
                    }
                    if (is_dir("{$base}/Views")) {
                        $this->loadViewsFrom("{$base}/Views", $moduleName);
                    }
                    if (is_dir("{$base}/Migrations")) {
                        $this->loadMigrationsFrom("{$base}/Migrations");
                    }
                    if (is_dir("{$base}/Lang")) {
                        $this->loadTranslationsFrom("{$base}/Lang", $moduleName);
                    }
                } else {
                    Module::where('name', $moduleName)->delete(); // 🧹 Чистим записи модулей, которых нет
                }
            }
        }

        // 🔧 Ручная регистрация модулей (работает и без install.lock)
        $this->loadRoutesFrom("{$modulesPath}/Users/Routes/web.php");
        $this->loadViewsFrom("{$modulesPath}/Users/Views", 'users');

        $this->loadRoutesFrom("{$modulesPath}/Search/Routes/web.php");
        $this->loadViewsFrom("{$modulesPath}/Search/Views", 'Search');

        $this->loadViewsFrom("{$modulesPath}/Categories/Views", 'Categories');
        $this->loadViewsFrom("{$modulesPath}/News/Views", 'News');

        $this->loadRoutesFrom("{$modulesPath}/Slideshow/Routes/web.php");
        $this->loadViewsFrom("{$modulesPath}/Slideshow/Views", 'Slideshow');
        $this->loadMigrationsFrom("{$modulesPath}/Slideshow/Migrations");

        $this->loadRoutesFrom("{$modulesPath}/Messages/Routes/web.php");
        $this->loadViewsFrom("{$modulesPath}/Messages/Views", 'messages');
        $this->loadMigrationsFrom("{$modulesPath}/Messages/Migrations");

        $this->loadRoutesFrom("{$modulesPath}/Payments/Routes/web.php");
        $this->loadViewsFrom("{$modulesPath}/Payments/Views", 'Payments');
        $this->loadMigrationsFrom("{$modulesPath}/Payments/Migrations");

        $this->loadRoutesFrom("{$modulesPath}/Delivery/Routes/web.php");
        $this->loadViewsFrom("{$modulesPath}/Delivery/Views", 'Delivery');
        $this->loadMigrationsFrom("{$modulesPath}/Delivery/Migrations");

        $this->loadRoutesFrom("{$modulesPath}/Menu/Routes/web.php");
        $this->loadViewsFrom("{$modulesPath}/Menu/Views", 'Menu');
        $this->loadMigrationsFrom("{$modulesPath}/Menu/Migrations");

        // 🔔 Компоненты уведомлений
        $this->loadViewsFrom("{$modulesPath}/Notifications/Resources/views", 'Notifications');
        Blade::component('frontend-notifications', NotificationsComponent::class);

        // ♿ Accessibility модуль
        $this->loadRoutesFrom("{$modulesPath}/Accessibility/Routes/web.php");
        $this->loadViewsFrom("{$modulesPath}/Accessibility/Views", 'Accessibility');
        if (is_dir("{$modulesPath}/Accessibility/Migrations")) {
            $this->loadMigrationsFrom("{$modulesPath}/Accessibility/Migrations");
        }
        Blade::component('accessibility-widget', AccessibilityWidget::class);

        // 📰 NewsIO — регистрация (вариант 2: через AppServiceProvider)
        $this->loadRoutesFrom("{$modulesPath}/NewsIO/Routes/web.php");
        $this->loadViewsFrom("{$modulesPath}/NewsIO/Views", 'NewsIO'); // <-- хинт для вьюх
        if (is_dir("{$modulesPath}/NewsIO/Migrations")) {
            $this->loadMigrationsFrom("{$modulesPath}/NewsIO/Migrations");
        }
        // поддержка обоих расположений вьюх: "Views" и "resources/views"
        if (is_dir("{$modulesPath}/NewsIO/Views")) {
            $this->loadViewsFrom("{$modulesPath}/NewsIO/Views", 'NewsIO');
        } elseif (is_dir("{$modulesPath}/NewsIO/resources/views")) {
            $this->loadViewsFrom("{$modulesPath}/NewsIO/resources/views", 'NewsIO');
        }
        if (is_dir("{$modulesPath}/NewsIO/Migrations")) {
            $this->loadMigrationsFrom("{$modulesPath}/NewsIO/Migrations");
        }

        // 📩 Глобальные уведомления + доступность
        View::composer('*', function ($view) {
            // Уведомления
            $view->with('notifications', Notification::where('enabled', true)->get());

            // Доступность (без падения, если таблицы нет)
            try {
                if (
                    class_exists(\Modules\Accessibility\Models\AccessibilitySetting::class) &&
                    Schema::hasTable('accessibility_settings')
                ) {
                    $settings = \Modules\Accessibility\Models\AccessibilitySetting::settings();
                    $view->with('accessibility', $settings);
                } else {
                    $view->with('accessibility', null);
                }
            } catch (\Throwable $e) {
                $view->with('accessibility', null);
            }
        });

        // ✅ JWT API маршруты
        if (file_exists(base_path('routes/api.php'))) {
            $this->loadRoutesFrom(base_path('routes/api.php'));
        }
    }

    /**
     * 🔁 Синхронизирует title и priority модулей из module.json
     */
    protected function syncModuleMetadata(): void
    {
        $moduleDirectories = File::directories(base_path('modules'));

        foreach ($moduleDirectories as $modulePath) {
            $moduleName = basename($modulePath);
            $moduleJsonPath = $modulePath . DIRECTORY_SEPARATOR . 'module.json';

            if (!File::exists($moduleJsonPath)) continue;

            try {
                $jsonContent = File::get($moduleJsonPath);
                $metadata = json_decode($jsonContent, true);
            } catch (\Exception $e) {
                continue;
            }

            if (json_last_error() !== JSON_ERROR_NONE || !is_array($metadata)) continue;
            if (!isset($metadata['title']) || !isset($metadata['priority'])) continue;

            $module = Module::where('name', $moduleName)->first();
            if (!$module) continue;

            $module->title = $metadata['title'];
            $module->priority = $metadata['priority'];
            $module->save();
        }
    }
}

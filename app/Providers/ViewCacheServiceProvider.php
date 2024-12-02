<?php

namespace App\Providers;

use App\Types\CacheKeysType;
use Illuminate\Support\ServiceProvider;

class ViewCacheServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $appSettings = $this->getAppSettings();

        view()->composer('*', function ($view) use ($appSettings) {
            $view->with(compact('appSettings'));
        });
    }

    /**
     * Get the application settings from the cache.
     *
     * @return mixed
     */
    private function getAppSettings()
    {
        return app(CacheKeysType::APP_SETTINGS_CACHE);
    }
}
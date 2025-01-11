<?php

namespace App\Providers;

use App\Types\CacheKeysType;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        config()->set('app.timezone', core()->getAppSettings()->timezone ?? 'UTC');
    }
}

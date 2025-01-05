<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/home';
    public const ADMIN_DASHBOARD = '/admin';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();
        $this->namespace = 'App\Http\Controllers';
        $this->routes(function () {
            Route::prefix('api/user')
                ->middleware('api')
                ->namespace($this->namespace . '\Api')
                ->group(base_path('routes/api.php'));

            Route::prefix('api/admin')
                ->middleware('api')
                ->namespace($this->namespace . '\Admin')
                ->group(base_path('routes/admin-api.php'));
                Route::prefix('api/vendor')
                ->middleware('api')
                ->namespace($this->namespace . '\Vendor')
                ->group(base_path('routes/vendor-api.php'));

            // Route::middleware('web')
            //     ->namespace($this->namespace . '\theme')
            //     ->group(base_path('routes/theme.php'));
        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }
}

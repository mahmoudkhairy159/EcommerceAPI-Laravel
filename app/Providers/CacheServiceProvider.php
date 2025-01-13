<?php

namespace App\Providers;

use App\Repositories\AboutUsPageRepository;
use App\Repositories\BrainstormingPageRepository;
use App\Repositories\CareersPageRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\CityRepository;
use App\Repositories\CollaborationPageRepository;
use App\Repositories\ContactUsPageRepository;
use App\Repositories\CountryRepository;
use App\Repositories\DataFusionPageRepository;
use App\Repositories\DigitalRecyclingPageRepository;
use App\Repositories\HomePageRepository;
use App\Repositories\InstitutionsPageRepository;
use App\Repositories\MemberRepository;
use App\Repositories\PartnerRepository;
use App\Repositories\SettingsRepository;
use App\Repositories\StateRepository;
use App\Repositories\TestResultPageRepository;
use App\Types\CacheKeysType;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;

class CacheServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerCacheKeys();
    }

    /**
     * Bind cache keys and their closures to the container.
     *
     * @return void
     */
    private function registerCacheKeys()
    {
        $cacheKeys = $this->getCacheKeys();

        foreach ($cacheKeys as $key => $closure) {
            $this->app->singleton($key, $closure);
        }
    }

    /**
     * Get an array of cache keys and their closures.
     *
     * @return array
     */
    private function getCacheKeys()
    {
        $cacheData = [];
        $countriesCacheKeys = CacheKeysType::getCountriesCacheKeys();
        // Register countries cache
        foreach ($countriesCacheKeys as $countriesCacheKey) {
            $cacheData[$countriesCacheKey] = function () use ($countriesCacheKey) {
                return Cache::remember($countriesCacheKey, now()->addDays(5), function () {
                    $locale = core()->getCurrentLocale();

                    return app(CountryRepository::class)->getAllActive($locale);
                });
            };
        }
        // Static cache definitions for cities, states, and event categories
        $cacheData = array_merge($cacheData, [
                // Cities Cache
            CacheKeysType::CITIES_CACHE => function () {
                return Cache::remember(CacheKeysType::CITIES_CACHE, now()->addDays(5), function () {
                    return app(CityRepository::class)->getAll()->get();
                });
            },

                // States Cache
            CacheKeysType::STATES_CACHE => function () {
                return Cache::remember(CacheKeysType::STATES_CACHE, now()->addDays(5), function () {
                    return app(StateRepository::class)->getAll()->get();
                });
            },
                // app settings Cache
            CacheKeysType::APP_SETTINGS_CACHE => function () {
                return Cache::remember(CacheKeysType::APP_SETTINGS_CACHE, now()->addDays(5), function () {
                    return app(SettingsRepository::class)->getSettings();
                });
            },
                // app settings Cache

                // Categories Tree Structure Cache
            CacheKeysType::CATEGORIES_TREE_CACHE => function () {
                return Cache::remember(CacheKeysType::CATEGORIES_TREE_CACHE, now()->addDays(5), function () {
                    return app(CategoryRepository::class)->getActiveTreeStructure();
                });
            },

        ]);

        return $cacheData;

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Additional bootstrapping if needed
    }
}

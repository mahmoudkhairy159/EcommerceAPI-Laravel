<?php

namespace App\Types;

class CacheKeysType
{
    const APP_SETTINGS_CACHE = "APP_SETTINGS_CACHE";
    const PAYPAL_SETTING_CACHE = "PAYPAL_SETTING_CACHE";
    const STRIPE_SETTING_CACHE = "STRIPE_SETTING_CACHE";


    const CATEGORIES_TREE_CACHE = "CATEGORIES_TREE_CACHE";


      //countries in all languages

      public static function getCountriesCacheKeys(): array
      {
          $supportedLocales = ['en','ar','sv','de','es','fr','zh'];
          $cacheKeys = [];

          foreach ($supportedLocales as $locale) {
              $cacheKeys[$locale] = "COUNTRIES_CACHE_{$locale}";
          }

          return $cacheKeys;
      }
      //countries in all languages

      const CITIES_CACHE = "CITIES_CACHE";
      const STATES_CACHE = "CITIES_CACHE";
      const CITIES_CACHE_PREFIX = "CITIES_CACHE_";
      const STATES_CACHE_PREFIX = "STATES_CACHE_";
       /**
     * Get the cache key for states by country ID.
     *
     * @param int $countryId
     * @return string
     */
    public static function citiesCacheKey(int $countryId): string
    {
        return self::CITIES_CACHE_PREFIX . $countryId;
    }
    public static function statesCacheKey(int $countryId): string
    {
        return self::STATES_CACHE_PREFIX . $countryId;
    }



}

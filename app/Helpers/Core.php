<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;



class Core
{

    public function getSupportedLocales()
    {
        return LaravelLocalization::getSupportedLocales();
    }

    public function getSupportedLanguagesKeys()
    {
        return LaravelLocalization::getSupportedLanguagesKeys();
    }


    public function getCurrentLocaleName()
    {
        return LaravelLocalization::getCurrentLocaleName();
    }


    public function getCurrentLocale()
    {
        return LaravelLocalization::getCurrentLocale();
    }

    public function getLocalesOrder()
    {
        return LaravelLocalization::getLocalesOrder();
    }

    public function getCurrentLocaleDirection()
    {
        return LaravelLocalization::getCurrentLocaleDirection();
    }
    function getPricingPolicy(): string
    {
        /*
        1. Static Pricing: The price and tax are fixed at the time the product is added to the cart,
         meaning any updates to the product's price or tax after the addition will not affect the cart.
        2. Dynamic Pricing: The price and tax are always recalculated based on the current values in the products table. */
        return DB::table('settings')->value('pricing_policy') ?? 'static';
    }
    function getTaxPercentage(): string
    {
        return (DB::table('settings')->value('tax_percentage') ?? 14)/100;
    }
}

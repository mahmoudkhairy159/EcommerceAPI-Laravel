<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            LaratrustSeeder::class,
            AdminSeeder::class,
            UserSeeder::class,
            VendorSeeder::class,
            SettingSeeder::class,
            PaypalSettingSeeder::class,
            StripeSettingSeeder::class,
            PageSeeder::class,
            AssetSeeder::class,
            HeroSliderSeeder::class,
            BannerSeeder::class,
            CategorySeeder::class,
            BrandSeeder::class,
            FlashSaleSeeder::class,
            CouponSeeder::class,
            ShippingRuleSeeder::class,
            ProductSeeder::class,
            CountrySeeder::class,
            StateSeeder::class,
            CitySeeder::class,

        ]);
    }
}

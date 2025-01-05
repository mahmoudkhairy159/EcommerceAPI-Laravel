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
            UserSeeder::class,
            AdminSeeder::class,
            VendorSeeder::class,
            SettingSeeder::class,
            PageSeeder::class,
            AssetSeeder::class,
            CategorySeeder::class,
            HeroSliderSeeder::class,
            BannerSeeder::class,
            ProductSeeder::class,

        ]);
    }
}

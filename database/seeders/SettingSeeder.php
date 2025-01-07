<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            'en' => [
                'title' => 'Ecommerce',
                'slogan' => 'Ecommerce',
                'summary' => 'Ecommerce',
            ],
            'sv' => [
                'title' => 'Ecommerce',
                'slogan' => 'Ecommerce',
                'summary' => 'Ecommerce',
            ],
            'ar' => [
                'title' => 'Ecommerce',
                'slogan' => 'Ecommerce',
                'summary' => 'Ecommerce',
            ],
        ];


        Setting::create($data);
    }
}

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
                'title' => 'Envirdian',
                'slogan' => 'Envirdian',
                'summary' => 'Envirdian',
            ],
            'sv' => [
                'title' => 'Envirdian',
                'slogan' => 'Envirdian',
                'summary' => 'Envirdian',
            ],
            'ar' => [
                'title' => 'Envirdian',
                'slogan' => 'Envirdian',
                'summary' => 'Envirdian',
            ],
        ];


        Setting::create($data);
    }
}

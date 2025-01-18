<?php

namespace Database\Seeders;

use App\Models\StripeSetting;
use Illuminate\Database\Seeder;

class StripeSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        StripeSetting::create([
            'client_id' => 'pk_test_51QdNeCPLgTnxPyHnH3jcoPAv3y8wW1hgfZDZ8fKyS65hGWOIbYLrBEpogz9zHrPO8pTP4DEJOGxOaFs6bN0rJR3T00RtoLAIl8',
            'client_secret' => 'sk_test_51QdNeCPLgTnxPyHnmH36ybj2iGLW3v9iEylW0j7CY8zstM9IMVCvhRsQhGpddRhrCaHsYahUMVTjuGZey1tWGxNQ00LA9ZAwF9',
            'app_id' => null,
            'mode' => 'sandbox', // or 'production'
            'currency' => 'USD', // or any other currency
            'status' => 1, // 1 for active, 0 for inactive
        ]);
    }
}

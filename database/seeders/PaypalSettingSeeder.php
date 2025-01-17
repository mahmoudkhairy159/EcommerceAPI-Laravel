<?php

namespace Database\Seeders;

use App\Models\PaypalSetting;
use App\Models\Setting;
use Illuminate\Database\Seeder;

class PaypalSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PaypalSetting::create([
            'client_id' => 'AV41V2z7ivRh2IkRZpLCgOccQdCVndahnHZqRAqIJ2qTVuLXBvEssWGlxUptfGb-LmbT5TB7_xexbTmX',
            'client_secret' => 'EO5I4EEKlUaSk24Oo10toRHmtNYfgeVjTXQQqD4pNuMmB0QtApEpnnxrEMWgTlJbpMBB3e4SgSZuMMBQ',
            'app_id' => 'APP-80W284485P519543T',
            'mode' => 'sandbox', // or 'production'
            'currency' => 'USD', // or any other currency
            'status' => 1, // 1 for active, 0 for inactive
        ]);
    }
}

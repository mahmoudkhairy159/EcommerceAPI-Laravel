<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $filePath = database_path('data/countries.json');
        $batchSize = 100; // Adjust this to a suitable batch size

        // Read the file and decode the JSON
        $countries = json_decode(file_get_contents($filePath), true);

        collect($countries)
            ->chunk($batchSize)
            ->each(function ($chunk) {
                foreach ($chunk as $country) {
                    Country::create([
                        'code' => $country['iso2'],
                        'phone_code' => $country['phone_code'],
                        'longitude' => $country['longitude'],
                        'latitude' => $country['latitude'],
                        'geometry' => $country['geometry'],
                        'ar' => [
                            'name' => $country['translations']['ar'] ?? $country['name']
                        ],
                        'en' => [
                            'name' => $country['translations']['en'] ?? $country['name']
                        ],
                        'sv' => [
                            'name' => $country['translations']['sv'] ?? $country['name']
                        ],
                        'de' => [
                            'name' => $country['translations']['de'] ?? $country['name']
                        ],
                        'es' => [
                            'name' => $country['translations']['es'] ?? $country['name']
                        ],
                        'fr' => [
                            'name' =>  $country['translations']['fr'] ?? $country['name']
                        ],
                        'zh' => [
                            'name' => $country['translations']['cn'] ?? $country['name']
                        ],
                    ]);
                }
            });
    }

}

<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */


     /*run for production */
    public function run()
    {

        $filePath = database_path('data/cities.json');
        $batchSize = 500; // Adjust this to a suitable batch size

        // Read the file and decode the JSON
        $cities = json_decode(file_get_contents($filePath), true);

        collect($cities)
            ->chunk($batchSize)
            ->each(function ($chunk) {
                $countryCodes = $chunk->pluck('country_code')->unique();
                $stateNames = $chunk->pluck('state_name')->unique();
                $countryMap = DB::table('countries')
                    ->whereIn('code', $countryCodes)
                    ->pluck('id', 'code')
                    ->toArray();

                $stateMap = DB::table('state_translations')
                ->whereIn('name', $stateNames)
                ->where('locale', 'en') // Adjust locale as necessary
                ->pluck('state_id', 'name')
                ->toArray();
                foreach ($chunk as $city) {
                    City::create([
                        'longitude' => $city['longitude'],
                        'latitude' => $city['latitude'],
                        'country_id' => $countryMap[$city['country_code']] ?? null,
                        'state_id' => $stateMap[$city['state_name']] ?? null,
                        'ar' => [
                            'name' => $city['name']
                        ],
                        'en' => [
                            'name' => $city['name']
                        ],
                        'sv' => [
                            'name' => $city['name']
                        ],
                        'de' => [
                            'name' => $city['name']
                        ],
                        'es' => [
                            'name' => $city['name']
                        ],
                        'fr' => [
                            'name' => $city['name']
                        ],
                        'zh' => [
                            'name' => $city['name']
                        ],
                    ]);
                }
            });
    }
         /*********************************end run for production **********************************************************/

    //  /*run for development */
    // public function run()
    // {
    //     $filePath = database_path('data/cities.json');
    //     $batchSize = 100; // Adjust this to a suitable batch size

    //     // Read the file and decode the JSON
    //     $cities = json_decode(file_get_contents($filePath), true);

    //     collect($cities)
    //         ->chunk($batchSize)
    //         ->each(function ($chunk) {
    //             $countryCodes = $chunk->pluck('country_code')->unique();
    //             $stateNames = $chunk->pluck('state_name')->unique();
    //             $countryMap =  DB::table('countries')
    //                 ->whereIn('code', $countryCodes)
    //                 ->pluck('id', 'code')
    //                 ->toArray();
    //             $stateMap =  DB::table('state_translations')
    //                 ->whereIn('name', $stateNames)
    //                 ->where('locale', 'en') // Adjust locale as necessary
    //                 ->pluck('state_id', 'name')
    //                 ->toArray();

    //             $cityCountPerCountry = [];
    //             foreach ($chunk as $city) {
    //                 $countryId = $countryMap[$city['country_code']] ?? null;
    //                 $stateId = $stateMap[$city['state_name']] ?? null;

    //                 if ($countryId) {
    //                     // Initialize or increment the count for the country
    //                     if (!isset($cityCountPerCountry[$countryId])) {
    //                         $cityCountPerCountry[$countryId] = 0;
    //                     }

    //                     // Only insert if the country hasn't reached 5 cities
    //                     if ($cityCountPerCountry[$countryId] < 5) {
    //                         City::create([
    //                             'longitude' => $city['longitude'],
    //                             'latitude' => $city['latitude'],
    //                             'country_id' => $countryId,
    //                             'state_id' => $stateId,
    //                             'ar' => [
    //                                 'name' => $city['name']
    //                             ],
    //                             'en' => [
    //                                 'name' => $city['name']
    //                             ],
    //                             'sv' => [
    //                                 'name' => $city['name']
    //                             ],
    //                             'de' => [
    //                                 'name' => $city['name']
    //                             ],
    //                             'es' => [
    //                                 'name' => $city['name']
    //                             ],
    //                             'fr' => [
    //                                 'name' => $city['name']
    //                             ],
    //                             'zh' => [
    //                                 'name' => $city['name']
    //                             ],
    //                         ]);

    //                         // Increment the count for the country
    //                         $cityCountPerCountry[$countryId]++;
    //                     }
    //                 }
    //             }
    //         });
    // }
    //      /*************************************end run for development*********************** */



}

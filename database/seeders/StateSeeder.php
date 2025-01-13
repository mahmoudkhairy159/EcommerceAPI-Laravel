<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\State;

class StateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run()
    {

        $filePath = database_path('data/states.json');
        $batchSize = 100; // Adjust this to a suitable batch size

        // Read the file and decode the JSON
        $states = json_decode(file_get_contents($filePath), true);

        collect($states)
            ->chunk($batchSize)
            ->each(function ($chunk) {
                $countryCodes = $chunk->pluck('country_code')->unique();
                $countryMap = DB::table('countries')
                    ->whereIn('code', $countryCodes)
                    ->pluck('id', 'code')
                    ->toArray();

                foreach ($chunk as $state) {
                    State::create([
                        'code' => $state['state_code'],
                        'longitude' => $state['longitude'],
                        'latitude' => $state['latitude'],
                        'country_id' => $countryMap[$state['country_code']] ?? null,
                        'ar' => [
                            'name' => $state['name']
                        ],
                        'en' => [
                            'name' => $state['name']
                        ],
                        'sv' => [
                            'name' => $state['name']
                        ],
                        'de' => [
                            'name' => $state['name']
                        ],
                        'es' => [
                            'name' => $state['name']
                        ],
                        'fr' => [
                            'name' => $state['name']
                        ],
                        'zh' => [
                            'name' => $state['name']
                        ],
                    ]);
                }
            });
    }



}

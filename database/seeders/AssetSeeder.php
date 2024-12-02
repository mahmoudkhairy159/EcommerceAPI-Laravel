<?php

namespace Database\Seeders;

use App\Models\Asset;
use Illuminate\Database\Seeder;

class AssetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //home page Assets
        Asset::Create([
            'name' => 'home_page_company_profile',
            'image' => 'assets/default.png',
            'page_id' => 1,
        ]);

        Asset::Create([
            'name' => 'home_page_Promotion_image_1',
            'image' => 'assets/default.png',
            'page_id' => 1,
        ]);
        Asset::Create([
            'name' => 'home_page_Promotion_image_2',
            'image' => 'assets/default.png',
            'page_id' => 1,
        ]);
        Asset::Create([
            'name' => 'home_page_approvement',
            'image' => 'assets/default.png',
            'page_id' => 1,
        ]);
        Asset::Create([
            'name' => 'home_page_service_electrical_image',
            'image' => 'assets/default.png',
            'page_id' => 1,
        ]);
        Asset::Create([
            'name' => 'home_page_service_telecom_image',
            'image' => 'assets/default.png',
            'page_id' => 1,
        ]);
        Asset::Create([
            'name' => 'home_page_service_other_description',
            'image' => 'assets/default.png',
            'page_id' => 1,
        ]);
        //home page Assets

        //    //about us page Assets
        // Asset::Create([
        //     'name' => 'about_us_page_image_1',
        //     'image' =>'assets/default.png',
        //     'page_id' => 2,
        // ]);
        // Asset::Create([
        //     'name' => 'about_us_page_image_2',
        //     'image' => 'assets/default.png',
        //     'page_id' => 2,
        // ]); Asset::Create([
        //     'name' => 'about_us_page_image_3',
        //     'image' => 'assets/default.png',
        //     'page_id' => 2,
        // ]); Asset::Create([
        //     'name' => 'about_us_page_image_4',
        //     'image' =>'assets/default.png',
        //     'page_id' => 2,
        // ]);
        //    //about us page Assets

    }

}

<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Page::create([
            'name' => ' Home Page',
            'content' => [
                'line_title_1' => "line_title_1",
                'line_title_2' => "line_title_2",
                'line_title_3' => "line_title_3",
                'line_title_4' => "line_title_4",
                'line_description_1' => "In today's fast-paced world, effective communication is more critical than ever. Whether in professional settings or personal interactions, the ability to convey ideas clearly and succinctly can make a significant impact. By honing our communication skills, we not only improve our relationships but also enhance our overall success.",
                'line_description_2' => "In today's fast-paced world, effective communication is more critical than ever. Whether in professional settings or personal interactions, the ability to convey ideas clearly and succinctly can make a significant impact. By honing our communication skills, we not only improve our relationships but also enhance our overall success.",
                'line_description_3' => "In today's fast-paced world, effective communication is more critical than ever. Whether in professional settings or personal interactions, the ability to convey ideas clearly and succinctly can make a significant impact. By honing our communication skills, we not only improve our relationships but also enhance our overall success.",
                'line_description_4' => "In today's fast-paced world, effective communication is more critical than ever. Whether in professional settings or personal interactions, the ability to convey ideas clearly and succinctly can make a significant impact. By honing our communication skills, we not only improve our relationships but also enhance our overall success.",
                'focused_description' => "In today's fast-paced world, effective communication is more critical than ever. Whether in professional settings or personal interactions, the ability to convey ideas clearly and succinctly can make a significant impact. By honing our communication skills, we not only improve our relationships but also enhance our overall success.",
                'transparency_description' => "In today's fast-paced world, effective communication is more critical than ever. Whether in professional settings or personal interactions, the ability to convey ideas clearly and succinctly can make a significant impact. By honing our communication skills, we not only improve our relationships but also enhance our overall success.",
                'reliable_products_description' => "In today's fast-paced world, effective communication is more critical than ever. Whether in professional settings or personal interactions, the ability to convey ideas clearly and succinctly can make a significant impact. By honing our communication skills, we not only improve our relationships but also enhance our overall success.",
                'advertisers_count' => "70000",
                'visitors_count' => "550000",
                'products_count' => "10000",
                'service_electrical_description' => 'service_electrical_description',
                'service_telecom_description' => 'service_telecom_description',
                'service_other_description' => 'service_other_description',
                "hero_status" => 1,
                "our_values_status" => 1,
                "featured_products_status" => 1,
                "featured_services_status" => 1,
                "lines_status" => 1,
                "statistics_status" => 1,
                "banner_status" => 1,
                "promotion_status" => 1,
            ],
        ]);

        Page::create([
            'name' => 'About Us Page',
            'content' => [
                'about_us_short_description' => "In today's fast-paced world, effective communication is more critical than ever. Whether in professional settings or personal interactions, the ability to convey ideas clearly and succinctly can make a significant impact. By honing our communication skills, we not only improve our relationships but also enhance our overall success.",
                // 'about_us_long_description_1' => "In today's fast-paced world, effective communication is more critical than ever. Whether in professional settings or personal interactions, the ability to convey ideas clearly and succinctly can make a significant impact. By honing our communication skills, we not only improve our relationships but also enhance our overall success.",
                // 'about_us_long_description_2' => "In today's fast-paced world, effective communication is more critical than ever. Whether in professional settings or personal interactions, the ability to convey ideas clearly and succinctly can make a significant impact. By honing our communication skills, we not only improve our relationships but also enhance our overall success.",
                // 'about_us_long_description_3' => "In today's fast-paced world, effective communication is more critical than ever. Whether in professional settings or personal interactions, the ability to convey ideas clearly and succinctly can make a significant impact. By honing our communication skills, we not only improve our relationships but also enhance our overall success.",
                // 'about_us_long_description_4' => "In today's fast-paced world, effective communication is more critical than ever. Whether in professional settings or personal interactions, the ability to convey ideas clearly and succinctly can make a significant impact. By honing our communication skills, we not only improve our relationships but also enhance our overall success.",
            ],
        ]);
    }

}

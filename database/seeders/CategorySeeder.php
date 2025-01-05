<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $electronics = Category::create(['name' => 'Electronics']);
        $electronics->children()->createMany([
            ['name' => 'Mobile Phones'],
            ['name' => 'Laptops'],
        ]);

        $fashion = Category::create(['name' => 'Fashion']);
        $fashion->children()->createMany([
            ['name' => 'Men'],
            ['name' => 'Women'],
        ]);
    }
}

<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Brand;
use App\Models\Category;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         $admin = Admin::inRandomOrder()->first(); // Randomly pick an admin for created_by and updated_by

         // Creating sample brands
         Brand::create([
             'name' => 'Brand A',
             'slug' => 'brand-a',
             'image' => 'https://via.placeholder.com/150', // Placeholder image URL
             'short_description' => 'Short description of Brand A.',
             'long_description' => 'Detailed description of Brand A.',
             'long_description_status' => 1,
             'brief' => 'Brief description of Brand A.',
             'code' => 'BRAND-A123',
             'created_by' => $admin ? $admin->id : null,
             'updated_by' => $admin ? $admin->id : null,
             'status' => 1, // Active
             'is_featured' => 1, // Featured
             'serial' => 1,
         ]);

         Brand::create([
             'name' => 'Brand B',
             'slug' => 'brand-b',
             'image' => 'https://via.placeholder.com/150',
             'short_description' => 'Short description of Brand B.',
             'long_description' => 'Detailed description of Brand B.',
             'long_description_status' => 1,
             'brief' => 'Brief description of Brand B.',
             'code' => 'BRAND-B123',
             'created_by' => $admin ? $admin->id : null,
             'updated_by' => $admin ? $admin->id : null,
             'status' => 1, // Active
             'is_featured' => 0, // Not Featured
             'serial' => 2,
         ]);

         Brand::create([
             'name' => 'Brand C',
             'slug' => 'brand-c',
             'image' => 'https://via.placeholder.com/150',
             'short_description' => 'Short description of Brand C.',
             'long_description' => 'Detailed description of Brand C.',
             'long_description_status' => 1,
             'brief' => 'Brief description of Brand C.',
             'code' => 'BRAND-C123',
             'created_by' => $admin ? $admin->id : null,
             'updated_by' => $admin ? $admin->id : null,
             'status' => 1, // Active
             'is_featured' => 1, // Featured
             'serial' => 3,
         ]);
    }
}

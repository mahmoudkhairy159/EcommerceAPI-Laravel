<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Vendor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(){
    return [
        'name' => $this->faker->word(),
        'slug' => $this->faker->slug(),
        'code' => $this->faker->unique()->word(),
        'seo_description' => $this->faker->sentence(),
        'seo_keys' => $this->faker->words(5, true),
        'image' => $this->faker->imageUrl(640, 480, 'product'),
        'video_url' => $this->faker->url(),
        'vendor_id' => Vendor::inRandomOrder()->first()->id, // Choose a random vendor from existing vendors
        'category_id' => Category::inRandomOrder()->first()->id, // Choose a random category from existing categories
        'brand_id' => Brand::inRandomOrder()->first()->id, // Choose a random brand from existing brands
        'price' => $this->faker->randomFloat(2, 10, 1000),
        'offer_price' => $this->faker->randomFloat(2, 5, 900),
        'offer_start_date' => $this->faker->dateTimeBetween('-1 year', 'now'),
        'offer_end_date' => $this->faker->dateTimeBetween('now', '+1 year'),
        'currency' => 'USD', // You can adjust this as per your requirement
        'quantity' => $this->faker->numberBetween(1, 100),
        'alert_stock_quantity' => $this->faker->numberBetween(1, 10),
        'short_description' => $this->faker->sentence(),
        'long_description' => $this->faker->paragraph(),
        'return_policy' => $this->faker->text(200),
        'is_featured' => $this->faker->boolean(),
        'is_top' => $this->faker->boolean(),
        'is_best' => $this->faker->boolean(),
        'approval_status' => $this->faker->randomElement([0, 1, 2]), // Pending, Approved, Rejected
        'status' => $this->faker->randomElement([0, 1]), // Inactive, Active
        'serial' => 1,
    ];
    }
}

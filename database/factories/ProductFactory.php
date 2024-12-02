<?php

namespace Database\Factories;

use App\Models\Product;
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
    public function definition()
    {
        return [
            'name' => $this->faker->word(),
            'code' => $this->faker->unique()->numerify('P####'),
            'image' => 'default.png',
            'video_url' => $this->faker->url(),
            'rank' => $this->faker->numberBetween(0, 100),
            'selling_price' => $this->faker->randomFloat(2, 10, 1000),
            'cost_price' => $this->faker->randomFloat(2, 5, 500),
            'discount' => $this->faker->randomFloat(2, 0, 50),
            'currency' => $this->faker->currencyCode(),
            'quantity' => $this->faker->numberBetween(1, 100),
            'alert_stock_quantity' => $this->faker->numberBetween(1, 10),
            'order_type' => $this->faker->randomElement(['single', 'bulk']),
            'short_description' => $this->faker->sentence(),
            'long_description' => $this->faker->paragraph(),
            'return_policy' => $this->faker->paragraph(),
            'rate' => $this->faker->numberBetween(0, 5),
            'category_id' => null, // Assign appropriate category ID or leave null for seeding
            'brand_id' => null, // Assign appropriate brand ID or leave null for seeding
            'main_category' =>$this->faker->randomElement(['0', '1','2']) ,
        ];
    }
}

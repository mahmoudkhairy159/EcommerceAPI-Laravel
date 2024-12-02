<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class HeroSliderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'heading' => $this->faker->sentence,
            'paragraph' => $this->faker->paragraph,
            'image' => 'default.png',
            'rank' => '1',
            'admin_id' => '1',
        ];
    }
}

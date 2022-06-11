<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {   
        $name = $this->faker->unique()->name();
        return [
            'name' => $name,
            'description' => $this->faker->text(),
            'slug' => Str::of($name)->slug('-'),
            'image' => 'path.jpg', // password
            'minimum_stock' => 5,
            'sale_price' => number_format(rand(10, 1000), 2, '.', ','),
            'purchase_price' => number_format(rand(100, 100), 2,'.', ',')
        ];
    }
}

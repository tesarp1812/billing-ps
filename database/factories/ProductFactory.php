<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition()
    {
        return [
            'category' => $this->faker->randomElement(['Food', 'Drink', 'Snack']),
            'name' => $this->faker->unique()->words(2, true),
            'price' => $this->faker->randomElement([5000, 7000, 10000, 12000]),
            'stock' => $this->faker->numberBetween(10, 100),
            'is_active' => true,
        ];
    }
}

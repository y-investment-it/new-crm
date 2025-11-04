<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'sku' => strtoupper($this->faker->unique()->bothify('PRD-####')),
            'name' => $this->faker->words(3, true),
            'price' => $this->faker->randomFloat(2, 50, 1500),
            'stock' => $this->faker->numberBetween(0, 200),
            'status' => $this->faker->randomElement(['active', 'inactive']),
        ];
    }
}

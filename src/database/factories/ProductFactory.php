<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $conditions = ['good', 'no_obvious_damage', 'some_damage', 'bad'];

        return [
            'user_id'     => User::factory(), // 出品者
            'name'        => $this->faker->word,
            'brand'       => $this->faker->company,
            'price'       => $this->faker->numberBetween(500, 50000),
            'description' => $this->faker->sentence,
            'condition'   => $this->faker->randomElement($conditions),
            'image_path'  => $this->faker->imageUrl(640, 480, 'products', true),
            'sold_at'     => null, // 最初は未購入
        ];
    }
}

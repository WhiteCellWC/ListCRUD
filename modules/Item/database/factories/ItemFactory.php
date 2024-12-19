<?php

namespace Modules\Item\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Category\Model\Category;
use Modules\Item\Model\Item;

class ItemFactory extends Factory
{
    protected $model = Item::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word(),
            'category_id' => Category::inRandomOrder()->first()->id,
            'price' => $this->faker->randomFloat(2, 10, 1000),
            'description' => $this->faker->sentence(), // Random sentence for description
            'item_condition' => $this->faker->randomElement(['New', 'Used', 'Good Second Hand']),
            'item_type' => $this->faker->randomElement(['Sell', 'Buy', 'For Exchange']),
            'is_published' => $this->faker->boolean(80),
            'image' => $this->faker->imageUrl(640, 480, 'items', true),
            'owner_name' => $this->faker->name(),
            'contact_number' => $this->faker->phoneNumber(),
            'address' => $this->faker->address(),
            'latitude' => $this->faker->latitude(),
            'longitude' => $this->faker->longitude(),
        ];
    }
}

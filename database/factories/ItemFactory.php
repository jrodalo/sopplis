<?php

namespace Database\Factories;

use App\Models\Item;
use Illuminate\Database\Eloquent\Factories\Factory;

class ItemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Item::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'cart_id' => 1,
            'name' => $this->faker->word,
            'count' => $this->faker->numberBetween(1, 100),
            'visible' => $this->faker->numberBetween(0, 1),
            'done' => $this->faker->numberBetween(0, 1),
        ];
    }

    /**
     * Indicate that the item is a favorite.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function favorite()
    {
        return $this->state(function (array $attributes) {
            return [
                'count' => $this->faker->numberBetween(10, 100),
                'visible' => 0,
                'done' => 0
            ];
        });
    }
}

<?php

namespace Azaharizaman\LaravelInventoryManagement\Database\Factories;

use Azaharizaman\LaravelInventoryManagement\Models\Item;
use Azaharizaman\LaravelInventoryManagement\Models\Location;
use Azaharizaman\LaravelInventoryManagement\Models\Stock;
use Illuminate\Database\Eloquent\Factories\Factory;

class StockFactory extends Factory
{
    protected $model = Stock::class;

    public function definition(): array
    {
        return [
            'itemable_id' => Item::factory(),
            'itemable_type' => Item::class,
            'location_id' => Location::factory(),
            'quantity' => $this->faker->randomFloat(4, 0, 500),
        ];
    }
}

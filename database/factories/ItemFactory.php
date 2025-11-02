<?php

namespace Azaharizaman\LaravelInventoryManagement\Database\Factories;

use Azaharizaman\LaravelInventoryManagement\Models\Item;
use Azaharizaman\LaravelUomManagement\Models\UomUnit;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ItemFactory extends Factory
{
    protected $model = Item::class;

    public function definition()
    {
        return [
            'name' => $this->faker->words(2, true),
            'sku' => Str::upper($this->faker->unique()->lexify('SKU-????')), 
            'uom_id' => UomUnit::factory(),
        ];
    }
}

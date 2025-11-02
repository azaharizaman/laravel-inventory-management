<?php

namespace Azaharizaman\LaravelInventoryManagement\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Azaharizaman\LaravelInventoryManagement\Models\Location;

class LocationFactory extends Factory
{
    protected $model = Location::class;

    public function definition()
    {
        return [
            'name' => $this->faker->city,
        ];
    }
}

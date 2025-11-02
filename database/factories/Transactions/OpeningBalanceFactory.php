<?php

namespace Azaharizaman\LaravelInventoryManagement\Database\Factories\Transactions;

use Azaharizaman\LaravelInventoryManagement\Models\Stock;
use Azaharizaman\LaravelInventoryManagement\Models\Transactions\OpeningBalance;
use Illuminate\Database\Eloquent\Factories\Factory;

class OpeningBalanceFactory extends Factory
{
    protected $model = OpeningBalance::class;

    public function definition(): array
    {
        $initialQuantity = $this->faker->randomFloat(4, 0, 500);

        return [
            'stock_id' => Stock::factory(),
            'initial_quantity' => $initialQuantity,
            'recorded_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'note' => $this->faker->optional()->sentence(),
        ];
    }
}

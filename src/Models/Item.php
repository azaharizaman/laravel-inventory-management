<?php

namespace Azaharizaman\LaravelInventoryManagement\Models;

use Azaharizaman\LaravelInventoryManagement\Concerns\IsItem;
use Azaharizaman\LaravelInventoryManagement\Contracts\Item as ItemContract;
use Azaharizaman\LaravelInventoryManagement\Database\Factories\ItemFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config as ConfigFacade;

class Item extends Model implements ItemContract
{
    use HasFactory;
    use IsItem;

    protected $guarded = [];

    public function getSku(): string
    {
        return (string) $this->sku;
    }

    public function getTable(): string
    {
        return ConfigFacade::get('inventory-management.table_names.items', parent::getTable());
    }

    protected static function newFactory(): Factory
    {
        return ItemFactory::new();
    }
}

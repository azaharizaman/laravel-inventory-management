<?php
return [
    // Map framework contracts to the package defaults. Consumers may override these.
    'models' => [
        'item' => \Azaharizaman\LaravelInventoryManagement\Models\Item::class,
        'location' => \Azaharizaman\LaravelInventoryManagement\Models\Location::class,
        'stock' => \Azaharizaman\LaravelInventoryManagement\Models\Stock::class,
        'stock_movement' => \Azaharizaman\LaravelInventoryManagement\Models\StockMovement::class,
        'unit' => \Azaharizaman\LaravelUomManagement\Models\UomUnit::class,

        // Transaction models
        'transactions' => [
            'opening_balance' => \Azaharizaman\LaravelInventoryManagement\Models\Transactions\OpeningBalance::class,
            'stock_in' => \Azaharizaman\LaravelInventoryManagement\Models\Transactions\StockIn::class,
            'stock_out' => \Azaharizaman\LaravelInventoryManagement\Models\Transactions\StockOut::class,
            'stock_transfer' => \Azaharizaman\LaravelInventoryManagement\Models\Transactions\StockTransfer::class,
            'stock_adjustment' => \Azaharizaman\LaravelInventoryManagement\Models\Transactions\StockAdjustment::class,
        ],
    ],

    // Define custom table names for publishable migrations.
    'table_names' => [
        'items' => 'items',
        'locations' => 'locations',
        'stocks' => 'stocks',
        'stock_movements' => 'stock_movements',
        'opening_balances' => 'transaction_opening_balances',
        'stock_ins' => 'transaction_stock_ins',
        'stock_outs' => 'transaction_stock_outs',
        'stock_transfers' => 'transaction_stock_transfers',
        'stock_adjustments' => 'transaction_stock_adjustments',
    ],

    // Default precision for decimal quantities across the package.
    'quantity_precision' => 4,

    // Key used to generate serial numbers for stock movements.
    'serial_numbering_key' => 'inventory-movement',
];

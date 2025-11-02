# Project Requirements Document: laravel-inventory-management

**Author**: @azaharizaman
**Date**: 2025-11-02
**Version**: 1.0.0

## 1. Project Overview

`laravel-inventory-management` will be a headless, contract-driven Laravel package for managing inventory. It provides a robust backend for tracking stock levels, movements, and valuation without imposing any UI constraints.

The package is designed to be highly flexible, allowing developers to integrate it with their existing application models (e.g., `Product`, `Warehouse`) seamlessly. It will be built as part of an ecosystem, relying on `azaharizaman/laravel-uom-management` and `azaharizaman/laravel-serial-numbering` to ensure data integrity and consistency.

### Core Principles

*   **Non-Constricting**: The package will not force users into a rigid data structure. It will adapt to the user's existing models.
*   **Contract-Driven**: All core logic will depend on interfaces (Contracts), not concrete model implementations.
*   **Pluggable & Extensible**: Functionality can be extended through Events, Observers, and custom model implementations.
*   **Integrated Ecosystem**: The package will leverage `laravel-uom-management` and `laravel-serial-numbering` as first-class dependencies.
*   **Data Integrity**: Every stock movement is an immutable event, uniquely identified by a serial number, creating an auditable, double-entry-like ledger.

## 2. Core Dependencies

The package will require the following external packages in `composer.json`:

*   `azaharizaman/laravel-uom-management`: For handling units of measure for all inventory items.
*   `azaharizaman/laravel-serial-numbering`: For generating unique, non-duplicate serial numbers for every stock transaction.

## 3. Package Structure

```
laravel-inventory-management/
├── config/
│   └── inventory-management.php
├── database/
│   ├── factories/
│   │   ├── ItemFactory.php
│   │   ├── LocationFactory.php
│   │   ├── StockFactory.php
│   │   └── StockMovementFactory.php
│   └── migrations/
│       ├── xxxx_xx_xx_xxxxxx_create_items_table.php
│       ├── xxxx_xx_xx_xxxxxx_create_locations_table.php
│       ├── xxxx_xx_xx_xxxxxx_create_stocks_table.php
│       └── xxxx_xx_xx_xxxxxx_create_stock_movements_table.php
├── src/
│   ├── Concerns/
│   │   ├── IsItem.php
│   │   └── IsLocation.php
│   ├── Contracts/
│   │   ├── Item.php
│   │   └── Location.php
│   ├── Events/
│   │   ├── StockAdjusted.php
│   │   ├── StockMoving.php
│   │   ├── StockMoved.php
│   │   └── LowStockThresholdReached.php
│   ├── Exceptions/
│   │   └── InsufficientStockException.php
│   ├── Facades/
│   │   └── Inventory.php
│   ├── Models/
│   │   ├── Item.php
│   │   ├── Location.php
│   │   ├── Stock.php
│   │   └── StockMovement.php
│   ├── Observers/
│   │   └── StockObserver.php
│   ├── Services/
│   │   └── InventoryService.php
│   └── InventoryManagementServiceProvider.php
├── .gitignore
├── composer.json
└── README.md
```

## 4. Entities and Relationships

The package revolves around four primary entities. By default, the package provides models for these, but the `Item` and `Location` can be replaced by user-defined models.

*   **Item**: The "what". Any Eloquent model that can be stocked.
    *   *Relationship*: Polymorphic `morphs('itemable')` on the `stocks` table.
*   **Location**: The "where". Any Eloquent model representing a place where stock is held.
    *   *Relationship*: A `Location` `hasMany` `Stock` records.
*   **Stock**: The "how many". Represents the quantity of a specific `Item` at a specific `Location`.
    *   *Relationship*: A `Stock` record `belongsTo` a `Location` and `morphTo` an `Item`. It also `hasMany` `StockMovement` records.
*   **StockMovement**: The "why and when". An immutable ledger entry detailing a single change in stock quantity.
    *   *Relationship*: A `StockMovement` `belongsTo` a `Stock` record.

```
+----------------+      +----------------+
| User's Model   |      |   Location     |
| (e.g. Product) |      | (Contract)     |
| (Implements    |      +-------+--------+
|  Item Contract)|              | 1
+-------+--------+              |
        |                       | hasMany
        | morphMany             |
        |                       v
+-------+-----------------------+--------+
|                Stock                   |
+-----------------------+----------------+
| itemable_id (morph)   | location_id    |
| itemable_type (morph) | quantity       |
+-------+--------------------------------+
        | 1
        |
        | hasMany
        |
        v
+-------+--------------------------------+
|               StockMovement            |
+----------------------------------------+
| stock_id          | serial_number (unique) |
| quantity_change   | reason                 |
+----------------------------------------+
```

## 5. Configuration (`config/inventory-management.php`)

A configuration file will allow users to customize the package's behavior without altering its code.

```php
<?php
return [
    // Map contracts to the user's own models
    'models' => [
        'item' => \Azaharizaman\InventoryManagement\Models\Item::class,
        'location' => \Azaharizaman\InventoryManagement\Models\Location::class,
        'stock' => \Azaharizaman\InventoryManagement\Models\Stock::class,
        'stock_movement' => \Azaharizaman\InventoryManagement\Models\StockMovement::class,
    ],

    // Define custom table names
    'table_names' => [
        'items' => 'items',
        'locations' => 'locations',
        'stocks' => 'stocks',
        'stock_movements' => 'stock_movements',
    ],

    // Key used to generate serial numbers for stock movements
    'serial_numbering_key' => 'inventory-movement',
];
```

## 6. Contracts (The "Rules")

Contracts define the minimum functionality required for a model to interact with the inventory system.

*   `src/Contracts/Item.php`:
    *   `stocks(): MorphMany`: Must return the relationship to its stock records.
    *   `getSku(): string`: Must return a unique SKU or part number.
    *   `uom(): BelongsTo`: Must return the relationship to a `UnitOfMeasure` model from the UOM package.
*   `src/Contracts/Location.php`:
    *   `stocks(): HasMany`: Must return the relationship to its stock records.
    *   `getLocationName(): string`: Must return a human-readable name for the location.

## 7. Traits (The "Helpers")

Traits will be provided to make it trivial for a user to implement the contracts on their own models.

*   `src/Concerns/IsItem.php`: Provides the `stocks()` and `uom()` relationship methods. The user will only need to implement `getSku()`.
*   `src/Concerns/IsLocation.php`: Provides the `stocks()` relationship method. The user will only need to implement `getLocationName()`.

## 8. Database Migrations and Factories

Four migration files will be created, using the table names from the config file.

1.  `...create_items_table.php`: A default table for users who don't have one. Includes `sku`, `name`, `uom_id`.
2.  `...create_locations_table.php`: A default table for locations.
3.  `...create_stocks_table.php`: The core table.
    *   `morphs('itemable')`: Polymorphic relation to the item model.
    *   `foreignIdFor(config('models.location'))`: Foreign key to the locations table.
    *   `decimal('quantity')`: The current quantity on hand.
    *   A `unique` constraint on `['itemable_type', 'itemable_id', 'location_id']`.
4.  `...create_stock_movements_table.php`: The immutable ledger.
    *   `foreignIdFor(config('models.stock'))`: Foreign key to the stocks table.
    *   `string('serial_number')->unique()`: The unique transaction ID from the serial numbering package.
    *   `decimal('quantity_before')`, `decimal('quantity_change')`, `decimal('quantity_after')`: For a full audit trail.
    *   `morphs('reference')`: An optional polymorphic relation to a source document (e.g., `PurchaseOrder`, `Sale`).

**Factories** will be created for each default model to facilitate testing and database seeding.

## 9. Core Service and Facade

*   `src/Services/InventoryService.php`: The main service class containing all business logic.
    *   `moveStock(Item, Location, float, ?string, ?object)`: The primary method for all stock movements. It will generate a serial number and create an immutable `StockMovement` record.
    *   `addStock(...)`: A convenience wrapper around `moveStock` for positive quantities.
    *   `removeStock(...)`: A convenience wrapper for negative quantities, which also checks for sufficient stock.
    *   `transferStock(Item, Location $from, Location $to, float)`: Creates two `StockMovement` records (a negative from the source, a positive to the destination) under a single database transaction.
    *   `adjustStock(Item, Location, float, string $reason)`: A dedicated method for manual stock adjustments.
    *   `getStockLevel(Item, ?Location)`: Retrieves the current stock quantity for an item, either globally or at a specific location.

*   `src/Facades/Inventory.php`: A simple, static-like interface providing access to the `InventoryService`. Example: `Inventory::addStock(...)`.

## 10. Events and Observers

The package will be highly extensible through events.

*   **Events**:
    *   `StockMoving`: Fired *before* a stock movement is saved. Can be used for validation. Listeners returning `false` will cancel the transaction.
    *   `StockMoved`: Fired *after* a stock movement has been successfully saved. Used for triggering side-effects like notifications.
    *   `StockAdjusted`: A specific event fired after a manual adjustment.
    *   `LowStockThresholdReached`: Fired when a stock's quantity drops below its defined `stock_alert_threshold`.

*   **Observers**:
    *   `StockObserver`: An observer on the `Stock` model can be used to automatically fire the `LowStockThresholdReached` event when the `quantity` attribute is updated.

This comprehensive plan provides a clear roadmap for developing the `laravel-inventory-management` package, ensuring it meets the goals of flexibility, integrity, and seamless integration.
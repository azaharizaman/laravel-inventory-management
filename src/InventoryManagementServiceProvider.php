<?php

namespace Azaharizaman\LaravelInventoryManagement;

use Illuminate\Support\ServiceProvider;

class InventoryManagementServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/inventory-management.php' => $this->app->configPath('inventory-management.php'),
            ], 'inventory-management-config');

            $this->publishes([
                __DIR__.'/../database/migrations/' => $this->app->databasePath('migrations'),
            ], 'inventory-management-migrations');
        }
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/inventory-management.php', 'inventory-management'
        );
    }
}

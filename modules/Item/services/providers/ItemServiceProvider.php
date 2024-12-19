<?php

namespace Modules\Item\Services\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Item\Services\Implementations\ItemCommand;
use Modules\Item\Services\Implementations\ItemQuery;
use Modules\Item\Services\ItemCommandInterface;
use Modules\Item\Services\ItemQueryInterface;

class ItemServiceProvider extends ServiceProvider
{
    public function register() {}

    public function boot()
    {

        $this->loadMigrationsFrom(__DIR__ . "/../../database/migrations");
        $this->mergeConfigFrom(__DIR__ . "/../../config.php", 'item');
    }
}

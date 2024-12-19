<?php

namespace Modules\Category\Services\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Category\Services\CategoryCommandInterface;
use Modules\Category\Services\CategoryQueryInterface;
use Modules\Category\Services\Implementations\CategoryCommand;
use Modules\Category\Services\Implementations\CategoryQuery;

class CategoryServiceProvider extends ServiceProvider
{
    public function register() {}
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . "/../../database/migrations");
        $this->mergeConfigFrom(__DIR__ . "/../../config.php", 'category');
    }
}

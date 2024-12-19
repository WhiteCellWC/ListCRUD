<?php

return [
    App\Providers\AppServiceProvider::class,
    App\Providers\FortifyServiceProvider::class,
    App\Providers\JetstreamServiceProvider::class,
    Modules\Category\Services\Providers\CategoryServiceProvider::class,
    Modules\Item\Services\Providers\ItemServiceProvider::class,
];

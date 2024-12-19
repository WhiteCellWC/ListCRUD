<?php

namespace Modules\Category\Services\Implementations;

use Modules\Category\Model\Category;
use Modules\Category\Services\CategoryQueryInterface;

class CategoryQuery implements CategoryQueryInterface
{
    public function all()
    {
        return Category::on('mysql:read')->get();
    }
}

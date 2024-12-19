<?php

namespace Modules\Category\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Category\Model\Category;

class CategorySeeder extends Seeder
{
    public function run()
    {
        Category::factory()->count(10)->create();
    }
}

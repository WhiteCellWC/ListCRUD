<?php

namespace Modules\Item\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Item\Model\Item;

class ItemSeeder extends Seeder
{
    public function run()
    {
        Item::factory()->count(10)->create();
    }
}

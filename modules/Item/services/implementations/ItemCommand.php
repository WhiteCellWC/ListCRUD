<?php

namespace Modules\Item\Services\Implementations;

use Modules\Item\Contracts\StoreItem;
use Modules\Item\Contracts\UpdateItem;
use Modules\Item\Model\Item;
use Modules\Item\Services\ItemCommandInterface;

class ItemCommand implements ItemCommandInterface
{
    public function store(StoreItem $storeItem)
    {
        return Item::create($storeItem->toArray());
    }
    public function delete(int $id)
    {
        return Item::find($id)->delete();
    }

    public function update(int $id, UpdateItem $updateItem)
    {
        return Item::find($id)->update($updateItem->toArray());
    }

    public function togglePublish(int $id)
    {
        $item = Item::find($id);
        $item->is_published = !$item->is_published;
        $item->save();
        return $item;
    }
}

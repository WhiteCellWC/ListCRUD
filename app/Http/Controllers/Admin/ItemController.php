<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Item\ItemStoreRequest;
use App\Http\Requests\Admin\Item\ItemUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Modules\Category\Services\Implementations\CategoryQuery;
use Modules\Common\FileUpload\FileUploadService;
use Modules\Item\Contracts\StoreItem;
use Modules\Item\Contracts\UpdateItem;
use Modules\Item\Model\Item;
use Modules\Item\Services\Implementations\ItemCommand;
use Modules\Item\Services\Implementations\ItemQuery;

class ItemController extends Controller
{
    public function __construct(protected ItemQuery $itemQuery, protected ItemCommand $itemCommand, protected CategoryQuery $categoryQuery, protected FileUploadService $fileUploadService) {}

    public function index(Request $request)
    {
        return Inertia::render('Items/Index');
    }

    public function store(ItemStoreRequest $request)
    {
        $storedPath = $this->fileUploadService->upload(
            '/images/items/',
            config('item.image_max_size'),
            $request->file('image')
        );

        $storeItemContract = new StoreItem([...$request->except('image'), 'image' => $storedPath]);
        $this->itemCommand->store($storeItemContract);

        return Inertia::location(route('items.index'));
    }

    public function create()
    {
        $category = $this->categoryQuery->all();

        return Inertia::render('Items/Create', [
            'category' => $category
        ]);
    }

    public function show(int $id) {}

    public function edit(int $id)
    {
        $item = $this->itemQuery->find($id, ['category' => 'name']);

        $category = $this->categoryQuery->all();

        return Inertia::render('Items/Edit', [
            'item' => $item,
            'category' => $category
        ]);
    }

    public function update(ItemUpdateRequest $request, int $id,)
    {
        $storedPath = null;
        if ($request->hasFile('image')) {
            $storedPath = $this->fileUploadService->upload(
                '/images/items/',
                config('item.image_max_size'),
                $request->file('image')
            );
        }
        $data = $request->except('image');
        if ($storedPath) {
            $data['image'] = $storedPath;
            $item = $this->itemQuery->find($id);
            if ($item->image) {
                $this->fileUploadService->delete($item->image);
            }
        }

        $updateItemContract = new UpdateItem($data);
        $item = $this->itemCommand->update($id, $updateItemContract);

        return Inertia::location(route('items.index'));
    }

    public function destroy(int $id)
    {
        $this->itemCommand->delete($id);
        return response()->json(['message' => 'Item deleted successfully.']);
    }

    public function list(Request $request)
    {
        $options = [
            'relations' => ['category' => 'name']
        ];

        if ($request->get('sortBy')) $options['sortBy'] = $request->get('sortBy');
        if ($request->get('sortOrder')) $options['sortOrder'] = $request->get('sortOrder');
        if ($request->get('show')) $options['show'] = $request->get('show');

        $items = $this->itemQuery->getAllWithOptions($options);

        return response()->json($items);
    }

    public function changePublishStatus(int $id)
    {
        $this->itemCommand->togglePublish($id);
        return response()->json(['message' => 'Item status changed successfully']);
    }
}

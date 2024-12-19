<?php

namespace Modules\Item\Model;

use App\Models\Traits\UserTrackable;
use App\Models\Traits\Versionable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Category\Model\Category;
use Modules\Item\Database\Factories\ItemFactory;

class Item extends Model
{
    use HasFactory, Versionable, UserTrackable;
    protected $fillable = [
        "name",
        "category_id",
        "price",
        "description",
        "item_condition",
        "item_type",
        "is_published",
        "image",
        "owner_name",
        "contact_number",
        "address",
        "latitude",
        "longitude",
        "version",
        "created_by",
        "updated_by"
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    protected static function newFactory()
    {
        return ItemFactory::new();
    }
}

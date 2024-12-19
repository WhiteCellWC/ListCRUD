<?php

namespace Modules\Category\Model;

use App\Models\Traits\UserTrackable;
use App\Models\Traits\Versionable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Category\Database\Factories\CategoryFactory;
use Modules\Item\Model\Item;

class Category extends Model
{
    use HasFactory, Versionable, UserTrackable;
    protected $fillable = [
        "name",
        "parent_id",
        "description",
        "is_active",
        "version",
        "created_by",
        "updated_by"
    ];

    public function items()
    {
        return $this->hasMany(Item::class);
    }

    protected static function newFactory()
    {
        return CategoryFactory::new();
    }
}

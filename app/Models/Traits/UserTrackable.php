<?php

namespace App\Models\Traits;

use Illuminate\Support\Facades\Auth;

trait UserTrackable
{
    protected static function bootUserTrackable()
    {
        static::creating(function ($model) {
            $model->created_by = Auth::id() ?? null;
            $model->updated_by = Auth::id() ?? null;
        });

        static::updating(function ($model) {
            $model->updated_by = Auth::id() ?? null;
        });
    }
}

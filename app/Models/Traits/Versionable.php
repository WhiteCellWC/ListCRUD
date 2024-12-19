<?php

namespace App\Models\Traits;

trait Versionable
{
    protected static function bootVersionable()
    {
        static::creating(function ($model) {
            $model->version = 1;
        });

        static::updating(function ($model) {
            $model->version = intval($model->version) + 1;
        });
    }
}

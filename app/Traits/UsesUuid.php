<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait UsesUuid
{
    protected static function bootUsesUuid()
    {
        static::creating(function ($model) {
            do {
                $uuid = (string) Str::uuid();
            } while ($model::where($model->getKeyName(), $uuid)->exists());
    
            $model->{$model->getKeyName()} = $uuid;
        });
    }

    public function getIncrementing()
    {
        return false;
    }

    public function getKeyType()
    {
        return 'string';
    }
}

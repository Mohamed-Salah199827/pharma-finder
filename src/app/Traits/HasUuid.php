<?php

namespace App\Traits;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

trait HasUuid
{
    protected static function bootHasUuid()
    {
        static::creating(function ($model) {
            if ($model->isFillable('uuid') || Schema::hasColumn($model->getTable(), 'uuid')) {
                if (empty($model->uuid)) {
                    $model->uuid = (string) Str::uuid();
                }
            } else {
                if (empty($model->{$model->getKeyName()})) {
                    $model->{$model->getKeyName()} = (string) Str::uuid();
                }
            }
        });
    }

    // لو هتخلي الـ primary key هو الـ uuid
    public function getIncrementing()
    {
        return false;
    }

    public function getKeyType()
    {
        return 'string';
    }
}

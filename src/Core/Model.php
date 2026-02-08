<?php

namespace Ofernandoavila\TucanoCore\Core;

use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Support\Str;

class Model extends EloquentModel
{
    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    protected $casts = [];

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->{$model->getKeyName()} = (string) Str::uuid();
        });
    }
}

<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

trait Uuid
{
    protected static function boot()
    {
        parent::boot();

        static::creating(function (Model $model) {
            $model->uuid = (string) \Illuminate\Support\Str::uuid();
        });
    }
}

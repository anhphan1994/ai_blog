<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PlatformAccount extends Model
{
    use SoftDeletes;
    use Uuid;

    public $timestamps = true;

    protected $fillable = [
        'uuid',
        'platform_name',
        'username',
        'api_key',
        'password',
    ];

    const PLATFORM_WORDPRESS = 'wordpress';
}

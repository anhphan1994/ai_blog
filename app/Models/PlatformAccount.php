<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PlatformAccount extends Model
{
    use SoftDeletes;

    public $timestamps = true;
    protected $fillable = [
        'uuid',
        'platform_name',
        'username',
        'api_key',
        'password',
    ];
}

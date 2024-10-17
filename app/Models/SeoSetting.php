<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SeoSetting extends Model
{
    use HasFactory;
    use SoftDeletes;
    public $timestamps = true;
    protected $fillable = [
        'meta_title',
        'meta_description',
        'meta_keywords',
        'blog_post_id',
    ];
}

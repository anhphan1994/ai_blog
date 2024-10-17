<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Media extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'media';
    public $timestamps = true;
    protected $fillable = [
        'blog_post_id',
        'file_name',
        'file_url',
        'file_type',
    ];
}

<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlogPost extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Uuid;
    public $timestamps = true;
    protected $fillable = [
        'title',
        'slug',
        'outline',
        'content',
        'short_content',
        'status',
        'user_id',
        'published_at'
    ];

    const STATUS_PENDING = 'pending';

    const STATUS_GENERATING = 'generating';
    const STATUS_GENERATED = 'generated';
    const STATUS_DRAFT = 'draft';
    const STATUS_SCHEDULED = 'scheduled';
    const STATUS_PUBLISHED = 'published';
    const STATUS_DELETED = 'deleted';

}
